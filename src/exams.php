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
       <title>Vizsgák</title>
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
            <h1>Vizsgák</h1>
            <p class="entry-meta">Itt találod röviden és érthetően, hogyan zajlanak a vizsgák.</p>
            <div class="content-grid">
            <article class="card">
                <h3>Szoftverfejlesztő vizsga</h3>
                <p class="entry-meta">Felépítés, menetrend, mire figyelj, tippek.</p>
                <a class="btn-cta" href="exam.php?type=sw">Megnyitás</a>
            </article>
            <article class="card">
                <h3>Rendszerüzemeltető vizsga</h3>
                <p class="entry-meta">Felépítés, menetrend, tipikus feladatok, tippek.</p>
                <a class="btn-cta" href="exam.php?type=sys">Megnyitás</a>
            </article>
            </div>
        </main>
        </div>
        <?php include 'assets/php/footer.php'; ?>
   </body>
</html>