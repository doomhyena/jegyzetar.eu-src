<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    if (!isset($_COOKIE['id']) || !ctype_digit($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $currentUserId = (int)$_COOKIE['id'];

    $userRes = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$currentUserId]);
    $user = ($userRes && $userRes->num_rows > 0) ? $userRes->fetch_assoc() : null;

    if (!$user) {
        header("Location: reglog.php");
        exit;
    }

    $isLoggedIn = true;
    $nfRes = db_query($conn, "SELECT id FROM notifys WHERE toid = ? AND readed = 0", "i", [$user['id']]);
    $notify_number = $nfRes ? (int)$nfRes->num_rows : 0;

    $note_id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? (int)$_GET['id'] : 0;

    if ($note_id <= 0) {
        http_response_code(400);
        $note = null;
    } else {
        $noteRes = db_query($conn, "SELECT * FROM files WHERE id = ? LIMIT 1", "i", [$note_id]);
        $note = ($noteRes && $noteRes->num_rows > 0) ? $noteRes->fetch_assoc() : null;
    }

    $isOwner = false;
    if ($note) {
        $isOwner = ((int)$note['uploaded_by'] === (int)$currentUserId);
    }

    if (isset($_POST['favorite-btn'], $_POST['favorite_file_id'])) {
        $file_id = (int)$_POST['favorite_file_id'];
        $user_id = (int)$user['id'];

        if ($file_id > 0) {
            $check_result = db_query(
                    $conn,
                    "SELECT id FROM favorites WHERE file_id = ? AND user_id = ? LIMIT 1",
                    "ii",
                    [$file_id, $user_id]
            );

            if ($check_result && $check_result->num_rows > 0) {
                db_exec($conn, "DELETE FROM favorites WHERE file_id = ? AND user_id = ?", "ii", [$file_id, $user_id]);
                log_file_event($conn, $file_id, (int)$user['id'], 'favorite_remove', null);
            } else {
                db_exec($conn, "INSERT INTO favorites (file_id, user_id) VALUES (?, ?)", "ii", [$file_id, $user_id]);
                log_file_event($conn, $file_id, (int)$user['id'], 'favorite_add', null);
            }
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    if (isset($_POST['comment-btn'], $_POST['post_id'])) {
        $postid = (int)$_POST['post_id'];
        $text   = trim($_POST['comment-text'] ?? '');

        if ($postid > 0 && $text !== '') {
            db_exec($conn, "INSERT INTO comments (userid, postid, text) VALUES (?, ?, ?)", "iis", [$user['id'], $postid, $text]);

            log_file_event($conn, $postid, (int)$user['id'], 'comment', null);

            if (isset($_GET['uploader']) && ctype_digit($_GET['uploader'])) {
                $uploader = (int)$_GET['uploader'];
                db_exec($conn, "INSERT INTO notifys (fromid, toid, notifytype, readed) VALUES (?, ?, ?, 0)", "iis", [$user['id'], $uploader, 'comment']);
            }

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            echo "<script>alert('Hiba történt a komment írásakor!');</script>";
        }
    }

    if (isset($_POST['rate-btn'], $_POST['rate_file_id'], $_POST['rating'])) {
        $file_id = (int)$_POST['rate_file_id'];
        $rating  = (int)$_POST['rating'];
        $user_id = (int)$user['id'];

        if ($file_id > 0 && $rating >= 1 && $rating <= 5) {
            $check_result = db_query($conn, "SELECT id FROM ratings WHERE file_id = ? AND user_id = ? LIMIT 1", "ii", [$file_id, $user_id]);

            if ($check_result && $check_result->num_rows > 0) {
                db_exec($conn, "UPDATE ratings SET rating = ? WHERE file_id = ? AND user_id = ?", "iii", [$rating, $file_id, $user_id]);
            } else {
                db_exec($conn, "INSERT INTO ratings (file_id, user_id, rating) VALUES (?, ?, ?)", "iii", [$file_id, $user_id, $rating]);
            }

            log_file_event($conn, $file_id, (int)$user['id'], 'rate', $rating);
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    if ($note && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
        log_file_event($conn, (int)$note['id'], (int)$user['id'], 'view', null);
    }
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title>Jegyzet</title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name="author" content="Baranyi Norbert, Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="content-wrapper">
    <?php include "assets/php/ads.php"; ?>
    <div class="main">
        <?php if ($note): ?>
            <?php
                $file_id = (int)$note['id'];
                $file_name = htmlspecialchars($note['name']);
                $ext = strtolower(pathinfo($note['file_name'], PATHINFO_EXTENSION));

                $uploaderRes = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [(int)$note['uploaded_by']]);
                $uploader = ($uploaderRes && $uploaderRes->num_rows > 0) ? $uploaderRes->fetch_assoc() : ['username' => 'ismeretlen'];
                $username = htmlspecialchars($uploader['username'] ?? 'ismeretlen');

                $avgRes = db_query($conn, "SELECT IFNULL(AVG(rating),0) as avg_rating, COUNT(id) as rating_count FROM ratings WHERE file_id = ?", "i", [$file_id]);
                $avg_data = ($avgRes && $avgRes->num_rows > 0) ? $avgRes->fetch_assoc() : ['avg_rating' => 0, 'rating_count' => 0];
                $avg = number_format((float)$avg_data['avg_rating'], 2, '.', '');
                $cnt = (int)$avg_data['rating_count'];

                $is_favorite = false;
                $favRes = db_query($conn, "SELECT id FROM favorites WHERE file_id = ? AND user_id = ? LIMIT 1", "ii", [$file_id, (int)$user['id']]);
                if ($favRes && $favRes->num_rows > 0) $is_favorite = true;

                $user_dir  = "users/" . ($uploader['username'] ?? '') . "/";
                $safe_path = $user_dir . $note['file_name'];
            ?>
            <article class="card note-card">
                <header class="card-head">
                    <h1 class="entry-title"><?= $file_name ?></h1>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="favorite_file_id" value="<?= $file_id ?>">
                            <button type="submit" name="favorite-btn" class="favorite-btn <?= $is_favorite ? 'favorited' : '' ?>">
                                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <?php if ($is_favorite): ?>
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                                    <?php else: ?>
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="none" stroke="currentColor" stroke-width="2"/>
                                    <?php endif; ?>
                                </svg>
                                <span><?= $is_favorite ? 'Kedvencek' : 'Kedvencezés' ?></span>
                            </button>
                        </form>
                        <a class="entry-download-btn" href="assets/php/download.php?id=<?= $file_id ?>">
                            <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M5 20h14M12 3v12m0 0l-4-4m4 4 4-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <?= t('btn_download') ?>
                        </a>
                        <?php if ($isOwner): ?>
                            <a class="btn-cta" href="note_stats.php?id=<?= $file_id ?>">Statisztikák</a>
                        <?php endif; ?>
                        <?php if ($isLoggedIn): ?>
                            <div class="profile-report" style="min-width:240px;">
                                <form method="post" action="assets/php/report.php" id="note-report-form">
                                    <input type="hidden" name="type" value="note">
                                    <input type="hidden" name="target_id" value="<?= (int)$file_id ?>">
                                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="button" class="btn-ghost danger" id="report-toggle-btn">
                                        Jegyzet jelentése
                                    </button>
                                    <div id="report-box" style="display:none; margin-top:8px;">
                                        <textarea name="reason" rows="3" required
                                                  placeholder="Írd le, miért jelented..."
                                                  style="width:100%; resize:vertical; margin-bottom:8px;"></textarea>

                                        <button type="submit" class="btn-cta danger"
                                                onclick="return confirm('Biztosan elküldöd a jelentést?');">
                                            Jelentés elküldése
                                        </button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>
                <?php if ($ext === 'docx'): ?>
                    <p><b>Ez egy .docx fájl. A megtekintéshez töltsd le és nyisd meg Microsoft Word-ben.</b></p>
                <?php elseif ($ext === 'mp4'): ?>
                    <video controls class="file-preview">
                        <source src="<?= htmlspecialchars($safe_path) ?>" type="video/mp4">
                        A te böngésződ nem támogatja a videocímkét.
                    </video>
                <?php elseif ($ext === 'pdf'): ?>
                    <iframe src="<?= htmlspecialchars($safe_path) ?>" width="100%" height="500"></iframe>
                <?php endif; ?>
                <p>Feltöltötte:
                    <a class="uploader-name" href="profile.php?user=<?= urlencode($uploader['username'] ?? '') ?>">
                        <?= $username ?>
                    </a>
                </p>
                <div class="rating-section">
                    <h3>Értékelés</h3>
                    <p><b>Átlag értékelés:</b> <?= $avg ?> (<?= $cnt ?> értékelés)</p>
                    <form method="post" class="rating-form filters-inner">
                        <input type="hidden" name="rate_file_id" value="<?= $file_id ?>">
                        <div class="star-rating" aria-label="Értékelés 1–5">
                            <?php
                                $usr_rate = 0;
                                $rs = db_query($conn, "SELECT rating FROM ratings WHERE file_id = ? AND user_id = ? LIMIT 1", "ii", [$file_id, (int)$user['id']]);
                                if ($rs && $rs->num_rows > 0) $usr_rate = (int)$rs->fetch_assoc()['rating'];

                                for ($i = 5; $i >= 1; $i--) {
                                    $checked  = ($usr_rate === $i) ? 'checked' : '';
                                    $input_id = "star{$i}_note_{$file_id}";
                                    echo '<input type="radio" id="'.$input_id.'" name="rating" value="'.$i.'" '.$checked.'>';
                                    echo '<label for="'.$input_id.'" title="'.$i.' csillag">★</label>';
                                }
                            ?>
                        </div>
                        <button type="submit" name="rate-btn" class="btn-search">
                            <svg class="icon icon-send" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"
                                      fill="none" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span><?= t('btn_send') ?></span>
                        </button>
                    </form>
                </div>

                <div class="comments-section">
                    <h3>Kommentek</h3>
                    <?php
                        $comments_result = db_query($conn, "SELECT c.*, u.username FROM comments c JOIN users u ON c.userid = u.id WHERE c.postid = ? ORDER BY c.id DESC", "i", [$file_id]);
                    ?>
                    <?php if ($comments_result && $comments_result->num_rows > 0): ?>
                        <?php while ($comment = $comments_result->fetch_assoc()): ?>
                            <div class="comment">
                                <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
                                <p><?= htmlspecialchars($comment['text']) ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="entry-meta">Még nincs komment.</p>
                    <?php endif; ?>
                    <form method="post" class="comment-form filters-inner">
                        <input type="hidden" name="post_id" value="<?= $file_id ?>">
                        <textarea name="comment-text" class="input" placeholder="Írj kommentet..." required rows="3"></textarea>
                        <button type="submit" name="comment-btn" class="btn-search">
                            <svg class="icon icon-send" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"
                                      fill="none" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span><?= t('btn_send') ?></span>
                        </button>
                    </form>
                </div>
            </article>
        <?php else: ?>
            <div class="card">
                <h1>Jegyzet nem található!</h1>
                <p>A keresett jegyzet nem létezik vagy törölve lett.</p>
                <a href="index.php" class="btn-cta">Vissza a főoldalra</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
