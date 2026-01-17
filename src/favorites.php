<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    // norbi: kedvenc jegyzetek megjelenítése
    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once 'assets/php/functions.php';

    if (!isset($_COOKIE['id']) || !ctype_digit($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $user_id = (int)$_COOKIE['id'];

    $user_result = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$user_id]
    );
    if (!$user_result || $user_result->num_rows == 0) {
        header("Location: reglog.php");
        exit;
    }

    $user = $user_result->fetch_assoc();
    $notify_number = 0;

    $nf = db_query($conn, "SELECT id FROM notifys WHERE toid = ? AND readed = 0", "i", [$user_id]);
    if ($nf) {
        $notify_number = $nf->num_rows;
    }

    $talalt_sorok = db_query($conn, "SELECT * FROM favorites WHERE user_id = ?", "i", [$user_id]);

    $favorites = [];
    while ($sor = $talalt_sorok->fetch_assoc()) {
        $file_id = (int)$sor['file_id'];

        $file_q = db_query($conn, "SELECT * FROM files WHERE id = ? LIMIT 1", "i", [$file_id]);

        if ($file_q && $file_q->num_rows > 0) {
            $favorites[] = $file_q->fetch_assoc();
        }
    }

?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title>Kedvenceim</title>
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
<?php include 'assets/php/navbar.php'; ?>
<div class="content-wrapper w-full">
    <?php include "assets/php/ads.php"; ?>
    <div class="main w-full max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6">
        <div class="section-titlebar mb-6">
            <h1 class="text-2xl md:text-3xl lg:text-4xl flex items-center gap-2 md:gap-3">
                <svg class="icon w-6 h-6 md:w-7 md:h-7 flex-shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                </svg>
                <span class="truncate">Kedvenc jegyzeteim</span>
            </h1>
        </div>
        <?php if (!empty($favorites)): ?>
            <div class="content-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <?php foreach ($favorites as $f):
                    $uploaderRes = db_query(
                        $conn,
                        "SELECT username FROM users WHERE id = ? LIMIT 1",
                        "i",
                        [(int)$f['uploaded_by']]
                    );
                    $uploader = $uploaderRes ? $uploaderRes->fetch_assoc() : ['username' => 'ismeretlen'];
                    $file_id = (int)$f['id'];
                    $file_name = htmlspecialchars($f['name']);
                    $username = htmlspecialchars($uploader['username'] ?? 'ismeretlen');
                    $ext = strtolower(pathinfo($f['file_name'], PATHINFO_EXTENSION));
                    $user_dir = "users/" . ($uploader['username'] ?? '') . "/";
                    $safe_path = $user_dir . $f['file_name'];

                    $avgRes = db_query($conn, "SELECT IFNULL(AVG(rating),0) as avg, COUNT(*) as c FROM ratings WHERE file_id = ?", "i", [$file_id]);
                    $avg = 0;
                    $cnt = 0;
                    if ($avgRes && $avgRes->num_rows) {
                        $d = $avgRes->fetch_assoc();
                        $avg = number_format((float)$d['avg'], 2);
                        $cnt = (int)$d['c'];
                    }
                    ?>
                    <article class="card p-4 md:p-6 flex flex-col gap-3 break-words">
                        <header class="card-head flex flex-col gap-2">
                            <h4 class="entry-title text-lg md:text-xl truncate"><?= $file_name ?></h4>
                            <a class="uploader-name text-sm md:text-base hover:underline" href="profile.php?userid=<?= (int)$f['uploaded_by'] ?>">@<?= $username ?></a>
                        </header>
                        <div class="flex flex-col md:flex-row gap-2 mt-auto">
                            <a class="note-desc-btn text-sm md:text-base w-full md:w-auto text-center" href="note.php?id=<?= $file_id ?>">
                                <?= t('btn_details') ?>
                            </a>
                            <a class="entry-download-btn text-sm md:text-base w-full md:w-auto text-center flex items-center justify-center gap-2" href="assets/php/download.php?id=<?= $file_id ?>">
                                <svg class="icon icon-download w-4 h-4" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 3v10m0 0l-4-4m4 4l4-4M4 17v3h16v-3"></path>
                                </svg>
                                <span><?= t('btn_download') ?></span>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card text-center p-8 md:p-12">
                <svg class="icon w-12 h-12 md:w-16 md:h-16 mx-auto mb-4 opacity-30" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="none" stroke="currentColor" stroke-width="2"/>
                </svg>
                <h2 class="text-xl md:text-2xl">Még nincsenek kedvenc jegyzeteid</h2>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
