<?php
// norbi: kedvenc jegyzetek megjelenítése
require_once "assets/php/db.php";
require_once "assets/php/lang.php";
require_once 'assets/php/functions.php';

if (!isset($_COOKIE['id'])) {
    header("Location: reglog.php");
    exit;
}

$user_id = (int)$_COOKIE['id'];
$user_result = $conn->query("SELECT * FROM users WHERE id = $user_id LIMIT 1");
if (!$user_result || $user_result->num_rows == 0) {
    header("Location: reglog.php");
    exit;
}
$user = $user_result->fetch_assoc();

$notify_number = 0;
$nf = $conn->query("SELECT id FROM notifys WHERE toid = $user_id AND readed = 0");
if ($nf) {
    $notify_number = $nf->num_rows;
}

// kedvenc fájlok lekérése
$lekerdezes = "SELECT * FROM favorites WHERE user_id = $user_id";
$talalt_sorok = $conn->query($lekerdezes);
$favorites = [];
while($sor = $talalt_sorok->fetch_assoc()){
    $file_id = (int)$sor['file_id'];
    $file_q = $conn->query("SELECT * FROM files WHERE id = $file_id LIMIT 1");
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
    <meta name="author" content="Baranyai Norbert, Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="main">
    <div class="section-titlebar">
        <h1>
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true" style="width: 28px; height: 28px; margin-right: 8px; vertical-align: middle;">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
            </svg>
            Kedvenc jegyzeteim
        </h1>
    </div>

    <?php if (!empty($favorites)): ?>
        <div class="content-grid grid-large">
            <?php foreach ($favorites as $f):
                $uploader_q = $conn->query("SELECT username FROM users WHERE id=".(int)$f['uploaded_by']." LIMIT 1");
                $uploader   = $uploader_q ? $uploader_q->fetch_assoc() : ['username'=>'ismeretlen'];

                $file_id   = (int)$f['id'];
                $file_name = htmlspecialchars($f['name']);
                $username  = htmlspecialchars($uploader['username'] ?? 'ismeretlen');

                $ext       = strtolower(pathinfo($f['file_name'], PATHINFO_EXTENSION));
                $user_dir  = "users/" . ($uploader['username'] ?? '') . "/";
                $safe_path = $user_dir . $f['file_name'];

                $avg_q = $conn->query("SELECT IFNULL(AVG(rating),0) as avg, COUNT(*) as c FROM ratings WHERE file_id=$file_id");
                $avg = 0; 
                $cnt = 0;
                if ($avg_q && $avg_q->num_rows) {
                    $d = $avg_q->fetch_assoc();
                    $avg = number_format((float)$d['avg'], 2);
                    $cnt = (int)$d['c'];
                }
                ?>
                <article class="card">
                    <header class="card-head">
                        <h4 class="entry-title"><?= $file_name ?></h4>
                        <a class="uploader-name" href="profile.php?userid=<?= (int)$f['uploaded_by'] ?>">@<?= $username ?></a>
                        <a class="note-desc-btn" href="note.php?id=<?= $file_id ?>">
                            <?= t('btn_details') ?>
                        </a>
                        <a class="entry-download-btn" href="assets/php/download.php?id=<?= $file_id ?>">
                            <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 3v10m0 0l-4-4m4 4l4-4M4 17v3h16v-3"></path>
                            </svg>
                            <?= t('btn_download') ?>
                        </a>
                    </header>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card" style="text-align: center; padding: 48px 24px;">
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true" style="width: 64px; height: 64px; margin: 0 auto 16px; opacity: 0.3;">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
            <h2>Még nincsenek kedvenc jegyzeteid</h2>
        </div>
    <?php endif; ?>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
