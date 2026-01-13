<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    $isLoggedIn = isset($_COOKIE['id']) && ctype_digit($_COOKIE['id']);
    $user = null;
    $notify_number = 0;

    if ($isLoggedIn) {
        $uid = (int)$_COOKIE['id'];

        $res = db_query($conn, "SELECT id, username FROM users WHERE id = ? LIMIT 1", "i", [$uid]);
        $user = ($res && $res->num_rows) ? $res->fetch_assoc() : null;

        $nf = db_query($conn, "SELECT id FROM notifys WHERE toid = ? AND readed = 0", "i", [$uid]);
        $notify_number = $nf ? $nf->num_rows : 0;
    }

    $currentUserId = $user['id'] ?? 0;
    $displayName   = $user['username'] ?? t('guest');
    $today = date("m-d");
    $nd = db_query($conn, "SELECT nevek FROM namedays WHERE datum = ? LIMIT 1", "s", [$today]);
    $nameday = ($nd && $nd->num_rows > 0) ? $nd->fetch_assoc()['nevek'] : t('nameday_none_today');

    $popular_sql = "
        SELECT f.*, IFNULL(AVG(r.rating),0) AS avg_rating, COUNT(r.id) AS rating_count
        FROM files f
        LEFT JOIN ratings r ON f.id = r.file_id
        GROUP BY f.id
        ORDER BY avg_rating DESC, rating_count DESC
        LIMIT 8
    ";
    $popular_result = $conn->query($popular_sql);
    $latest_result = $conn->query("SELECT * FROM files ORDER BY id DESC LIMIT 12");

    if (isset($_POST['favorite-btn'])) {
        if (!$isLoggedIn || !$currentUserId) {
            header("Location: reglog.php");
            exit;
        }

        $file_id = isset($_POST['favorite_file_id']) ? (int)$_POST['favorite_file_id'] : 0;

        if ($file_id > 0) {
            $check_result = db_query(
                    $conn,
                    "SELECT id FROM favorites WHERE file_id = ? AND user_id = ? LIMIT 1",
                    "ii",
                    [$file_id, $currentUserId]
            );

            if ($check_result && $check_result->num_rows > 0) {
                db_exec($conn, "DELETE FROM favorites WHERE file_id = ? AND user_id = ?", "ii", [$file_id, $currentUserId]);
            } else {
                db_exec($conn, "INSERT INTO favorites (file_id, user_id) VALUES (?, ?)", "ii", [$file_id, $currentUserId]);
            }
        }

        header("Location: index.php#file-" . $file_id);
        exit;
    }

    if (isset($_POST['rate-btn'], $_POST['rate_file_id'], $_POST['rating'])) {
        if (!$isLoggedIn || !$user) {
            header("Location: reglog.php");
            exit;
        }

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
        }

        header("Location: index.php#file-" . $file_id);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang ?? 'hu') ?>">
