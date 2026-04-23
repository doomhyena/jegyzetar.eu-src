<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require "assets/php/db.php";
    require "assets/php/lang.php";
    require "assets/php/functions.php";
    require_login();

    $BUG_TABLE = "bug_reports";

    $currentUserId = (int)auth_user_id();

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
            $errors[] = t('report_err_csrf');
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
            $errors[] = t('report_err_invalid_type');
        }
        if (!in_array($form['severity'], $allowedSeverity, true)) {
            $errors[] = t('report_err_invalid_severity');
        }
        if ($form['title'] === '' || mb_strlen($form['title'], 'UTF-8') < 4) {
            $errors[] = t('report_err_title_short');
        }
        if ($form['description'] === '' || mb_strlen($form['description'], 'UTF-8') < 10) {
            $errors[] = t('report_err_desc_short');
        }
        if ($form['contact_email'] !== '' && !filter_var($form['contact_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = t('report_err_email_invalid');
        }
        if ($form['page_url'] !== '' && !filter_var($form['page_url'], FILTER_VALIDATE_URL)) {
            $errors[] = t('report_err_url_invalid');
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
                    $currentUserId,
                    ($ua !== '' ? $ua : null),
                    ($ipHash !== null ? $ipHash : null),
                ];

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
                $errors[] = t('report_err_save_failed');
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') ?>">
<head>
    <title><?= t('report_page_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('report_meta_desc') ?>">
    <meta name="keywords" content="<?= t('report_meta_keywords') ?>">
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
                <h1 style="margin:0;"><?= t('report_h1') ?></h1>
                <p class="entry-meta" style="margin:6px 0 0;">
                    <?= t('report_sub') ?>
                </p>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="toast toast-success">
                <?= t('report_success') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="toast toast-error">
                <strong><?= t('report_error_heading') ?></strong>
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
                    <label for="type"><?= t('report_label_type') ?></label>
                    <select id="type" name="type" class="select" required>
                        <option value="bug"     <?= $form['type']==='bug'     ? 'selected' : '' ?>><?= t('report_type_bug') ?></option>
                        <option value="feature" <?= $form['type']==='feature' ? 'selected' : '' ?>><?= t('report_type_feature') ?></option>
                        <option value="abuse"   <?= $form['type']==='abuse'   ? 'selected' : '' ?>><?= t('report_type_abuse') ?></option>
                        <option value="other"   <?= $form['type']==='other'   ? 'selected' : '' ?>><?= t('report_type_other') ?></option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="severity"><?= t('report_label_severity') ?></label>
                    <select id="severity" name="severity" class="select" required>
                        <option value="low"      <?= $form['severity']==='low'      ? 'selected' : '' ?>><?= t('report_severity_low') ?></option>
                        <option value="medium"   <?= $form['severity']==='medium'   ? 'selected' : '' ?>><?= t('report_severity_medium') ?></option>
                        <option value="high"     <?= $form['severity']==='high'     ? 'selected' : '' ?>><?= t('report_severity_high') ?></option>
                        <option value="critical" <?= $form['severity']==='critical' ? 'selected' : '' ?>><?= t('report_severity_critical') ?></option>
                    </select>
                </div>
            </div>
            <div class="form-field">
                <label for="title"><?= t('report_label_title') ?></label>
                <input id="title" name="title" type="text" class="input" maxlength="120" required
                    placeholder="<?= htmlspecialchars(t('report_placeholder_title'), ENT_QUOTES, 'UTF-8') ?>"
                    value="<?= htmlspecialchars($form['title'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-field">
                <label for="description"><?= t('report_label_description') ?></label>
                <textarea id="description" name="description" class="input" maxlength="5000" required
                    placeholder="<?= htmlspecialchars(t('report_placeholder_description'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($form['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="content-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                <div class="form-field">
                    <label for="page_url"><?= t('report_label_page_url') ?></label>
                    <input id="page_url" name="page_url" type="url" class="input" maxlength="255"
                        placeholder="https://..."
                        value="<?= htmlspecialchars($form['page_url'], ENT_QUOTES, 'UTF-8') ?>">
                    <small class="entry-meta"><?= t('report_hint_page_url') ?></small>
                </div>
                <div class="form-field">
                    <label for="contact_email"><?= t('report_label_contact_email') ?></label>
                    <input id="contact_email" name="contact_email" type="email" class="input" maxlength="190"
                        placeholder="<?= htmlspecialchars(t('report_placeholder_contact_email'), ENT_QUOTES, 'UTF-8') ?>"
                        value="<?= htmlspecialchars($form['contact_email'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>
            <div class="form-field">
                <label for="steps"><?= t('report_label_steps') ?></label>
                <textarea id="steps" name="steps" class="input" maxlength="5000"
                    placeholder="1) ... 2) ... 3) ..."><?= htmlspecialchars($form['steps'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="content-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                <div class="form-field">
                    <label for="expected_result"><?= t('report_label_expected') ?></label>
                    <textarea id="expected_result" name="expected_result" class="input" maxlength="3000"
                        placeholder="<?= htmlspecialchars(t('report_placeholder_expected'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($form['expected_result'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="form-field">
                    <label for="actual_result"><?= t('report_label_actual') ?></label>
                    <textarea id="actual_result" name="actual_result" class="input" maxlength="3000"
                        placeholder="<?= htmlspecialchars(t('report_placeholder_actual'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($form['actual_result'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </div>
            <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-top:6px;">
                <button type="submit" class="btn-primary"><?= t('report_btn_submit') ?></button>
                <span class="entry-meta"><?= t('report_hint_no_sensitive') ?></span>
            </div>
        </form>
    </div>
</main>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>