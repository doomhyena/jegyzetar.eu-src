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
       <title><?= t('exams_page_title') ?></title>
       <meta charset="UTF-8">
       <meta name="description" content="<?= t('meta_description_home') ?>">
       <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
       <meta name="author" content="Baranyi Norbert, Csontos Kincső, Szekeres Levente">
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
       <link rel="stylesheet" href="assets/css/styles.css">
       <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
       <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
       <script src="assets/js/script.js" defer></script>
   </head>
   <body>
        <?php include 'assets/php/navbar.php'; ?>
        <div class="content-wrapper">
        <?php include "assets/php/ads.php"; ?>
        <main class="main">
            <h1><?= t('exams_page_title') ?></h1>
            <p class="entry-meta"><?= t('exams_page_subtitle') ?></p>
            <div class="content-grid">
            <article class="card">
                <h3><?= t('exams_sw_title') ?></h3>
                <p class="entry-meta"><?= t('exams_sw_subtitle') ?></p>
                <a class="btn-cta" href="exam.php?type=sw"><?= t('exams_open_button') ?></a>
            </article>
            <article class="card">
                <h3><?= t('exams_sys_title') ?></h3>
                <p class="entry-meta"><?= t('exams_sys_subtitle') ?></p>
                <a class="btn-cta" href="exam.php?type=sys"><?= t('exams_open_button') ?></a>
            </article>
            </div>
        </main>
        </div>
        <?php include 'assets/php/footer.php'; ?>
   </body>
</html>