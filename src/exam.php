<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    $type = $_GET['type'] ?? 'sw';
    if (!in_array($type, ['sw','sys'], true)) $type = 'sw';

    $content = [
        'sw' => [
            'title' => 'Szoftverfejlesztő és tesztelő – Vizsgák',
            'subtitle' => 'Áttekintés az ágazati alapvizsgáról és a szakmai vizsgáról, a mérés és értékelés szempontjaival.',
            'sections' => [
                [
                    'h' => 'Ágazati alapvizsga – Írásbeli vizsga (interaktív teszt)',
                    'list' => [
                        'Megnevezés: „Informatikai és távközlési alapok interaktív teszt”.',
                        '15 db számítógépen megoldandó tesztfeladatból áll.',
                        'Feladattípusok: feleletválasztós (egyszeres, többszörös, illesztés) és kiegészítést igénylő feleletalkotó.',
                        'A teszt értékelésének automatizálhatónak kell lennie.',
                        'Segédanyag nem használható.',
                        'Időtartam: 30 perc.',
                        'Aránya az ágazati alapvizsgán belül: 10%.'
                    ]
                ],
                [
                    'h' => 'Ágazati alapvizsga – Írásbeli értékelés',
                    'list' => [
                        'Minden feladat 2 pontot ér.',
                        'Részleges megoldásért részpontszám adható.',
                        'Nem adható maximális pont, ha a megoldás hibás választ is tartalmaz.',
                        'Az értékelés százalékos formában történik.',
                        'Eredményes, ha a megszerezhető összes pontszám legalább 40%-át eléri a tanuló.'
                    ]
                ],
                [
                    'h' => 'Ágazati alapvizsga – Gyakorlati vizsga (feladatsor)',
                    'list' => [
                        'Megnevezés: „Weboldalak kódolása, programozás, hálózatok gyakorlat”.',
                        'Az írásbeli és a gyakorlati vizsgatevékenység külön napon kerül megrendezésre.',
                        'Időtartam: egybefüggő 180 perc; a 3 feladatrészre javasolt időkeret 60–60 perc (a beosztás a vizsgázó döntése).',
                        'Internetkapcsolat biztosított lehet, de csak általános keresésre; kommunikációra vagy célirányos anyagletöltésre nem használható (az adott feladat útmutatója szerint akár korlátozva is lehet).',
                        'A gyakorlati vizsga 3 feladatrészből áll.'
                    ]
                ],
                [
                    'h' => 'Ágazati alapvizsga – Gyakorlati A) Weboldalak kódolása (mit kell csinálni?)',
                    'list' => [
                        'Egy egyszerű, de reszponzív weblapot kell elkészíteni.',
                        'Kapott anyagok: wireframe (vázszerkezet), forrásszövegek, képek, formai elváráslista.',
                        'A HTML-oldalnak tartalmaznia kell az előírt alapvető és szemantikus HTML-elemeket.',
                        'Formázás csatolt CSS fájllal történik.',
                        'A kész oldalt HTML-validátorral ellenőrizni kell.'
                    ]
                ],
                [
                    'h' => 'Ágazati alapvizsga – Gyakorlati A) Weboldalak kódolása (mért készségek – példák)',
                    'list' => [
                        'HTML5 oldalszerkezet (!DOCTYPE, html, head, body, meta) és szemantikus elemek (header, nav, main, section, footer).',
                        'Strukturális elemek (p, title, h1–h6, img, a, link, strong, em, figure, figcaption, div, span).',
                        'Attribútumok (href, target, src, alt, lang, charset stb.).',
                        'Listák, táblázatok készítése.',
                        'CSS: inline/internal/external; szelektorok; alapvető CSS3 jellemzők; media query, töréspontok; egységek (em/rem/%/vw/vh).',
                        'Bootstrap alapok (ha a kiadott feladatban szerepel).'
                    ]
                ],
                [
                    'h' => 'Szakmai vizsga – A vizsga célja',
                    'p' => 'A szakmai vizsga célja annak igazolása, hogy a vizsgázó képes önállóan szakmai feladatokat megoldani, érti a szoftverfejlesztés és tesztelés alapelveit, és azokat gyakorlati környezetben alkalmazni tudja. Ez a vizsga már összetettebb feladatokon keresztül méri a szakmai kompetenciákat.'
                ],
                [
                    'h' => 'Szakmai vizsga – A vizsga részei',
                    'list' => [
                        'Központi interaktív vizsgarész (inkább elméleti jellegű, teszt vagy feladatsor formában).',
                        'Gyakorlati vizsgarész vagy projektfeladat, ahol egy komplexebb feladatot kell megoldani.',
                        'A gyakorlati részhez gyakran dokumentáció készítése is tartozik.'
                    ]
                ],
                [
                    'h' => 'Szakmai vizsga – Mit mér a vizsga?',
                    'list' => [
                        'Szoftverfejlesztési alapismeretek (tervezés, megvalósítás, tesztelés).',
                        'Programozási és adatkezelési ismeretek.',
                        'Hibakeresési és tesztelési képességek.',
                        'Dokumentálási készség.',
                        'A szakmai szabályok és jó gyakorlatok betartása.'
                    ]
                ],
                [
                    'h' => 'Szakmai vizsga – Értékelés szempontjai',
                        'list' => [
                        'A megoldás szakmai helyessége.',
                        'A program vagy alkalmazás működőképessége.',
                        'A feladat teljes körű megvalósítása.',
                        'A kód és a megoldás áttekinthetősége, struktúrája.',
                        'A dokumentáció minősége.',
                        'A vizsgázó munkamódszere és problémamegoldása.'
                    ]
                ],
            ],
        ],
        'sys' => [
            'title' => 'Informatikai rendszer- és alkalmazás-üzemeltető – Vizsgák',
            'subtitle' => 'Áttekintés az ágazati alapvizsgáról és a szakmai vizsgáról, a mérés és értékelés fő szempontjaival.',
            'sections' => [
                [
                    'h' => 'Ágazati alapvizsga – Vizsgára bocsátás feltétele',
                    'p' => 'Az ágazati alapvizsgára bocsátás feltétele: valamennyi előírt képzési évfolyam eredményes teljesítése.'
                ],
                [
                    'h' => 'Ágazati alapvizsga – Írásbeli vizsga (interaktív teszt)',
                    'list' => [
                        'Megnevezés: „Informatikai és távközlési alapok interaktív teszt”.',
                        '15 db számítógépen megoldandó tesztfeladatból áll.',
                        'Feladattípusok: feleletválasztós (egyszeres, többszörös, illesztés) és kiegészítést igénylő feleletalkotó.',
                        'A teszt értékelésének automatizálhatónak kell lennie.',
                        'Segédanyag nem használható.',
                        'Időtartam: 30 perc.',
                        'Aránya az ágazati alapvizsgán belül: 10%.'
                    ]
                ],
                [
                    'h' => 'Ágazati alapvizsga – Írásbeli értékelés',
                    'list' => [
                        'Minden feladat 2 pontot ér.',
                        'Részleges megoldásért részpontszám adható.',
                        'Nem adható maximális pont, ha a megoldás hibás választ is tartalmaz.',
                        'Az értékelés százalékos formában történik.',
                        'Eredményes, ha a megszerezhető összes pontszám legalább 40%-át eléri a tanuló.'
                    ]
                ],
                [
                    'h' => 'Ágazati alapvizsga – Gyakorlati vizsga (feladatsor)',
                    'list' => [
                        'Megnevezés: „Weboldalak kódolása, programozás, hálózatok gyakorlat”.',
                        'Az írásbeli és a gyakorlati vizsgatevékenység külön napon kerül megrendezésre.',
                        'Időtartam: egybefüggő 180 perc; a 3 feladatrészre javasolt időkeret 60–60 perc (a beosztás a vizsgázó döntése).',
                        'Internetkapcsolat biztosított lehet, de csak általános keresésre; kommunikációra vagy célirányos anyagletöltésre nem használható (az adott feladat útmutatója szerint akár korlátozva is lehet).',
                        'A gyakorlati vizsga 3 feladatrészből áll.'
                    ]
                ],
                [
                    'h' => 'Ágazati alapvizsga – Gyakorlati A) Weboldalak kódolása (mit kell csinálni?)',
                    'list' => [
                        'Egy egyszerű, de reszponzív weblapot kell elkészíteni.',
                        'Kapott anyagok: wireframe (vázszerkezet), forrásszövegek, képek, formai elváráslista.',
                        'A HTML-oldalnak tartalmaznia kell az előírt alapvető és szemantikus HTML-elemeket.',
                        'Formázás csatolt CSS fájllal történik.',
                        'A kész oldalt HTML-validátorral ellenőrizni kell.'
                    ]
                ],
                [
                    'h' => 'Ágazati alapvizsga – Gyakorlati A) Weboldalak kódolása (mért készségek – példák)',
                    'list' => [
                        'HTML5 oldalszerkezet (!DOCTYPE, html, head, body, meta) és szemantikus elemek (header, nav, main, section, footer).',
                        'Strukturális elemek (p, title, h1–h6, img, a, link, strong, em, figure, figcaption, div, span).',
                        'Attribútumok (href, target, src, alt, lang, charset stb.).',
                        'Listák, táblázatok készítése.',
                        'CSS: inline/internal/external; szelektorok; alapvető CSS3 jellemzők; media query, töréspontok; egységek (em/rem/%/vw/vh).',
                        'Bootstrap alapok (ha a kiadott feladatban szerepel).'
                    ]
                ],
                [
                    'h' => 'Szakmai vizsga – Áttekintés',
                    'p' => 'A szakmai vizsga célja annak mérése, hogy a vizsgázó a rendszer- és alkalmazásüzemeltetési feladatokat önállóan, szakszerűen és dokumentáltan képes-e elvégezni (konfigurálás, üzemeltetés, hibakeresés, hálózati beállítások, biztonsági alapelvek).'
                ],
                [
                    'h' => 'Szakmai vizsga – Mit szoktak mérni?',
                    'list' => [
                        'Rendszer- és hálózati beállítások helyessége, működőképessége.',
                        'Hibakeresés módszeressége (logok, diagnosztika, lépések dokumentálása).',
                        'Biztonságos beállítások (jogosultságok, jelszavak, alap védelmi elvek).',
                        'Dokumentáció minősége (mit, miért, hogyan állítottál be).'
                    ]
                ],
                [
                    'h' => 'Szakmai vizsga – Értékelés fő szempontjai',
                    'list' => [
                        'A megoldás működése (a szolgáltatás/hálózat tényleg megy-e).',
                        'A feladat teljessége (minden elvárt rész megvan-e).',
                        'Szakszerűség (jó gyakorlatok, átlátható konfiguráció).',
                        'Dokumentáltság és ellenőrzések (show/parancsok, tesztek, logolás).'
                    ]
                ],
            ],
        ],
    ];
    $page = $content[$type];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title><?= htmlspecialchars($page['title']) ?></title>
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
        <div class="content-wrapper">
            <?php include "assets/php/ads.php"; ?>
            <main class="main">
                <article class="card">
                <header class="card-head">
                    <div style="min-width:0">
                    <h1 class="entry-title"><?= htmlspecialchars($page['title']) ?></h1>
                    <p class="entry-meta"><?= htmlspecialchars($page['subtitle']) ?></p>
                    </div>
                    <a class="btn-ghost" href="exams.php"><- Vissza</a>
                </header>
                <div class="exam-sections">
                    <?php foreach ($page['sections'] as $s): ?>
                    <section class="exam-section">
                        <h3><?= htmlspecialchars($s['h']) ?></h3>
                        <?php if (!empty($s['p'])): ?>
                        <p class="entry-meta" style="font-size:1rem; color: var(--text); opacity:.95;">
                            <?= htmlspecialchars($s['p']) ?>
                        </p>
                        <?php endif; ?>
                        <?php if (!empty($s['list'])): ?>
                        <ul class="exam-list">
                            <?php foreach ($s['list'] as $item): ?>
                            <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </section>
                    <?php endforeach; ?>
                </div>
                </article>
            </main>
        </div>
        <?php include 'assets/php/footer.php'; ?>
    </body>
</html>



