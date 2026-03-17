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
    <title><?= t('about_title') ?></title>
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
                    <h1><?= t('about_title') ?></h1>
                    <p class="hero-sub"><?= t('about_hero_sub') ?></p>
                    <div class="hero-pills">
                        <span class="pill"><?= t('about_pill_community') ?></span>
                        <span class="pill"><?= t('about_pill_structure') ?></span>
                        <span class="pill"><?= t('about_pill_ui') ?></span>
                        <span class="pill"><?= t('about_pill_dev') ?></span>
                    </div>
                </div>
                <div class="hero-actions">
                    <a class="btn-cta" href="faq.php"><?= t('about_btn_faq') ?></a>
                    <a class="btn-ghost" href="report.php"><?= t('about_btn_report') ?></a>
                </div>
            </div>
        </section>
        <div class="content-grid gap-6">
            <section class="card">
                <h3><?= t('about_what_title') ?></h3>
                <p class="entry-meta"><?= t('about_what_p1') ?></p>
                <p class="entry-meta"><?= t('about_what_p2') ?></p>
            </section>
            <section class="card">
                <h3><?= t('about_principles_title') ?></h3>
                <ul class="entry-meta" style="margin: 0; padding-left: 18px;">
                    <li><?= t('about_principle_1') ?></li>
                    <li><?= t('about_principle_2') ?></li>
                    <li><?= t('about_principle_3') ?></li>
                    <li><?= t('about_principle_4') ?></li>
                    <li><?= t('about_principle_5') ?></li>
                </ul>
            </section>
        </div>
        <section class="card">
            <h3><?= t('about_features_title') ?></h3>
            <div class="content-grid">
                <div class="card" style="box-shadow:none;">
                    <p class="entry-title"><?= t('about_feature_notes_title') ?></p>
                    <p class="entry-meta"><?= t('about_feature_notes_desc') ?></p>
                </div>
                <div class="card" style="box-shadow:none;">
                    <p class="entry-title"><?= t('about_feature_community_title') ?></p>
                    <p class="entry-meta"><?= t('about_feature_community_desc') ?></p>
                </div>
                <div class="card" style="box-shadow:none;">
                    <p class="entry-title"><?= t('about_feature_gamification_title') ?></p>
                    <p class="entry-meta"><?= t('about_feature_gamification_desc') ?></p>
                </div>
            </div>
            <p class="entry-meta" style="margin-top: 10px;"><?= t('about_motto') ?></p>
        </section>
        <section class="card">
            <h3><?= t('about_tech_title') ?></h3>
            <table>
                <thead>
                <tr>
                    <th><?= t('about_tech_col_part') ?></th>
                    <th><?= t('about_tech_col_tech') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr><td><strong>Frontend</strong></td><td><?= t('about_tech_frontend') ?></td></tr>
                <tr><td><strong>Backend</strong></td><td>PHP</td></tr>
                <tr><td><strong>MySQL</strong></td><td>MySQL</td></tr>
                <tr><td><strong>Git</strong></td><td>Git + GitHub</td></tr>
                <tr><td><strong>Hosting</strong></td><td>Rackhost</td></tr>
                </tbody>
            </table>
            <p class="entry-meta"><?= t('about_tech_docs') ?></p>
        </section>
        <section class="card">
            <h3><?= t('about_team_title') ?></h3>
            <p class="entry-meta"><?= t('about_team_p1') ?></p>
            <p class="entry-meta"><?= t('about_team_p2') ?></p>
        </section>
        <section class="card">
            <h3><?= t('about_legal_title') ?></h3>
            <p class="entry-meta"><?= t('about_legal_p1') ?></p>
            <ul class="entry-meta" style="margin: 0; padding-left: 18px;">
                <li><?= t('about_legal_copyright') ?></li>
                <li><?= t('about_legal_personal') ?></li>
                <li><?= t('about_legal_liability') ?></li>
            </ul>
            <p class="entry-meta" style="margin-top: 10px;"><?= t('about_legal_links') ?></p>
        </section>
        <section class="card">
            <div class="hero-body">
                <div class="hero-text">
                    <p class="entry-title" style="margin:0;"><?= t('about_cta_title') ?></p>
                    <p class="entry-meta" style="margin: 4px 0 0;"><?= t('about_cta_sub') ?></p>
                </div>
                <div class="hero-actions">
                    <a class="btn-cta" href="contact.php"><?= t('about_btn_contact') ?></a>
                    <a class="btn-ghost" href="report.php"><?= t('about_btn_report') ?></a>
                </div>
            </div>
        </section>
    </main>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>