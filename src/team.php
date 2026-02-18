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
    <title>Csapattagjaink</title>
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
<body>
    <?php include 'assets/php/navbar.php'; ?>
    <main class="main flex flex-col gap-6">
        <section class="card">
            <div class="hero-body">
                <div class="hero-text">
                    <h1>Csapattagjaink</h1>
                    <p class="hero-sub">
                        A Jegyzetár mögött egy lelkes diákcsapat áll, akik elkötelezettek a tudásmegosztás és a
                        tanulás iránt. Ismerd meg a csapattagokat, akik nap mint nap azon dolgoznak, hogy a
                        Jegyzetár egy jobb hely legyen!
                    </p>
                </div>
            </div>
        </section>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <section class="card flex flex-col items-center text-center gap-3">
                    <img src="assets/img/default_profile_picture.jpg"
                        alt="Baranyi Norbert profilképe"
                        class="w-28 h-28 rounded-full object-cover border-2 border-neutral-200">
                    <h3>Baranyi Norbert</h3>
                    <span class="text-sm text-neutral-500"><a href="https://github.com/baranyi0">@baranyi0</a></span>
                    <p class="entry-meta">
                        Baranyi Norbert a Jegyzetár fejlesztésében elsősorban a backend és az adatkezelés kulcsterületein dolgozott. 
                        Nevéhez köthető a hitelesítési folyamatok megvalósítása (például a kétlépcsős azonosítás és az e-mailes visszaigazolás), valamint 
                        a jegyzetekhez kapcsolódó funkciók fejlesztése: a jegyzet adatlap, értékelések, kedvencek és a tag-alapú rendszerezés. 
                        Emellett több adatbázis-bővítést és feltöltési logika javítást is elvégzett, hogy a Jegyzetár stabilan és biztonságosan működjön.
                    </p>
                </section>
                <section class="card flex flex-col items-center text-center gap-3">
                    <img src="assets/img/cska_profile_picture.png"
                        alt="Csontos Kincső Anasztázia profilképe"
                        class="w-28 h-28 rounded-full object-cover border-2 border-neutral-200">
                    <h3>Csontos Kincső Anasztázia</h3>
                    <span class="text-sm text-neutral-500"><a href="https://github.com/doomhyena">@doomhyena</a></span>
                    <p class="entry-meta">
                        Csontos Kincső Anasztázia a Jegyzetár alapítója és vezető fejlesztője. 
                        A projektben a felhasználói élmény, a vizuális egység és a rendszer-szintű minőség fejlesztése volt 
                        a fő fókusza: a teljes felület újradizájnolása (Aurora UI stílus, reszponzív layout, 
                        navigáció újratervezése), a többnyelvű rendszer bevezetése, valamint a profiloldal funkcióinak bővítése 
                        (bio, jelvények, egyedi CSS kérelem és előnézet). Emellett több biztonsági és stabilitási fejlesztést is 
                        megvalósított (adatbázis helper függvények, SQL-injection védelem), dolgozott a jelentés/moderációs rendszeren, 
                        bevezette a Discord OAuth bejelentkezést, és elkészítette a projekt dokumentációját is.
                    </p>
                </section>
                <section class="card flex flex-col items-center text-center gap-3">
                    <img src="assets/img/default_profile_picture.jpg"
                        alt="Szekeres Levente profilképe"
                        class="w-28 h-28 rounded-full object-cover border-2 border-neutral-200">
                    <h3>Szekeres Levente</h3>
                    <span class="text-sm text-neutral-500"><a href="https://github.com/PaladiTech">@paladitech</a></span>
                    <p class="entry-meta">
                        Szekeres Levente a Jegyzetár közösségi bővítésein és mobilos használhatóságán dolgozott. 
                        Teljes körűen megvalósította a tanulócsoport funkciókat (csoport létrehozás, tagságkezelés, jelentkezések), 
                        valamint a csoporton belüli jegyzetfeltöltést jóváhagyási és moderációs folyamattal. 
                        Emellett több hibajavítást és stabilizálást végzett a csoportos modulon, fejlesztette a mobil navigációt, 
                        és részt vett az oldalon megjelenő hirdetések integrálásában is.
                    </p>
                </section>
            </div>
    </main>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>
