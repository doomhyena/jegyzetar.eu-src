<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title><?= t('terms_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name="author" content="Baranyi Norbert, Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body class="theme-default">
<?php include "assets/php/navbar.php"; ?>
<div class="content-wrapper">
    <main class="main">
        <section class="card legal-page">
            <h1><?= t('terms_h1') ?></h1>
            <p class="entry-meta"><?= t('terms_effective') ?></p>
            <h2><?= t('terms_s1_title') ?></h2>
            <p><?= t('terms_s1_p1') ?></p>
            <p><?= t('terms_s1_p2') ?></p>
            <h2><?= t('terms_s2_title') ?></h2>
            <ul>
                <li><?= t('terms_s2_li1') ?></li>
                <li><?= t('terms_s2_li2') ?></li>
                <li><?= t('terms_s2_li3') ?></li>
            </ul>
            <h2><?= t('terms_s3_title') ?></h2>
            <p><?= t('terms_s3_p1') ?></p>
            <ul>
                <li><?= t('terms_s3_li1') ?></li>
                <li><?= t('terms_s3_li2') ?></li>
                <li><?= t('terms_s3_li3') ?></li>
            </ul>
            <h2><?= t('terms_s4_title') ?></h2>
            <p><?= t('terms_s4_p1') ?></p>
            <h2><?= t('terms_s5_title') ?></h2>
            <p><?= t('terms_s5_p1') ?></p>
            <ul>
                <li><?= t('terms_s5_li1') ?></li>
                <li><?= t('terms_s5_li2') ?></li>
                <li><?= t('terms_s5_li3') ?></li>
            </ul>
            <h2><?= t('terms_s6_title') ?></h2>
            <p><?= t('terms_s6_p1') ?></p>
            <h2><?= t('terms_s7_title') ?></h2>
            <p><?= t('terms_s7_p1') ?></p>
            <h2><?= t('terms_s8_title') ?></h2>
            <p><?= t('terms_s8_p1') ?></p>
            <h2><?= t('terms_s9_title') ?></h2>
            <p><?= t('terms_s9_p1') ?> <a href="mailto:info@jegyzetar.eu">info@jegyzetar.eu</a></p>
            <p class="entry-meta"><?= sprintf(t('terms_last_updated'), date('Y.m.d')) ?></p>
        </section>
    </main>
</div>
<?php include "assets/php/footer.php"; ?>
</body>
</html>