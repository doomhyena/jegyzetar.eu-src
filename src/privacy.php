<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    $siteName = 'Jegyzetár';
    $siteDomain = 'https://jegyzetar.eu';
    $dataController  = 'Csontos Kincső Anastázia';
    $controllerForm  = 'Magánszemély';
    $contactEmail = 'adatvedelem@jegyzetar.eu';
    $effectiveDate = '2026-01-18';

    $hasRegistration = true;
    $storesNotes = true;
    $usesCookies = true;
    $usesAnalytics = false;
    $hasNewsletter = false;
    $hasContactForm = true;
    $usesPayments = false;
    $usesThirdPartyLogin = true; 

    $processors = [
        ['name' => 'Rackhost Kft.', 'purpose' => 'webtárhely és szerver üzemeltetés', 'location' => 'EU']
    ];

    function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

    $dtHuman = date('Y. m. d.', strtotime($effectiveDate));


?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title>Adatkezelés</title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-iconre" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
    <?php include 'assets/php/navbar.php'; ?>
    <div class="max-w-4xl mx-auto w-full px-2 sm:px-4">
        <main class="main break-words">
            <section class="card hover:translate-y-0">
                <div class="flex flex-col gap-2">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight m-0">Adatvédelmi Tájékoztató</h1>
                    <p class="meta m-0 text-sm sm:text-base">
                        <strong><?php echo h($siteName); ?></strong> - hatálybalépés: <strong><?php echo h($dtHuman); ?></strong>
                    </p>
                    <p class="small m-0 text-sm sm:text-base text-[var(--muted)]">
                        Ez a tájékoztató azt írja le, hogyan kezeljük a személyes adatokat a <?php echo h($siteName); ?> weboldal és szolgáltatás használata során.
                    </p>
                </div>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">0. Rövid összefoglalás</h2>
                <ul class="mt-4 space-y-2 list-disc pl-5">
                    <li>A <?php echo h($siteName); ?> a szolgáltatás működtetéséhez szükséges adatokat kezeli (pl. fiókadatok, technikai naplók<?php echo $storesNotes ? ', és a jegyzetek tartalma' : ''; ?>).</li>
                    <li>Csak olyan adatot kérünk, ami a működéshez kell; nem értékesítünk személyes adatokat.</li>
                    <li><?php echo $usesCookies ? 'A weboldal munkamenet (session) cookie-kat használhat a belépés és a biztonság érdekében.' : 'A weboldal nem használ a működéshez szükséges cookie-kon túlmutató sütiket.'; ?></li>
                    <li>Hibabejelentéskor / kapcsolatfelvételkor te döntöd el, mit osztasz meg velünk; ezeket a probléma rendezése után töröljük vagy anonimizáljuk.</li>
                    <li>Adatvédelmi kérdésben a következő címen érsz el minket: <a class="break-all" href="mailto:<?php echo h($contactEmail); ?>"><?php echo h($contactEmail); ?></a></li>
                </ul>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">1. Bevezetés</h2>
                <p class="mt-4 mb-0 text-[var(--text)]">
                    A <?php echo h($siteName); ?> (a továbbiakban: „szolgáltatás”) célja, hogy jegyzetek készítését, rendszerezését és kezelését tegye lehetővé.
                    Ez a tájékoztató bemutatja, milyen adatokat kezelünk, milyen célból, mennyi ideig, és milyen jogaid vannak.
                </p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">2. Adatkezelő azonosítása</h2>
                <div class="mt-4 space-y-2">
                    <p class="m-0"><strong>Adatkezelő:</strong> <?php echo h($dataController); ?><?php echo $controllerForm ? ' ('.h($controllerForm).')' : ''; ?></p>
                    <p class="m-0"><strong>Kapcsolattartó e-mail:</strong> <a class="break-all" href="mailto:<?php echo h($contactEmail); ?>"><?php echo h($contactEmail); ?></a></p>
                </div>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">3. Milyen adatokat kezelünk és honnan?</h2>
                <?php if ($hasRegistration): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]">3.1. Fiókhoz kapcsolódó adatok</h3>
                    <ul class="space-y-2 list-disc pl-5">
                    <li>kötelezően megadott adatok: e-mail cím (és/vagy felhasználónév), jelszó <em>(jelszót csak titkosított/hash formában tárolunk)</em></li>
                    <li>opcionális adatok: megjelenített név, profilkép, beállítások</li>
                    <li>biztonsági adatok: belépési események, gyanús aktivitás technikai metaadatai</li>
                    </ul>
                <?php endif; ?>
                <?php if ($storesNotes): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]">3.2. Jegyzetek és tartalmak</h3>
                    <ul class="space-y-2 list-disc pl-5">
                    <li>a létrehozott jegyzetek tartalma (szöveg, címkék, mappák, csatolmányok - ha van)</li>
                    <li>a tartalmakhoz kapcsolódó metaadatok (létrehozás/módosítás ideje, tulajdonos felhasználó azonosítója)</li>
                    </ul>
                    <p class="small mt-4 mb-0 text-sm text-[var(--muted)]">
                    Fontos: a jegyzetek tartalma személyes adatot is tartalmazhat attól függően, mit írsz bele. Kérjük, csak saját felelősségedre tölts fel érzékeny adatot.
                    </p>
                <?php endif; ?>
                <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]">3.3. Technikai adatok (automatikusan keletkeznek)</h3>
                <ul class="space-y-2 list-disc pl-5">
                    <li>IP-cím, dátum/idő, kért URL, HTTP státuszkód</li>
                    <li>User-Agent (böngésző és eszköz típus), nyelvi beállítás</li>
                    <li>munkamenet-azonosító<?php echo $usesCookies ? ' (cookie-ben tárolva)' : ''; ?>, biztonsági események</li>
                </ul>
                <?php if ($hasContactForm): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]">3.4. Kapcsolatfelvétel / hibabejelentés</h3>
                    <ul class="space-y-2 list-disc pl-5">
                    <li>név (ha megadod), e-mail cím, üzenet tartalma</li>
                    <li>csatolmányok (pl. képernyőkép) - csak ha te feltöltöd</li>
                    </ul>
                <?php endif; ?>
                <?php if ($usesAnalytics): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]">3.5. Látogatottsági / analitikai adatok</h3>
                    <p class="m-0">
                    A weboldal analitikai eszközt használhat a forgalom megértéséhez (pl. látogatások száma, oldalak, eszköztípus).
                    Ennek pontos szolgáltatóját és beállításait a „Szolgáltatók” részben kell feltüntetned.
                    </p>
                <?php endif; ?>
                <?php if ($hasNewsletter): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]">3.6. Hírlevél</h3>
                    <p class="m-0">Hírlevél feliratkozás esetén az e-mail címedet és a feliratkozás tényét/időpontját kezeljük.</p>
                <?php endif; ?>
                <?php if ($usesPayments): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]">3.7. Fizetés / előfizetés</h3>
                    <p class="m-0">
                    Fizetés esetén a fizetési szolgáltató kezelheti a tranzakciós adatokat. A kártyaadatokat jellemzően nem mi, hanem a fizetési szolgáltató kezeli.
                    Ezt a részt mindenképp igazítsd a konkrét szolgáltatóhoz (Stripe/Barion/PayPal stb.).
                    </p>
                <?php endif; ?>
                <?php if ($usesThirdPartyLogin): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]">3.8. Külső belépés (OAuth)</h3>
                    <p class="m-0">
                    Ha Google/Apple/GitHub belépést használsz, a külső szolgáltató által átadott azonosító adatokat (pl. e-mail, név, azonosító) kezelhetjük a fiók létrehozásához.
                    </p>
                <?php endif; ?>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">4. Adataid tárolásának helye és biztonsága</h2>
                <ul class="mt-4 space-y-2 list-disc pl-5">
                    <li>A szolgáltatás HTTPS kapcsolaton keresztül kommunikál.</li>
                    <li>Hozzáférés-vezérlést, naplózást és alapvető biztonsági intézkedéseket alkalmazunk a jogosulatlan hozzáférés ellen.</li>
                    <li>Jelszavakat nem olvasható formában (hash) tárolunk, és törekszünk az adatminimalizálásra.</li>
                </ul>
                <p class="small mt-4 mb-0 text-sm text-[var(--muted)]">
                    A pontos tárhely és infrastruktúra szolgáltatóidat (adatfeldolgozókat) a 7. pontban sorold fel.
                </p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">5. Az adatkezelés céljai és jogalapjai</h2>
                <ol class="mt-4 space-y-3 list-decimal pl-5">
                    <li>
                        <strong>A szolgáltatás nyújtása és a fiók működtetése</strong>
                        - cél: beléptetés, jogosultságkezelés, alapfunkciók biztosítása.
                        <strong>Jogalap:</strong> szerződés teljesítése (GDPR 6. cikk (1) b)).
                    </li>
                    <?php if ($storesNotes): ?>
                    <li>
                        <strong>Jegyzetek tárolása és szinkronizálása</strong>
                        - cél: a felhasználó által létrehozott tartalom elérhetővé tétele.
                        <strong>Jogalap:</strong> szerződés teljesítése (GDPR 6. cikk (1) b)).
                    </li>
                    <?php endif; ?>
                    <li>
                        <strong>Biztonság és visszaélések megelőzése, technikai naplók</strong>
                        - cél: hibakeresés, incidenskezelés, támadások kiszűrése.
                        <strong>Jogalap:</strong> jogos érdek (GDPR 6. cikk (1) f)).
                    </li>
                    <?php if ($hasContactForm): ?>
                    <li>
                        <strong>Kapcsolatfelvétel / hibajegyek kezelése</strong>
                        - cél: ügyfélszolgálat és problémamegoldás.
                        <strong>Jogalap:</strong> hozzájárulás (GDPR 6. cikk (1) a)) vagy jogos érdek (GDPR 6. cikk (1) f)) a megkeresés jellegétől függően.
                    </li>
                    <?php endif; ?>
                    <?php if ($hasNewsletter): ?>
                    <li>
                        <strong>Hírlevél küldése</strong>
                        - cél: tájékoztatás újdonságokról.
                        <strong>Jogalap:</strong> hozzájárulás (GDPR 6. cikk (1) a)). Bármikor leiratkozhatsz.
                    </li>
                    <?php endif; ?>
                    <?php if ($usesAnalytics): ?>
                    <li>
                        <strong>Látogatottság mérése</strong>
                        - cél: a weboldal fejlesztése és a felhasználói élmény javítása.
                        <strong>Jogalap:</strong> hozzájárulás (GDPR 6. cikk (1) a)) - cookie banner esetén, vagy jogos érdek (GDPR 6. cikk (1) f)) - eszköztől és beállítástól függően.
                    </li>
                    <?php endif; ?>
                </ol>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">6. Adatmegőrzési időtartam</h2>
                <ul class="mt-4 space-y-2 list-disc pl-5">
                    <?php if ($hasRegistration): ?>
                    <li><strong>Fiókadatok:</strong> a fiók fennállásáig; törlés kérésére ésszerű időn belül töröljük/anonymizáljuk, kivéve ha jogi kötelezettség mást ír elő.</li>
                    <?php endif; ?>
                    <?php if ($storesNotes): ?>
                    <li><strong>Jegyzetek tartalma:</strong> a felhasználó fiókjában a törlésig, illetve fióktörlésig.</li>
                    <?php endif; ?>
                    <li><strong>Technikai naplók:</strong> jellemzően legfeljebb 30-90 nap (biztonsági és hibakeresési célból), kivéve incidens vizsgálata esetén, amikor hosszabb megőrzés indokolt lehet.</li>
                    <?php if ($hasContactForm): ?>
                    <li><strong>Kapcsolatfelvételi üzenetek / hibajegyek:</strong> a megkeresés lezárásáig, majd ésszerű időn belül törlésre kerülnek, kivéve ha a további megőrzés jogi igények miatt szükséges.</li>
                    <?php endif; ?>

                    <?php if ($hasNewsletter): ?>
                    <li><strong>Hírlevél feliratkozás:</strong> leiratkozásig.</li>
                    <?php endif; ?>
                </ul>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">7. Adattovábbítás harmadik feleknek és nemzetközi adattovábbítás</h2>
                <p class="mt-4 mb-0">
                    Személyes adatot nem adunk el. Adatot kizárólag a szolgáltatás működtetéséhez szükséges szolgáltatóknak továbbíthatunk (adatfeldolgozók),
                    illetve jogszabályi kötelezettség esetén hatóságoknak.
                </p>
                <?php if (!empty($processors)): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]">7.1. Adatfeldolgozók (példák)</h3>
                    <ul class="space-y-2 list-disc pl-5">
                    <?php foreach ($processors as $p): ?>
                        <li>
                        <strong><?php echo h($p['name'] ?? ''); ?></strong> - <?php echo h($p['purpose'] ?? ''); ?>
                        <?php if (!empty($p['location'])): ?> (adatkezelés helye: <?php echo h($p['location']); ?>)<?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="small mt-4 mb-0 text-sm text-[var(--muted)]">
                    <em>Ide írd be a valós szolgáltatóidat (tárhely, e-mail küldés, CDN, analitika, stb.).</em>
                    </p>
                <?php endif; ?>
                <p class="small mt-4 mb-0 text-sm text-[var(--muted)]">
                    Ha a szolgáltató EU-n kívülre továbbít adatot (pl. USA), akkor megfelelő garanciákat alkalmazhat (pl. EU-s megfelelőségi határozat, SCC).
                </p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">8. Az érintett jogai és ezek gyakorlása</h2>
                <p class="mt-4 mb-0">Az érintett az alábbi jogokkal rendelkezik:</p>
                <ul class="mt-4 space-y-2 list-disc pl-5">
                    <li><strong>Hozzáférés joga</strong> a kezelt személyes adatokhoz</li>
                    <li><strong>Helyesbítés joga</strong> (pontatlan adatok javítása)</li>
                    <li><strong>Törlés joga</strong> ("elfeledtetés joga"), ha nincs jogalap a további kezelésre</li>
                    <li><strong>Adatkezelés korlátozásának joga</strong></li>
                    <li><strong>Adathordozhatóság joga</strong> (ha alkalmazható)</li>
                    <li><strong>Tiltakozás joga</strong> jogos érdek esetén</li>
                    <li><strong>Hozzájárulás visszavonása</strong> (ha a jogalap hozzájárulás)</li>
                </ul>
                <p class="mt-4 mb-0">
                    A jogok gyakorlásához írj a <a href="mailto:<?php echo h($contactEmail); ?>"><?php echo h($contactEmail); ?></a> címre.
                    A kérelem beérkezését követően ésszerű időn belül, de legkésőbb 1 hónapon belül válaszolunk; összetett ügyben ez 2 hónappal hosszabbítható.
                </p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">9. Panasz és jogorvoslat</h2>
                <p class="mt-4 mb-0">Amennyiben úgy ítéled meg, hogy a jogaid sérültek, panasszal fordulhatsz a felügyeleti hatósághoz:</p>
                <p class="mt-4 mb-0">
                    <strong>Nemzeti Adatvédelmi és Információszabadság Hatóság (NAIH)</strong><br>
                    1055 Budapest, Falk Miksa utca 9-11<br>
                    E-mail: ugyfelszolgalat@naih.hu<br>
                    Levelezési cím: 1363 Budapest, Pf.: 9.<br>
                    Honlap: <a href="https://www.naih.hu" rel="noopener">www.naih.hu</a>
                </p>
                <p class="mt-4 mb-0">Az érintett bírósági úton is érvényesítheti igényét.</p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">10. Automatizált döntéshozatal és profilalkotás</h2>
                <p class="mt-4 mb-0">
                    A <?php echo h($siteName); ?> nem alkalmaz automatizált döntéshozatalt vagy profilalkotást olyan módon, amely joghatással járna rád nézve.
                </p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0">11. Egyéb rendelkezések</h2>
                <ul class="mt-4 space-y-2 list-disc pl-5">
                    <li><strong>A tájékoztató frissítése:</strong> szükség szerint frissítjük; lényeges változás esetén a weboldalon közzétesszük.</li>
                    <li><strong>Kapcsolat:</strong> adatvédelemmel kapcsolatos megkeresés: <a href="mailto:<?php echo h($contactEmail); ?>"><?php echo h($contactEmail); ?></a></li>
                </ul>
            </section>
        </main>
    </div>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>