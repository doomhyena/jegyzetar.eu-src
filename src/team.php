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
    <title><?= t('team_title') ?></title>
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
<body>
    <?php include 'assets/php/navbar.php'; ?>
    <main class="main flex flex-col gap-6">
        <section class="card">
            <div class="hero-body">
                <div class="hero-text">
                    <h1><?= t('team_title') ?></h1>
                    <p class="hero-sub"><?= t('team_hero_sub') ?></p>
                </div>
            </div>
        </section>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <section class="card flex flex-col items-center text-center gap-3">
                <img src="assets/img/default_profile_picture.jpg"
                    alt="<?= t('team_norbert_alt') ?>"
                    class="w-28 h-28 rounded-full object-cover border-2 border-neutral-200">
                <h3>Norbert</h3>
                <span class="text-sm text-neutral-500"><a href="https://github.com/baranyi0">@baranyi0</a></span>
                <p class="entry-meta"><?= t('team_norbert_bio') ?></p>
            </section>
            <section class="card flex flex-col items-center text-center gap-3">
                <img src="assets/img/vergil_profile_picture.jpg"
                    alt="<?= t('team_anastasia_alt') ?>"
                    class="w-28 h-28 rounded-full object-cover border-2 border-neutral-200">
                <h3>Anastasia</h3>
                <span class="text-sm text-neutral-500"><a href="https://github.com/doomhyena">@doomhyena</a></span>
                <p class="entry-meta"><?= t('team_anastasia_bio') ?></p>
            </section>
            <section class="card flex flex-col items-center text-center gap-3">
                <img src="assets/img/default_profile_picture.jpg"
                    alt="<?= t('team_paladitech_alt') ?>"
                    class="w-28 h-28 rounded-full object-cover border-2 border-neutral-200">
                <h3>Paladitech</h3>
                <span class="text-sm text-neutral-500"><a href="https://github.com/PaladiTech">@paladitech</a></span>
                <p class="entry-meta"><?= t('team_paladitech_bio') ?></p>
            </section>
        </div>
    </main>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>
