<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    if (!isset($_COOKIE['id']) || !ctype_digit($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    require "assets/php/db.php";
    require "assets/php/lang.php";
    require "assets/php/functions.php";

    $BUG_TABLE = "bug_reports";

    $currentUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    $errors = [];
    $success = false;

    $prefillUrl = isset($_GET['url']) ? clean_str($_GET['url'], 255) : '';

    $form = [
        'type' => 'bug',
        'severity' => 'medium',
        'title' => '',
        'description' => '',
        'page_url' => $prefillUrl,
        'steps' => '',
        'expected_result' => '',
        'actual_result' => '',
        'contact_email' => ''
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postedToken = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'], (string)$postedToken)) {
            $errors[] = "Érvénytelen munkamenet (CSRF). Frissítsd az oldalt és próbáld újra.";
        }

        $form['type'] = clean_str($_POST['type'] ?? 'bug', 32);
        $form['severity'] = clean_str($_POST['severity'] ?? 'medium', 16);
        $form['title'] = clean_str($_POST['title'] ?? '', 120);
        $form['description'] = clean_str($_POST['description'] ?? '', 5000);
        $form['page_url'] = clean_str($_POST['page_url'] ?? '', 255);
        $form['steps'] = clean_str($_POST['steps'] ?? '', 5000);
        $form['expected_result'] = clean_str($_POST['expected_result'] ?? '', 3000);
        $form['actual_result'] = clean_str($_POST['actual_result'] ?? '', 3000);
        $form['contact_email'] = clean_str($_POST['contact_email'] ?? '', 190);

        $allowedTypes = ['bug', 'feature', 'abuse', 'other'];
        $allowedSeverity = ['low', 'medium', 'high', 'critical'];

        if (!in_array($form['type'], $allowedTypes, true)) {
            $errors[] = "Érvénytelen kategória.";
        }
        if (!in_array($form['severity'], $allowedSeverity, true)) {
            $errors[] = "Érvénytelen prioritás.";
        }
        if ($form['title'] === '' || mb_strlen($form['title'], 'UTF-8') < 4) {
            $errors[] = "A cím legyen legalább 4 karakter.";
        }
        if ($form['description'] === '' || mb_strlen($form['description'], 'UTF-8') < 10) {
            $errors[] = "A leírás legyen legalább 10 karakter.";
        }
        if ($form['contact_email'] !== '' && !filter_var($form['contact_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "A megadott e-mail cím formátuma nem megfelelő.";
        }
        if ($form['page_url'] !== '' && !filter_var($form['page_url'], FILTER_VALIDATE_URL)) {
            $errors[] = "Az oldal linkje nem tűnik érvényes URL-nek.";
        }

        if (empty($errors)) {
            try {
                $ip = get_client_ip();
                $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
                $ipHash = $ip ? hash('sha256', $ip . '|' . $ua) : null;

                $sql = "INSERT INTO {$BUG_TABLE} (type, severity, title, description, page_url, steps, expected_result, actual_result, contact_email, user_id, user_agent, ip_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $types = "sssssssssis" . "s";
                $params = [
                    $form['type'],
                    $form['severity'],
                    $form['title'],
                    $form['description'],
                    ($form['page_url'] !== '' ? $form['page_url'] : null),
                    ($form['steps'] !== '' ? $form['steps'] : null),
                    ($form['expected_result'] !== '' ? $form['expected_result'] : null),
                    ($form['actual_result'] !== '' ? $form['actual_result'] : null),
                    ($form['contact_email'] !== '' ? $form['contact_email'] : null),
                    ($currentUserId !== null ? $currentUserId : null),
                    ($ua !== '' ? $ua : null),
                    ($ipHash !== null ? $ipHash : null),
                ];

                foreach ($params as $k => $v) {
                    if ($v === null) $params[$k] = null;
                }

                db_exec($conn, $sql, $types, $params);

                $success = true;
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                $form = [
                    'type' => 'bug',
                    'severity' => 'medium',
                    'title' => '',
                    'description' => '',
                    'page_url' => '',
                    'steps' => '',
                    'expected_result' => '',
                    'actual_result' => '',
                    'contact_email' => ''
                ];
            } catch (Throwable $e) {
                $errors[] = "Nem sikerült menteni a hibajelentést. Próbáld újra később.";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') ?>">
<head>
    <title>Hibajelentés</title>
    <meta charset="UTF-8">
    <meta name="description" content="Hibajelentés és visszajelzés a Jegyzetárhoz">
    <meta name="keywords" content="hibajelentés, visszajelzés, jegyzetár">
    <meta name="author" content="Baranyi Norbert, Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<main class="main">
    <div class="card">
        <div class="card-head">
            <div>
                <h1 style="margin:0;">Hibajelentés / Visszajelzés</h1>
                <p class="entry-meta" style="margin:6px 0 0;">
                    Írd le röviden, mit tapasztalsz. Minél pontosabb (eszköz, lépések), annál gyorsabb a javítás.
                </p>
            </div>
        </div>
        <?php if ($success): ?>
            <div class="toast toast-success">
                Köszi! A jelentést megkaptuk.
            </div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="toast toast-error">
                <strong>Hiba történt:</strong>
                <ul style="margin:8px 0 0; padding-left:18px;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="POST" action="report.php" class="form-grid" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="content-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                <div class="form-field">
                    <label for="type">Kategória</label>
                    <select id="type" name="type" class="select" required>
                        <option value="bug" <?= $form['type']==='bug'?'selected':''; ?>>Hiba</option>
                        <option value="feature" <?= $form['type']==='feature'?'selected':''; ?>>Javaslat</option>
                        <option value="abuse" <?= $form['type']==='abuse'?'selected':''; ?>>Szabályszegés / visszaélés</option>
                        <option value="other" <?= $form['type']==='other'?'selected':''; ?>>Egyéb</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="severity">Prioritás</label>
                    <select id="severity" name="severity" class="select" required>
                        <option value="low" <?= $form['severity']==='low'?'selected':''; ?>>Alacsony</option>
                        <option value="medium" <?= $form['severity']==='medium'?'selected':''; ?>>Közepes</option>
                        <option value="high" <?= $form['severity']==='high'?'selected':''; ?>>Magas</option>
                        <option value="critical" <?= $form['severity']==='critical'?'selected':''; ?>>Kritikus</option>
                    </select>
                </div>
            </div>
            <div class="form-field">
                <label for="title">Rövid cím</label>
                <input id="title" name="title" type="text" class="input" maxlength="120" required placeholder="Pl.: Letöltés gomb nem működik mobilon" value="<?= htmlspecialchars($form['title'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-field">
                <label for="description">Leírás</label>
                <textarea id="description" name="description" class="input" maxlength="5000" required placeholder="Írd le részletesen, mit tapasztalsz..."><?= htmlspecialchars($form['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="content-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                <div class="form-field">
                    <label for="page_url">Érintett oldal linkje (opcionális)</label>
                    <input id="page_url" name="page_url" type="url" class="input" maxlength="255" placeholder="https://..." value="<?= htmlspecialchars($form['page_url'], ENT_QUOTES, 'UTF-8') ?>">
                    <small class="entry-meta">Tipp: másold be a címsorból.</small>
                </div>
                <div class="form-field">
                    <label for="contact_email">Kapcsolati e-mail (opcionális)</label>
                    <input id="contact_email" name="contact_email" type="email" class="input" maxlength="190" placeholder="ha szeretnél választ" value="<?= htmlspecialchars($form['contact_email'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>
            <div class="form-field">
                <label for="steps">Lépések a reprodukáláshoz (opcionális)</label>
                <textarea id="steps" name="steps" class="input" maxlength="5000" placeholder="1) ... 2) ... 3) ..."><?= htmlspecialchars($form['steps'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="content-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                <div class="form-field">
                    <label for="expected_result">Elvárt eredmény (opcionális)</label>
                    <textarea id="expected_result" name="expected_result" class="input" maxlength="3000" placeholder="Mit kellett volna történnie?"><?= htmlspecialchars($form['expected_result'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="form-field">
                    <label for="actual_result">Tényleges eredmény (opcionális)</label>
                    <textarea id="actual_result" name="actual_result" class="input" maxlength="3000" placeholder="Mi történt helyette?"><?= htmlspecialchars($form['actual_result'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </div>
            <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-top:6px;">
                <button type="submit" class="btn-primary">Jelentés elküldése</button>
                <span class="entry-meta">Ne adj meg jelszót vagy érzékeny adatot.</span>
            </div>
        </form>
    </div>
</main>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
