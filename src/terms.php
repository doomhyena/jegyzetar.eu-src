<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title>Felhasználási feltételek – Jegyzetár</title>
    <meta charset='UTF-8'>
    <meta name='description' content='Iskolai jegyzeteket megosztó oldal'>
    <meta name='keywords' content='iskola, jegyzet, megosztás, tanulás'>
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
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
            <h1>Felhasználási feltételek</h1>
            <p class="entry-meta">Hatályos: 2025. január 1-től</p>

            <h2>1. Általános információk</h2>
            <p>
                A Jegyzetár („Szolgáltatás”) egy közösségi alapú platform,
                amely lehetőséget biztosít jegyzetek, tananyagok és egyéb
                oktatási tartalmak megosztására.
            </p>

            <p>
                A Szolgáltatás használatával a felhasználó elfogadja
                jelen Felhasználási feltételeket.
            </p>

            <h2>2. Felhasználói fiók</h2>
            <ul>
                <li>A Szolgáltatás egyes funkciói regisztrációhoz kötöttek.</li>
                <li>A felhasználó köteles valós adatokat megadni.</li>
                <li>A fiók biztonságáért a felhasználó felelős.</li>
            </ul>

            <h2>3. Feltöltött tartalmak</h2>
            <p>
                A felhasználó által feltöltött fájlokért és azok jogtisztaságáért
                kizárólag a feltöltő felel.
            </p>

            <ul>
                <li>Tilos szerzői jogot sértő tartalom feltöltése.</li>
                <li>Tilos jogellenes, sértő vagy megtévesztő tartalom közzététele.</li>
                <li>A Szolgáltató jogosult a szabályokat sértő tartalmak eltávolítására.</li>
            </ul>

            <h2>4. Kedvencek, értékelések, közösségi funkciók</h2>
            <p>
                A közösségi funkciók (kedvencek, értékelések, kommentek stb.)
                kizárólag rendeltetésszerűen használhatók.
            </p>

            <h2>5. Moderáció és fiók felfüggesztés</h2>
            <p>
                A Szolgáltató fenntartja a jogot, hogy:
            </p>
            <ul>
                <li>figyelmeztetést adjon</li>
                <li>tartalmat eltávolítson</li>
                <li>felhasználói fiókot ideiglenesen vagy véglegesen felfüggesszen</li>
            </ul>

            <h2>6. Felelősség korlátozása</h2>
            <p>
                A Szolgáltatás „ahogy van” alapon működik.
                A Szolgáltató nem vállal felelősséget az esetleges adatvesztésért,
                hibákért vagy szolgáltatáskimaradásokért.
            </p>

            <h2>7. Adatkezelés</h2>
            <p>
                A személyes adatok kezelésére az
                <a href="privacy.php">Adatkezelési tájékoztató</a>
                vonatkozik.
            </p>

            <h2>8. A feltételek módosítása</h2>
            <p>
                A Szolgáltató jogosult jelen Felhasználási feltételeket módosítani.
                A módosítások a közzététellel lépnek hatályba.
            </p>

            <h2>9. Kapcsolat</h2>
            <p>
                Kapcsolatfelvétel:
                <a href="mailto:info@jegyzetar.eu">info@jegyzetar.eu</a>
            </p>

            <p class="entry-meta">
                Utolsó frissítés: <?= date('Y.m.d') ?>
            </p>
        </section>
    </main>
</div>

<?php include "assets/php/footer.php"; ?>
</body>
</html>
