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
    <title>GYIK</title>
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
            <h2 class="text-2xl font-semibold mb-4">Gyakran ismételt kérdések</h2>
            <div class="space-y-3" id="faq-accordion">
                <div class="border border-neutral-200 rounded-xl overflow-hidden">
                <button type="button"
                    class="faq-btn w-full flex items-center justify-between gap-4 p-4 text-left"
                    aria-expanded="false">
                    <span class="font-medium">Mi az a Jegyzetár?</span>
                    <span class="faq-icon text-xl leading-none select-none">+</span>
                </button>
                <div class="faq-panel max-h-0 overflow-hidden transition-all duration-300">
                    <div class="p-4 pt-0">
                    A Jegyzetár egy online platform, ahol iskolai jegyzeteket lehet feltölteni, rendszerezni és megosztani,
                    hogy a tanulás gyorsabb és közösségibb legyen.
                    </div>
                </div>
                </div>
                <div class="border border-neutral-200 rounded-xl overflow-hidden">
                <button type="button"
                    class="faq-btn w-full flex items-center justify-between gap-4 p-4 text-left"
                    aria-expanded="false">
                    <span class="font-medium">Kell regisztrálnom a használathoz?</span>
                    <span class="faq-icon text-xl leading-none select-none">+</span>
                </button>
                <div class="faq-panel max-h-0 overflow-hidden transition-all duration-300">
                    <div class="p-4 pt-0">
                    A böngészés részben elérhető vendégként is, de a feltöltés, értékelés, kedvencek és tanulócsoportok
                    használatához bejelentkezés szükséges.
                    </div>
                </div>
                </div>
                <div class="border border-neutral-200 rounded-xl overflow-hidden">
                <button type="button"
                    class="faq-btn w-full flex items-center justify-between gap-4 p-4 text-left"
                    aria-expanded="false">
                    <span class="font-medium">Hogyan tudok jegyzetet feltölteni?</span>
                    <span class="faq-icon text-xl leading-none select-none">+</span>
                </button>
                <div class="faq-panel max-h-0 overflow-hidden transition-all duration-300">
                    <div class="p-4 pt-0">
                    Bejelentkezés után a „Jegyzet feltöltése” oldalon megadod a címét, leírását, címkéit (tagek),
                    és feltöltöd a fájlt.
                    </div>
                </div>
                </div>
                <div class="border border-neutral-200 rounded-xl overflow-hidden">
                <button type="button"
                    class="faq-btn w-full flex items-center justify-between gap-4 p-4 text-left"
                    aria-expanded="false">
                    <span class="font-medium">Mi az a tanulócsoport és mire jó?</span>
                    <span class="faq-icon text-xl leading-none select-none">+</span>
                </button>
                <div class="faq-panel max-h-0 overflow-hidden transition-all duration-300">
                    <div class="p-4 pt-0">
                    A tanulócsoportok egy adott tantárgy vagy téma köré szerveződnek, ahol a tagok megoszthatják a jegyzeteiket.
                    A csoporton belüli feltöltések jóváhagyással/moderációval kezelhetők.
                    </div>
                </div>
                </div>
                <div class="border border-neutral-200 rounded-xl overflow-hidden">
                <button type="button"
                    class="faq-btn w-full flex items-center justify-between gap-4 p-4 text-left"
                    aria-expanded="false">
                    <span class="font-medium">Biztonságos a Jegyzetár használata?</span>
                    <span class="faq-icon text-xl leading-none select-none">+</span>
                </button>
                <div class="faq-panel max-h-0 overflow-hidden transition-all duration-300">
                    <div class="p-4 pt-0">
                    Igen. A rendszer több biztonsági megoldást alkalmaz (pl. biztonságos adatkezelés és védelmek),
                    valamint lehetőség van külső bejelentkezésre (pl. Discord OAuth).
                    </div>
                </div>
                </div>
            </div>
        </section>
    </main>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>
