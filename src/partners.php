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
            "tagline" => "Egy Discord bot, ami újraépült és folyamatosan fejlődik.",
            "logo" => "assets/img/partners/rangerbot.png",
            "website" => "https://rangerbot.hu/",   
            "invite"  => "https://rangerbot.hu/invite", 
            "discord" => "https://discord.gg/cgKcscUz3A", 
            "about" => [
                [
                    "title" => "A kezdetek",
                    "text"  => "Kezdetben Aki26 és MasterofAll, Aki26 apukája kezdte el a Botot csinálgatni. Eleinte csak maguknak, hogy Aki26 tanulhasson, majd később Aki26 felhozta, hogy lehetne publikus a Bot. Ezzel kezdődött minden."
                ],
                [
                    "title" => "Később...",
                    "text"  => "2020. 10. 14-én Aki26-t, a Bot fejlesztőjét feltörték, ezáltal a RangerBot-nak megszerezték a Token-jét, és elkezdtek vele, illetve Aki26 fiókjával hirdetni. A RangerBot-ot kidobták a szerverekről, mert károkat is okozott bőven. A Support szerver törlődött is a hacker által.\n\nAki26 nem tudta mi tévő legyen, ezért írt a Discordnak. A Discord nem tudott segíteni, mondván nincs mentésük a szerverekről. Aki26 és MasterofAll (egyenlőre) befejezte a RangerBot fejlesztését."
                ],
                [
                    "title" => "AvokádóZ hatására",
                    "text"  => "AvokádóZ kiírta a Discord Állapotába, hogy Bot fejlesztőket keres. MesterDavid írt Aki26-nak, hogy ezt látta. Aki26 ezáltal felkereste AvokádóZ-t. Avo azt találta ki, hogy kezdjenek el fejleszteni egy új Botot, viszont Aki26-nak ötlete támadt: mi lenne, ha a RangerBot-ot folytatnák? Aki26 újraírta a teljes RangerBot-ot újabb, frissebb tudásával. Jöttek a partnerek és egyre jobb lett a Bot. Jelenleg közeledik a 75 szerverhez, a hitelesítéshez. Ott dől el minden: Az elmúlt egy év munkája kifizetődő volt? Tud tovább haladni a RangerBot?"
                ],
                [
                    "title" => "Hitelesítés!",
                    "text"  => "2021. 04. 16-án meglett a 75 szerver, mely után 10 nappal, 04. 26-án el lett küldve Aki26 és MesterDavid által a hitelesítési kérelem a Discordnak. Ezek után kicsit több, mint egy hónappal, 06. 06-án elfogadták azt, viszont Intenteket nem kapott a Bot, ezért néhány nappal később, 06. 08-án Aki26 elküldte az első Intent kérelmet a \"Server Members\", illetve a \"Prestence\" intentekhez, melyet 08. 13-án fogadott el a Discord. Ekkor a \"Server Members\" intentet kapta meg a Bot. Később, 10. 25-én, miután megjelent a \"Message Content\" intent, Aki26 ahhoz is elküldte a kérelmet, melyet 11. 03-án el is fogadott a Discord."
                ],
                [
                    "title" => "Újraírás - búcsúzunk a C#-tól.",
                    "text"  => "Aki26 úgy döntött, ideje haladni a korral és újraírni a Botot egy modernebb, szélesebb körben használt nyelvben. A C# Bot már 2024. 05. 06-a óta offline, viszont Aki26 2026. 01. 18-án kiadta az új Botot (volt egy apróbb kihagyás :D), melynek programozási nyelve a Python."
                ],
            ]
        ]
    ];

?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') ?>">
<head>
    <title>Partnereink</title>
    <meta charset='UTF-8'>
    <meta name='description' content='<?= htmlspecialchars(t('meta_description_home'), ENT_QUOTES, "UTF-8") ?>'>
    <meta name='keywords' content='<?= htmlspecialchars(t('meta_keywords_home'), ENT_QUOTES, "UTF-8") ?>'>
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
    <?php include 'assets/php/navbar.php'; ?>
    <main class="container partners-page">
        <header class="page-header">
            <h1>Partnereink</h1>
            <p>Itt találod azokat a projekteket / közösségeket, akikkel együttműködünk.</p>
        </header>
        <section class="partners-grid">
            <?php foreach ($partners as $p): ?>
                <article class="partner-card" id="<?= htmlspecialchars(strtolower($p['name']), ENT_QUOTES, 'UTF-8') ?>">
                    <div class="partner-top">
                        <div class="partner-logo">
                            <?php
                                $logoPath = $p["logo"];
                                $hasLogo = !empty($logoPath) && file_exists(__DIR__ . "/" . $logoPath);
                            ?>
                            <?php if ($hasLogo): ?>
                                <img src="<?= htmlspecialchars($logoPath, ENT_QUOTES, 'UTF-8') ?>"
                                     alt="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?> logo">
                            <?php else: ?>
                                <div class="partner-logo-fallback" aria-label="Logo">
                                    <?= htmlspecialchars(mb_substr($p['name'], 0, 1, 'UTF-8'), ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="partner-head">
                            <h2><?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?></h2>
                            <p class="partner-tagline"><?= htmlspecialchars($p['tagline'], ENT_QUOTES, 'UTF-8') ?></p>
                            <div class="partner-links">
                                <?php if (!empty($p["website"])): ?>
                                    <a class="btn btn-secondary" href="<?= htmlspecialchars($p["website"], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Weboldal</a>
                                <?php endif; ?>
                                <?php if (!empty($p["invite"])): ?>
                                    <a class="btn btn-secondary" href="<?= htmlspecialchars($p["invite"], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Meghívás</a>
                                <?php endif; ?>
                                <?php if (!empty($p["discord"])): ?>
                                    <a class="btn btn-secondary" href="<?= htmlspecialchars($p["discord"], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Discord</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <details class="partner-about" open>
                        <summary><?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?> × About</summary>
                        <?php foreach ($p["about"] as $block): ?>
                            <div class="partner-about-block">
                                <h3><?= htmlspecialchars($block["title"], ENT_QUOTES, 'UTF-8') ?></h3>
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