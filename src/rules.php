<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require "assets/php/db.php";
    require "assets/php/lang.php";
    require "assets/php/functions.php";

    $MIN_AGE = 13;
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') ?>">
<head>
    <title><?= t('rules_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= htmlspecialchars(t('meta_description_home'), ENT_QUOTES, 'UTF-8') ?>">
    <meta name="keywords" content="<?= htmlspecialchars(t('meta_keywords_home'), ENT_QUOTES, 'UTF-8') ?>">
    <meta name="author" content="Baranyi Norbert, Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
    <?php include 'assets/php/navbar.php'; ?>
    <main class="main flex flex-col gap-6">
        <section class="card">
            <div class="rules-card">
                <h1><?= t('rules_h1') ?></h1>
                <div class="rules-badge"><?= sprintf(t('rules_min_age_badge'), (int)$MIN_AGE) ?></div>
                <p class="rules-muted"><?= sprintf(t('rules_last_updated'), date('Y-m-d')) ?></p>
                <div class="rules-note"><?= sprintf(t('rules_summary'), (int)$MIN_AGE) ?></div>
                <div class="rules-sep"></div>
                <h2><?= t('rules_s1_title') ?></h2>
                <ul class="rules-list">
                    <li><?= sprintf(t('rules_s1_li1'), (int)$MIN_AGE) ?></li>
                    <li><?= t('rules_s1_li2') ?></li>
                    <li><?= t('rules_s1_li3') ?></li>
                </ul>
                <h2><?= t('rules_s2_title') ?></h2>
                <ul class="rules-list">
                    <li><?= t('rules_s2_li1') ?></li>
                    <li><?= t('rules_s2_li2') ?></li>
                    <li><?= t('rules_s2_li3') ?></li>
                </ul>
                <h2><?= t('rules_s3_title') ?></h2>
                <p><?= t('rules_s3_intro') ?></p>
                <ul class="rules-list">
                    <li><?= t('rules_s3_li1') ?></li>
                    <li><?= t('rules_s3_li2') ?></li>
                    <li><?= t('rules_s3_li3') ?></li>
                    <li><?= t('rules_s3_li4') ?></li>
                </ul>
                <h2><?= t('rules_s4_title') ?></h2>
                <ul class="rules-list">
                    <li><?= t('rules_s4_li1') ?></li>
                    <li><?= t('rules_s4_li2') ?></li>
                    <li><?= t('rules_s4_li3') ?></li>
                </ul>
                <div class="rules-note"><?= t('rules_s4_note') ?></div>
                <h2><?= t('rules_s5_title') ?></h2>
                <ul class="rules-list">
                    <li><?= t('rules_s5_li1') ?></li>
                    <li><?= t('rules_s5_li2') ?></li>
                    <li><?= t('rules_s5_li3') ?></li>
                </ul>
                <h2><?= t('rules_s6_title') ?></h2>
                <ul class="rules-list">
                    <li><?= t('rules_s6_li1') ?></li>
                    <li><?= t('rules_s6_li2') ?></li>
                    <li><?= t('rules_s6_li3') ?></li>
                </ul>
                <h2><?= t('rules_s7_title') ?></h2>
                <ul class="rules-list">
                    <li><?= t('rules_s7_li1') ?></li>
                    <li><?= t('rules_s7_li2') ?></li>
                    <li><?= t('rules_s7_li3') ?></li>
                </ul>
                <h2><?= t('rules_s8_title') ?></h2>
                <ul class="rules-list">
                    <li><?= t('rules_s8_li1') ?></li>
                    <li><?= t('rules_s8_li2') ?></li>
                </ul>
                <h2><?= t('rules_s9_title') ?></h2>
                <p><?= t('rules_s9_intro') ?></p>
                <ul class="rules-list">
                    <li><?= t('rules_s9_li1') ?></li>
                    <li><?= t('rules_s9_li2') ?></li>
                </ul>
                <h2><?= t('rules_s10_title') ?></h2>
                <p><?= t('rules_s10_p') ?></p>
                <div class="rules-sep"></div>
                <p class="rules-muted"><?= t('rules_footer_note') ?></p>
            </div>
        </section>
    </main>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>