<head>
    <title><?= t('index_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
    <body>
    <?php
        include 'assets/php/navbar.php';
    ?>
    <div class="content-wrapper">
        <?php include "assets/php/ads.php"; ?>
        <div class="main">
            <section class="hero card">
                <div class="hero-body">
                    <div class="hero-text">
                        <h1 class="hero-title">
                            <?= t('hero_greeting') . " " . htmlspecialchars($displayName) ?>!
                        </h1>
                        <p class="hero-sub"><?= t('hero_nameday') ?>: <strong><?= htmlspecialchars($nameday) ?></strong></p>
                    </div>
                    <div class="hero-actions">
                        <a class="btn-cta" href="<?= $isLoggedIn ? 'upload.php' : 'reglog.php' ?>">
                            + <?= t('nav_upload') ?>
                        </a>
                    </div>
                </div>
            </section>
            <div class="home-grid">
                <section class="content-main">
                    <div class="section-titlebar">
                        <h3><?= t('home_new_uploads') ?></h3>
                        <a class="link-more" href="search.php?sort=new"><?= t('home_all_arrow') ?></a>
                    </div>
                    <?php if ($latest_result && $latest_result->num_rows > 0): ?>
                        <div class="content-grid grid-large">
                            <?php while ($file = $latest_result->fetch_assoc()):
                                $file_id = (int)$file['id'];

                                $uploaderRes = db_query(
                                        $conn,
                                        "SELECT username FROM users WHERE id = ? LIMIT 1",
                                        "i",
                                        [(int)$file['uploaded_by']]
                                );
                                $uploader = ($uploaderRes && $uploaderRes->num_rows)
                                        ? $uploaderRes->fetch_assoc()
                                        : ['username' => 'ismeretlen'];

                                $name = htmlspecialchars($file['name'] ?? '');
                                $username = htmlspecialchars($uploader['username'] ?? 'ismeretlen');

                                $ext = strtolower(pathinfo($file['file_name'] ?? '', PATHINFO_EXTENSION));

                                $avgRes = db_query(
                                        $conn,
                                        "SELECT AVG(rating) AS avg, COUNT(*) AS c FROM ratings WHERE file_id = ?",
                                        "i",
                                        [$file_id]
                                );
                                $avg = "0.00";
                                $cnt = 0;
                                if ($avgRes && $avgRes->num_rows) {
                                    $d = $avgRes->fetch_assoc();
                                    $avg = number_format((float)($d['avg'] ?? 0), 2);
                                    $cnt = (int)($d['c'] ?? 0);
                                }

                                $is_favorite = false;
                                if ($isLoggedIn && $currentUserId) {
                                    $favRes = db_query(
                                            $conn,
                                            "SELECT id FROM favorites WHERE file_id = ? AND user_id = ? LIMIT 1",
                                            "ii",
                                            [$file_id, $currentUserId]
                                    );
                                    $is_favorite = ($favRes && $favRes->num_rows > 0);
                                }
                                $safe_path = "users/" . ($uploader['username'] ?? '') . "/" . ($file['file_name'] ?? '');
                                ?>
                                <article class="card note-card" id="file-<?= $file_id ?>">
                                    <header class="card-head">
                                        <h4 class="entry-title"><?= $name ?></h4>
                                        <div style="display:flex; gap:8px; align-items:center;">
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="favorite_file_id" value="<?= $file_id ?>">
                                                <button type="submit" name="favorite-btn" class="favorite-btn <?= $is_favorite ? 'favorited' : '' ?>"<?= !$isLoggedIn ? 'disabled' : '' ?>aria-label="favorite">
                                                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                                        <?php if ($is_favorite): ?>
                                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                                                        <?php else: ?>
                                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="none" stroke="currentColor" stroke-width="2"/>
                                                        <?php endif; ?>
                                                    </svg>
                                                </button>
                                            </form>
                                            <a class="note-desc-btn" href="note.php?id=<?= $file_id ?>">
                                                <?= t('btn_details') ?>
                                            </a>
                                            <a class="entry-download-btn" href="assets/php/download.php?id=<?= $file_id ?>">
                                                <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M5 20h14M12 3v12m0 0l-4-4m4 4 4-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <?= t('btn_download') ?>
                                            </a>
                                        </div>
                                    </header>
                                    <p><?= t('label_uploaded_by') ?>
                                        <a class="uploader-name" href="profile.php?user=<?= urlencode($uploader['username']) ?>">@<?= htmlspecialchars($uploader['username']) ?>
                                        </a>
                                    </p>
                                    <p>
                                        <b><?= t('label_rating_average') ?></b>
                                        <?= $avg ?> (<?= $cnt ?> <?= t('suffix_ratings_paren') ?>)
                                    </p>
                                    <form method="post">
                                        <input type="hidden" name="rate_file_id" value="<?= $file_id ?>">
                                        <div class="star-rating">
                                            <?php
                                            $usr_rate = 0;
                                            if ($isLoggedIn && $currentUserId) {
                                                $rRes = db_query(
                                                        $conn,
                                                        "SELECT rating FROM ratings WHERE file_id = ? AND user_id = ? LIMIT 1",
                                                        "ii",
                                                        [$file_id, $currentUserId]
                                                );
                                                if ($rRes && $rRes->num_rows) {
                                                    $usr_rate = (int)$rRes->fetch_assoc()['rating'];
                                                }
                                            }
                                            for ($i = 5; $i >= 1; $i--) {
                                                $checked  = ($usr_rate === $i) ? "checked" : "";
                                                $disabled = (!$isLoggedIn ? "disabled" : "");
                                                $inputId  = "rate-{$file_id}-{$i}";

                                                echo "<input id='{$inputId}' type='radio' name='rating' value='{$i}' {$checked} {$disabled}>";
                                                echo "<label for='{$inputId}'>★</label>";
                                            }
                                            ?>
                                        </div>
                                        <?php if ($isLoggedIn): ?>
                                            <button type="button" class="btn-search">
                                                <svg class="icon icon-send" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"
                                                          fill="none" stroke="currentColor" stroke-width="2"
                                                          stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <span><?= t('btn_send') ?></span>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn-search">
                                                <svg class="icon icon-send" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"
                                                          fill="none" stroke="currentColor" stroke-width="2"
                                                          stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <span><?= t('btn_login_to_rate') ?></span>
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </section>
                <aside class="content-aside">
                    <div class="section-titlebar">
                        <h3><?= t('sidebar_top_rated') ?></h3>
                    </div>
                    <?php if ($popular_result && $popular_result->num_rows > 0): ?>
                        <div class="list-compact">
                            <?php while ($file = $popular_result->fetch_assoc()): ?>
                                <p><?= htmlspecialchars($file['name'] ?? '') ?> — ⭐ <?= number_format((float)$file['avg_rating'], 2) ?></p>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </div>
    <?php include 'assets/php/footer.php'; ?>
    </body>
</html>
