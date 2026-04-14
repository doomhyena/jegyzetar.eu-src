<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require "assets/php/db.php";
    require "assets/php/lang.php";
    require "assets/php/functions.php";

    $partners = [
    [
        "name" => "RangerBot",
        "tagline" => t('partner_rangerbot_tagline'),
        "logo" => "assets/img/partners/rangerbot.png",
        "website" => "https://rangerbot.hu/",
        "invite"  => "https://rangerbot.hu/invite",
        "discord" => "https://discord.gg/cgKcscUz3A",
        "about" => [
            ["title" => t('partner_rangerbot_s1_title'), "text" => t('partner_rangerbot_s1_text')],
            ["title" => t('partner_rangerbot_s2_title'), "text" => t('partner_rangerbot_s2_text')],
            ["title" => t('partner_rangerbot_s3_title'), "text" => t('partner_rangerbot_s3_text')],
            ["title" => t('partner_rangerbot_s4_title'), "text" => t('partner_rangerbot_s4_text')],
            ["title" => t('partner_rangerbot_s5_title'), "text" => t('partner_rangerbot_s5_text')],
            ]
        ]
    ];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title><?= t('partners_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= htmlspecialchars(t('meta_description_home')) ?>">
    <meta name="keywords" content="<?= htmlspecialchars(t('meta_keywords_home')) ?>">
    <meta name="author" content="Baranyi Norbert, Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
    <?php include 'assets/php/navbar.php'; ?>
    <main class="container partners-page">
        <header class="page-header">
            <h1><?= t('partners_title') ?></h1>
            <p><?= t('partners_intro') ?></p>
        </header>
        <section class="partners-grid">
            <?php foreach ($partners as $p): ?>
                <article class="partner-card" id="<?= htmlspecialchars(strtolower($p['name'])) ?>">
                    <div class="partner-top">
                        <div class="partner-logo">
                            <?php
                                $logoPath = $p["logo"];
                                $hasLogo = !empty($logoPath) && file_exists(__DIR__ . "/" . $logoPath);
                            ?>
                            <?php if ($hasLogo): ?>
                                <img src="<?= htmlspecialchars($logoPath) ?>"
                                     alt="<?= htmlspecialchars($p['name']) ?> logo">
                            <?php else: ?>
                                <div class="partner-logo-fallback" aria-label="Logo">
                                    <?= htmlspecialchars(mb_substr($p['name'], 0, 1, 'UTF-8')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="partner-head">
                            <h2><?= htmlspecialchars($p['name']) ?></h2>
                            <p class="partner-tagline"><?= htmlspecialchars($p['tagline']) ?></p>
                            <div class="partner-links">
                                <?php if (!empty($p["website"])): ?>
                                    <a class="btn btn-secondary" href="<?= htmlspecialchars($p["website"]) ?>" target="_blank" rel="noopener"><?= t('partners_btn_website') ?></a>
                                <?php endif; ?>
                                <?php if (!empty($p["invite"])): ?>
                                    <a class="btn btn-secondary" href="<?= htmlspecialchars($p["invite"]) ?>" target="_blank" rel="noopener"><?= t('partners_btn_invite') ?></a>
                                <?php endif; ?>
                                <?php if (!empty($p["discord"])): ?>
                                    <a class="btn btn-secondary" href="<?= htmlspecialchars($p["discord"]) ?>" target="_blank" rel="noopener">Discord</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <details class="partner-about" open>
                        <summary><?= htmlspecialchars($p['name']) ?> × About</summary>
                        <?php foreach ($p["about"] as $block): ?>
                            <div class="partner-about-block">
                                <h3><?= htmlspecialchars($block["title"]) ?></h3>
                                <p><?= safe_nl2br($block["text"]) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </details>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>