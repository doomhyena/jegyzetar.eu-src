<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
	header("Referrer-Policy: strict-origin-when-cross-origin");
    
    /*
    
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    
    */

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

    $popular_sql = "SELECT f.*, IFNULL(AVG(r.rating),0) AS avg_rating, COUNT(r.id) AS rating_count FROM files f LEFT JOIN ratings r ON f.id = r.file_id GROUP BY f.id ORDER BY avg_rating DESC, rating_count DESC LIMIT 8";
    $popular_result = $conn->query($popular_sql);
    $latest_result = $conn->query("SELECT * FROM files ORDER BY id DESC LIMIT 12");

    if (isset($_POST['favorite-btn'])) {
        if (!$isLoggedIn || !$currentUserId) {
            header("Location: reglog.php");
            exit;
        }

        $file_id = isset($_POST['favorite_file_id']) ? (int)$_POST['favorite_file_id'] : 0;

        if ($file_id > 0) {
            $check_result = db_query($conn, "SELECT id FROM favorites WHERE file_id = ? AND user_id = ? LIMIT 1", "ii", [$file_id, $currentUserId]);

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
        $rating = (int)$_POST['rating'];
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
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
    <?php
        include 'assets/php/navbar.php';
    ?>
    <div class="content-wrapper w-full">
        <?php include "assets/php/ads.php"; ?>
        <div class="main w-full max-w-6xl mx-auto px-4 md:px-6 lg:px-8">
            <section class="hero card mb-6 md:mb-8">
                <div class="hero-body p-4 md:p-6 lg:p-10">
                    <div class="hero-text">
                        <h1 class="hero-title text-2xl md:text-3xl lg:text-4xl font-bold mb-2">
                            <?= t('hero_greeting') . " " . htmlspecialchars($displayName) ?>!
                        </h1>
                        <p class="hero-sub text-base md:text-lg opacity-90"><?= t('hero_nameday') ?>: <strong><?= htmlspecialchars($nameday) ?></strong></p>
                    </div>
                    <div class="hero-actions mt-4 md:mt-6 lg:mt-0">
                        <div class="hero-actions">
                            <a class="btn-cta text-base md:text-lg px-6 md:px-8 py-2 md:py-3" href="<?= $isLoggedIn ? 'upload.php' : 'reglog.php' ?>">
                                + <?= t('nav_upload') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <section class="card mb-6" style="background: linear-gradient(135deg, rgba(125,211,252,.08), rgba(244,114,182,.06)); border-color: rgba(125,211,252,.18);">
                <div style="padding: 8px 4px;">
                    <p style="margin: 0 0 18px; font-size: 1.05rem; color: var(--text); line-height: 1.65; max-width: 72ch;">
                        A <strong>Jegyzetár</strong> egy ingyenes közösségi platform, ahol diákok és tanárok megoszthatják egymással
                        tananyagokat, összefoglalókat, feladatsorokat és egyéb iskolai segédanyagokat.
                        Keress tantárgy, évfolyam vagy kulcsszó alapján - töltsd le, értékeld, és te is járulj hozzá!
                    </p>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 999px; background: rgba(125,211,252,.10); border: 1px solid rgba(125,211,252,.22); font-size: .9rem; font-weight: 600; color: var(--primary);">
                            Ingyenes jegyzetek
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 999px; background: rgba(52,211,153,.10); border: 1px solid rgba(52,211,153,.22); font-size: .9rem; font-weight: 600; color: var(--ok);">
                            Közösségi tanulás
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 999px; background: rgba(244,114,182,.10); border: 1px solid rgba(244,114,182,.22); font-size: .9rem; font-weight: 600; color: var(--accent);">
                            Diákoknak & tanároknak
                        </div>
                    </div>
                    <div style="margin-top: 18px; display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="reglog.php" class="btn-cta">Regisztráció - ingyenes</a>
                        <a href="search.php" class="btn-ghost">Böngészés bejelentkezés nélkül</a>
                    </div>
                </div>
            </section>
            <section class="content-main flex-1 min-w-0">
                <div class="section-titlebar flex justify-between items-center mb-4">
                    <h3 class="text-xl md:text-2xl"><?= t('home_new_uploads') ?></h3>
                    <a class="link-more" href="search.php?sort=new"><?= t('home_all_arrow') ?></a>
                </div>
            <?php if ($latest_result && $latest_result->num_rows > 0): ?>
                <div class="home-grid">
                    <section class="content-main flex-1 min-w-0">
                        <div class="section-titlebar flex justify-between items-center mb-4">
                            <h3 class="text-xl md:text-2xl"><?= t('home_new_uploads') ?></h3>
                            <a class="link-more" href="search.php?sort=new"><?= t('home_all_arrow') ?></a>
                        </div>
                        <div class="content-grid grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <?php while ($file = $latest_result->fetch_assoc()):
                                $file_id = (int)$file['id'];
                                $contentType = $file['content_type'] ?? 'file';
                                $externalUrl = trim((string)($file['external_url'] ?? ''));
                                $uploaderRes = db_query($conn, "SELECT username FROM users WHERE id = ? LIMIT 1", "i", [(int)$file['uploaded_by']]);
                                $uploader = ($uploaderRes && $uploaderRes->num_rows) ? $uploaderRes->fetch_assoc() : ['username' => 'ismeretlen'];
                                $avgRes = db_query($conn, "SELECT AVG(rating) AS avg, COUNT(*) AS c FROM ratings WHERE file_id = ?", "i", [$file_id]);
                                $avg = "0.00";
                                $cnt = 0;
                                if ($avgRes && $avgRes->num_rows) {
                                    $d = $avgRes->fetch_assoc();
                                    $avg = number_format((float)($d['avg'] ?? 0), 2);
                                    $cnt = (int)($d['c'] ?? 0);
                                }

                                $is_favorite = false;
                                if ($isLoggedIn && $currentUserId) {
                                    $favRes = db_query($conn, "SELECT id FROM favorites WHERE file_id = ? AND user_id = ? LIMIT 1", "ii", [$file_id, $currentUserId]);
                                    $is_favorite = ($favRes && $favRes->num_rows > 0);
                                }
                            ?>
                            <article class="card note-card" id="file-<?= $file_id ?>">
                                <header class="note-header">

                                    <?php if ($contentType === 'link' && !empty($externalUrl)): ?>
                                        <div class="note-media" style="margin-top:10px;">
                                            <a href="<?= htmlspecialchars($externalUrl) ?>" target="_blank">Megnyitás</a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-title-group">
                                        <h4 class="entry-title"><?= htmlspecialchars($file['name'] ?? '') ?></h4>
                                        <p class="uploader-info">
                                            Feltöltötte:
                                            <a class="uploader-name" href="profile.php?user=<?= urlencode($uploader['username']) ?>">
                                                @<?= htmlspecialchars($uploader['username']) ?>
                                            </a>
                                        </p>
                                    </div>
                                </header>
                                <div class="card-actions">
                                    <?php if ($isLoggedIn && $currentUserId): ?>
                                        <form method="POST">
                                            <input type="hidden" name="favorite-btn" value="1">
                                            <input type="hidden" name="favorite_file_id" value="<?= $file_id ?>">
                                            <button type="submit" class="favorite-btn <?= $is_favorite ? 'favorited' : '' ?>">❤</button>
                                        </form>
                                    <?php else: ?>
                                        <a class="favorite-btn" href="reglog.php">❤</a>
                                    <?php endif; ?>

                                    <a href="note.php?id=<?= $file_id ?>" class="btn-sm btn-ghost">Részletek</a>
                                </div>
                                <div class="card-footer-meta">
                                    ⭐ <?= $avg ?> (<?= $cnt ?>)
                                </div>
                            </article>
                            <?php endwhile; ?>
                        </div>
                    </section>
                    <aside class="content-aside w-full lg:w-80 min-w-0">
                        <div class="section-titlebar mb-4">
                            <h3 class="text-xl md:text-2xl"><?= t('sidebar_top_rated') ?></h3>
                        </div>
                        <?php if ($popular_result && $popular_result->num_rows > 0): ?>
                            <div class="list-compact flex flex-col gap-3">
                                <?php while ($file = $popular_result->fetch_assoc()): ?>
                                    <a href="note.php?id=<?= $file['id'] ?>" class="popular-item-link block p-3 rounded-xl bg-white/5 hover:bg-white/10 border">
                                        <div class="flex justify-between">
                                            <span class="truncate"><?= htmlspecialchars($file['name']) ?></span>
                                            <span>⭐ <?= number_format((float)$file['avg_rating'], 2) ?></span>
                                        </div>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </aside>
                </div>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center min-h-[400px] text-center">
                    <div class="empty-state p-8 max-w-md w-full">
                        <p class="text-xl font-semibold mb-2">
                            Az oldal tartalmilag még üres.
                        </p>
                        <p class="text-sm opacity-80 mb-4">
                            Légy te az első!
                        </p>

                        <a href="<?= $isLoggedIn ? 'upload.php' : 'reglog.php' ?>" class="btn-cta">
                            + Feltöltés
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'assets/php/footer.php'; ?>
    </body>
</html>