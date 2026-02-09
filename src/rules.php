<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require "assets/php/db.php";
    require "assets/php/lang.php";
    require "assets/php/functions.php";

    // Korhatár (tervek szerint 13)
    $MIN_AGE = 13;
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') ?>">
<head>
    <title>Szabályzat</title>
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
    <main class="main flex flex-col gap-6">
        <section class="card">
            <div class="rules-card">
                <h1>Jegyzetár – Szabályzat</h1>
                <div class="rules-badge">Minimális korhatár: <?= (int)$MIN_AGE ?> év</div>
                <p class="rules-muted">
                    Utolsó frissítés: <?= date('Y-m-d') ?> • A Jegyzetár használatával elfogadod az alábbi szabályokat.
                </p>
                <div class="rules-note">
                    <strong>Röviden:</strong> légy tiszteletteljes, ne tölts fel illegális / jogsértő tartalmat,
                    ne csalj a pontokkal, és csak akkor használd a platformot, ha betöltötted a <?= (int)$MIN_AGE ?>. életéved.
                </div>
                <div class="rules-sep"></div>
                <h2>1) Korhatár és jogosultság</h2>
                <ul class="rules-list">
                    <li>A Jegyzetár használatához minimum <strong><?= (int)$MIN_AGE ?> éves</strong> életkor szükséges.</li>
                    <li>Regisztrációkor valós adatokat adj meg (különösen a születési dátumot).</li>
                    <li>Ha kiderül, hogy a felhasználó nem érte el a korhatárt, a fiókot <strong>korlátozhatjuk vagy törölhetjük</strong>.</li>
                </ul>
                <h2>2) Fiókbiztonság és hozzáférés</h2>
                <ul class="rules-list">
                    <li>A fiókodért te felelsz: jelszót, 2FA-t (ha van) ne oszd meg másokkal.</li>
                    <li>Tilos más fiókjába belépni, vagy erre kísérletet tenni.</li>
                    <li>Gyanús tevékenységet jelents a platformon belül vagy az adminoknak.</li>
                </ul>
                <h2>3) Engedélyezett és tiltott tartalmak</h2>
                <p>
                    A Jegyzetár célja az <strong>oktatási jellegű</strong> jegyzetek megosztása. Ennek megfelelően:
                </p>
                <ul class="rules-list">
                    <li><strong>Engedélyezett:</strong> saját készítésű jegyzet, összefoglaló, kidolgozott tétel, gyakorló feladat (jogszerűen).</li>
                    <li><strong>Tiltott:</strong> gyűlöletkeltő, zaklató, pornográf, erőszakos, önsértésre buzdító, vagy bármilyen jogellenes tartalom.</li>
                    <li><strong>Tiltott:</strong> személyes adatok közzététele (pl. telefonszám, lakcím, mások e-mailje, osztálylista, igazolvány).</li>
                    <li><strong>Tiltott:</strong> vírusos / kártékony fájlok, linkek, adathalászat.</li>
                </ul>
                <h2>4) Szerzői jog és forrásmegjelölés</h2>
                <ul class="rules-list">
                    <li>Csak olyan anyagot tölts fel, aminek a megosztására <strong>jogosult vagy</strong> (saját anyag, vagy engedéllyel / szabad licenc alatt).</li>
                    <li>Tilos tankönyvek, fizetős kurzusanyagok, megvásárolt PDF-ek, zárt rendszerekből származó anyagok teljes terjedelmű feltöltése.</li>
                    <li>Ha hivatkozol más forrásra, jelöld meg (pl. könyv címe, szerző, link, év).</li>
                </ul>
                <div class="rules-note">
                    <strong>Fontos:</strong> jogsértő anyag esetén a tartalmat eltávolíthatjuk, és ismételt esetben a fiókot korlátozhatjuk.
                </div>
                <h2>5) Közösségi viselkedés (kommentek, értékelések)</h2>
                <ul class="rules-list">
                    <li>Légy kulturált: tilos a személyeskedés, zaklatás, fenyegetés, sértegetés.</li>
                    <li>Ne spam-elj (ismétlődő kommentek, reklám, értelmetlen tartalom).</li>
                    <li>Az értékelés legyen őszinte és releváns (nem bosszúból / haveri alapon).</li>
                </ul>
                <h2>6) Pontok, badge-ek és visszaélések</h2>
                <ul class="rules-list">
                    <li>Tilos a pontokkal való manipuláció (pl. tömeges kamu fiókok, egymás mesterséges felpontozása).</li>
                    <li>Tilos automatizált eszközök használata (botok, scriptelt letöltések/feltöltések) a rendszer kijátszására.</li>
                    <li>Visszaélés esetén pontlevonás, badge visszavonás, ideiglenes vagy végleges tiltás alkalmazható.</li>
                </ul>
                <h2>7) Moderáció és jelentések</h2>
                <ul class="rules-list">
                    <li>A moderátorok/adminok eltávolíthatnak tartalmat, ha az sérti a szabályzatot.</li>
                    <li>Ha szabályszegést látsz, jelentsd (pl. jegyzet oldalon / kommentnél).</li>
                    <li>Ismételt vagy súlyos szabályszegés fióktiltással járhat.</li>
                </ul>
                <h2>8) Prémium funkciók és fizetés (ha bevezetésre kerül)</h2>
                <ul class="rules-list">
                    <li>A prémium célja: extra kényelmi funkciók (offline, statisztikák, reklámmentesség, AI-funkciók).</li>
                    <li>A vásárlás/fizetés részleteit külön ÁSZF / előfizetési feltételek szabályozhatják.</li>
                </ul>
                <h2>9) Adatvédelem és személyes adatok</h2>
                <p>
                    A személyes adatok kezeléséről az <strong><a href="privacy.php">Adatkezelési tájékoztató</a></strong> ad részletes információt.
                </p>
                <ul class="rules-list">
                    <li>Ne ossz meg mások személyes adatait.</li>
                    <li>Ha 13–18 éves vagy, különösen figyelj arra, hogy mit töltesz fel (név, osztály, iskola, arc a dokumentumon stb.).</li>
                </ul>
                <h2>10) Szabályzat módosítása</h2>
                <p>
                    A szabályzatot időnként frissíthetjük (funkcióbővítés, jogi megfelelés, biztonság).
                    A változások a közzétételtől érvényesek.
                </p>
                <div class="rules-sep"></div>
                <p class="rules-muted">
                    Ha kérdésed van, írj az adminoknak / projektcsapatnak a platformon megadott elérhetőségen.
                </p>
            </div>
        </section>
    </main>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>
