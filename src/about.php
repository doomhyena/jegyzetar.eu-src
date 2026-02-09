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
    <title>Rólunk</title>
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
                    <h1>Rólunk</h1>
                    <p class="hero-sub">
                        A <strong>Jegyzetár</strong> egy közösségi jegyzetmegosztó platform diákoknak:
                        ossz meg, rendszerezz és találj jegyzeteket egyszerűen – egy helyen, átláthatóan.
                    </p>
                    <div class="hero-pills">
                        <span class="pill">Közösségi tudásmegosztás</span>
                        <span class="pill">Áttekinthető felépítés</span>
                        <span class="pill">Reszponzív, modern UI</span>
                        <span class="pill">Folyamatos fejlesztés</span>
                    </div>
                </div>
                <div class="hero-actions">
                    <a class="btn-cta" href="faq.php">GYIK</a>
                    <a class="btn-ghost" href="report.php">Hibajelentés</a>
                </div>
            </div>
        </section>
        <div class="content-grid gap-6">
            <section class="card">
                <h3>Mi az a Jegyzetár?</h3>
                <p class="entry-meta">
                    A Jegyzetár egy diákok által épített, webalapú platform, amely lehetőséget ad jegyzetek
                    megosztására, böngészésére és közös tanulásra.
                </p>
                <p class="entry-meta">
                    Célunk egy olyan központi tudástár létrehozása, amely kiváltja a szétszórt Messenger-, Drive-
                    és e-mail-alapú megoldásokat.
                </p>
            </section>
            <section class="card">
                <h3>Alapelveink</h3>
                <ul class="entry-meta" style="margin: 0; padding-left: 18px;">
                    <li>Egyszerű használat</li>
                    <li>Áttekinthető felépítés</li>
                    <li>Közösségi tudásmegosztás</li>
                    <li>Biztonságos, felelős működés</li>
                </ul>
            </section>
        </div>
        <section class="card">
            <h3>Fő funkciók</h3>
            <div class="content-grid">
                <div class="card" style="box-shadow:none;">
                    <p class="entry-title">Jegyzetkezelés</p>
                    <p class="entry-meta">
                        Jegyzetek feltöltése, rendszerezése és letöltése; gyors keresés tantárgy, évfolyam és kulcsszó alapján.
                    </p>
                </div>
                <div class="card" style="box-shadow:none;">
                    <p class="entry-title">Közösségi funkciók</p>
                    <p class="entry-meta">
                        Kommentelés, értékelés, kedvencek, és aktivitás-alapú közösségi visszajelzés.
                    </p>
                </div>
                <div class="card" style="box-shadow:none;">
                    <p class="entry-title">Gamifikáció & bővítés</p>
                    <p class="entry-meta">
                        Pontok, jelvények és (jövőben) valós idejű közös jegyzetelés, AI-alapú funkciók.
                    </p>
                </div>
            </div>
            <p class="entry-meta" style="margin-top: 10px;">
                Közösségi mottónk: <strong>„Tanuljunk együtt, ne külön-külön.”</strong>
            </p>
        </section>

        <!-- TECH STACK -->
        <section class="card">
            <h3>Használt technológiák</h3>
            <table>
                <thead>
                <tr>
                    <th>Rész</th>
                    <th>Technológia</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><strong>Frontend</strong></td>
                    <td>React.js (tervezett), modern reszponzív UI</td>
                </tr>
                <tr>
                    <td><strong>Backend</strong></td>
                    <td>PHP</td>
                </tr>
                <tr>
                    <td><strong>Adatbázis</strong></td>
                    <td>MySQL</td>
                </tr>
                <tr>
                    <td><strong>Verziókezelés</strong></td>
                    <td>Git + GitHub</td>
                </tr>
                <tr>
                    <td><strong>Hosting</strong></td>
                    <td>Rackhost</td>
                </tr>
                </tbody>
            </table>
            <p class="entry-meta">
                A részletes telepítési/használati útmutató a fejlesztői dokumentációban található.
            </p>
        </section>

        <!-- NOTE FORGE / CSAPAT -->
        <section class="card">
            <h3>NoteForge Development – a csapat és a megalakulás</h3>
            <p class="entry-meta">
                A NoteForge Development a Jegyzetár mögött álló fejlesztői csapat. A projektet diákok indították,
                azzal a céllal, hogy egy modern, közösségi tudástár szülessen, ami valódi problémát old meg a mindennapi tanulásban.
            </p>
            <p class="entry-meta">
                A fejlesztés folyamatos, a projekt nyitott új ötletekre, visszajelzésekre és későbbi közreműködőkre.
            </p>
        </section>
        <section class="card">
            <h3>Rövid jogi nyilatkozat</h3>

            <p class="entry-meta">
                A Jegyzetár egy <strong>oktatási célú</strong>, diákok által fejlesztett projekt. A platformon megjelenő tartalmakért
                elsődlegesen a feltöltők felelnek.
            </p>

            <ul class="entry-meta" style="margin: 0; padding-left: 18px;">
                <li><strong>Jogvédett tartalom:</strong> kérünk, ne tölts fel teljes tankönyveket, fizetős anyagokat vagy más, engedélyhez kötött tartalmat.</li>
                <li><strong>Személyes adatok:</strong> ne ossz meg érzékeny információkat (pl. lakcím, telefonszám, diákigazolvány, osztálynapló fotó).</li>
                <li><strong>Felelősségkorlátozás:</strong> mindent ésszerű keretek között teszünk a biztonságért, de a szolgáltatást „ahogy van” alapon biztosítjuk.</li>
            </ul>

            <p class="entry-meta" style="margin-top: 10px;">
                Részletekért nézd meg az <a href="terms.php">ÁSZF</a> és az <a href="privacy.php">Adatvédelem</a> oldalt.
            </p>
        </section>
        <section class="card">
            <div class="hero-body">
                <div class="hero-text">
                    <p class="entry-title" style="margin:0;">Van ötleted vagy észrevételed?</p>
                    <p class="entry-meta" style="margin: 4px 0 0;">
                        Írj nekünk, vagy jelezd a hibát - a Jegyzetár attól lesz jobb, hogy használjátok és visszajelzést adtok.
                    </p>
                </div>
                <div class="hero-actions">
                    <a class="btn-cta" href="contact.php">Kapcsolat</a>
                    <a class="btn-ghost" href="report.php">Hibajelentés</a>
                </div>
            </div>
        </section>
    </main>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>
