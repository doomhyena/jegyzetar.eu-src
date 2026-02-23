<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");
    
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
            <div class="home-grid">
            <section class="content-main flex-1 min-w-0">
                    <div class="section-titlebar flex justify-between items-center mb-4">
                        <h3 class="text-xl md:text-2xl"><?= t('home_new_uploads') ?></h3>
                        <a class="link-more" href="search.php?sort=new"><?= t('home_all_arrow') ?></a>
                    </div>
                    <?php if ($latest_result && $latest_result->num_rows > 0): ?>
                        <div class="content-grid grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <?php while ($file = $latest_result->fetch_assoc()):
                                    $file_id = (int)$file['id'];
                                    $uploaderRes = db_query($conn, "SELECT username FROM users WHERE id = ? LIMIT 1", "i", [(int)$file['uploaded_by']]);
                                    $uploader = ($uploaderRes && $uploaderRes->num_rows) ? $uploaderRes->fetch_assoc() : ['username' => 'ismeretlen'];
                                    $name = htmlspecialchars($file['name'] ?? '');
                                    $username = htmlspecialchars($uploader['username'] ?? 'ismeretlen');
                                    $ext = strtolower(pathinfo($file['file_name'] ?? '', PATHINFO_EXTENSION));
                                    $avgRes = db_query($conn, "SELECT AVG(rating) AS avg, COUNT(*) AS c FROM ratings WHERE file_id = ?", "i", [$file_id]);
                                    $avg = "0.00";
                                    $cnt = 0;
                                    if ($avgRes && $avgRes->num_rows) {
                                        $d = $avgRes->fetch_assoc();
                                        $avg = number_format((float)($d['avg'] ?? 0), 2);
                                        $cnt = (int)($d['c'] ?? 0);
                                    }

                                    $userRating = 0;
                                    if ($isLoggedIn && $currentUserId) {
                                        $ur = db_query(
                                                $conn,
                                                "SELECT rating FROM ratings WHERE file_id = ? AND user_id = ? LIMIT 1",
                                                "ii",
                                                [$file_id, $currentUserId]
                                        );
                                        if ($ur && $ur->num_rows > 0) {
                                            $userRating = (int)$ur->fetch_assoc()['rating'];
                                        }
                                    }

                                $is_favorite = false;
                                    if ($isLoggedIn && $currentUserId) {
                                        $favRes = db_query($conn, "SELECT id FROM favorites WHERE file_id = ? AND user_id = ? LIMIT 1", "ii", [$file_id, $currentUserId]);
                                        $is_favorite = ($favRes && $favRes->num_rows > 0);
                                    }
                                    $safe_path = "users/" . ($uploader['username'] ?? '') . "/" . ($file['file_name'] ?? '');
                            ?>
                                <article class="card note-card" id="file-<?= (int)$file_id ?>">
                                    <header class="note-header">
                                        <div class="card-title-group">
                                            <h4 class="entry-title"><?= htmlspecialchars($file['name'] ?? '') ?></h4>
                                            <p class="uploader-info">
                                                Feltöltötte:
                                                <a class="uploader-name" href="profile.php?user=<?= urlencode($uploader['username'] ?? '') ?>">
                                                    @<?= htmlspecialchars($uploader['username'] ?? 'ismeretlen') ?>
                                                </a>
                                            </p>
                                        </div>
                                    </header>
                                    <div class="card-actions">
                                        <div class="card-actions">
                                            <?php if ($isLoggedIn && $currentUserId): ?>
                                            <form method="POST" style="margin:0;">
                                                <input type="hidden" name="favorite-btn" value="1">
                                                <input type="hidden" name="favorite_file_id" value="<?= (int)$file_id ?>">
                                                <button type="submit"
                                                        class="favorite-btn <?= $is_favorite ? 'favorited' : '' ?>"
                                                        title="<?= $is_favorite ? 'Kedvencekből törlés' : 'Kedvencekhez' ?>">
                                                    ❤
                                                </button>
                                            </form>
                                            <?php else: ?>
                                                <a class="favorite-btn" href="reglog.php" title="Jelentkezz be a kedvencekhez">
                                                    ❤
                                                </a>
                                            <?php endif; ?>
                                            <a href="note.php?id=<?= (int)$file_id ?>" class="btn-sm btn-ghost">
                                                Részletek
                                            </a>
                                            <a href="assets/php/download.php?id=<?= (int)$file_id ?>" class="btn-sm btn-cta">
                                                Letöltés
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-footer-meta">
                                        <div class="rating-display">
                                            ⭐ <span class="rating-value"><?= htmlspecialchars($avg) ?></span>
                                            <span class="rating-count">(<?= (int)$cnt ?> ért.)</span>
                                        </div>
                                        <?php if ($isLoggedIn && $currentUserId): ?>
                                            <form class="rating-form" method="POST" action="index.php">
                                                <input type="hidden" name="rate-btn" value="1">
                                                <input type="hidden" name="rate_file_id" value="<?= (int)$file_id ?>">

                                                <div class="star-rating" aria-label="Értékelés">
                                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                                        <input
                                                                type="radio"
                                                                id="rate-<?= (int)$file_id ?>-<?= $i ?>"
                                                                name="rating"
                                                                value="<?= $i ?>"
                                                                <?= ((int)$userRating === $i) ? 'checked' : '' ?>
                                                                onchange="this.form.submit()"
                                                        >
                                                        <label for="rate-<?= (int)$file_id ?>-<?= $i ?>" title="<?= $i ?> csillag">★</label>
                                                    <?php endfor; ?>
                                                </div>
                                                <?php if ((int)$userRating > 0): ?>
                                                    <span class="rating-count">Te: <?= (int)$userRating ?>/5</span>
                                                <?php endif; ?>
                                            </form>
                                        <?php else: ?>
                                            <span class="rating-count">Értékeléshez jelentkezz be.</span>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </section>
                <aside class="content-aside w-full lg:w-80 min-w-0">
                    <div class="section-titlebar mb-4">
                        <h3 class="text-xl md:text-2xl"><?= t('sidebar_top_rated') ?></h3>
                    </div>
                    <?php if ($popular_result && $popular_result->num_rows > 0): ?>
                        <div class="list-compact flex flex-col gap-3">
                            <?php while ($file = $popular_result->fetch_assoc()): ?>
                                <a href="note.php?id=<?= $file['id'] ?>" class="popular-item-link block p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-colors border border-white/5 min-w-0">
                                    <div class="flex justify-between items-center gap-2 min-w-0">
                                        <span class="font-medium truncate text-sm md:text-base"><?= htmlspecialchars($file['name'] ?? '') ?></span>
                                        <span class="text-xs md:text-sm whitespace-nowrap opacity-80">⭐ <?= number_format((float)$file['avg_rating'], 2) ?></span>
                                    </div>
                                </a>
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
