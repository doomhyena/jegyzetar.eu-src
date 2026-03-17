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

    <title><?= t('privacy_title') ?></title>
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
    <div class="max-w-4xl mx-auto w-full px-2 sm:px-4">
        <main class="main break-words">
            <section class="card hover:translate-y-0">
                <div class="flex flex-col gap-2">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight m-0"><?= t('privacy_h1') ?></h1>
                    <p class="meta m-0 text-sm sm:text-base">
                        <strong><?php echo h($siteName); ?></strong> – <?= t('privacy_effective') ?> <strong><?php echo h($dtHuman); ?></strong>
                    </p>
                    <p class="small m-0 text-sm sm:text-base text-[var(--muted)]">
                        <?= sprintf(t('privacy_intro'), h($siteName)) ?>
                    </p>
                </div>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s0_title') ?></h2>
                <ul class="mt-4 space-y-2 list-disc pl-5">
                    <li><?= sprintf(t('privacy_s0_li1'), h($siteName), $storesNotes ? t('privacy_s0_li1_notes') : '') ?></li>
                    <li><?= t('privacy_s0_li2') ?></li>
                    <li><?= $usesCookies ? t('privacy_s0_li3_cookies') : t('privacy_s0_li3_nocookies') ?></li>
                    <li><?= t('privacy_s0_li4') ?></li>
                    <li><?= t('privacy_s0_li5') ?> <a class="break-all" href="mailto:<?php echo h($contactEmail); ?>"><?php echo h($contactEmail); ?></a></li>
                </ul>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s1_title') ?></h2>
                <p class="mt-4 mb-0 text-[var(--text)]"><?= sprintf(t('privacy_s1_p'), h($siteName)) ?></p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s2_title') ?></h2>
                <div class="mt-4 space-y-2">
                    <p class="m-0"><strong><?= t('privacy_s2_controller') ?></strong> <?php echo h($dataController); ?><?php echo $controllerForm ? ' ('.h($controllerForm).')' : ''; ?></p>
                    <p class="m-0"><strong><?= t('privacy_s2_contact') ?></strong> <a class="break-all" href="mailto:<?php echo h($contactEmail); ?>"><?php echo h($contactEmail); ?></a></p>
                </div>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s3_title') ?></h2>
                <?php if ($hasRegistration): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]"><?= t('privacy_s3_1_title') ?></h3>
                    <ul class="space-y-2 list-disc pl-5">
                        <li><?= t('privacy_s3_1_li1') ?></li>
                        <li><?= t('privacy_s3_1_li2') ?></li>
                        <li><?= t('privacy_s3_1_li3') ?></li>
                    </ul>
                <?php endif; ?>
                <?php if ($storesNotes): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]"><?= t('privacy_s3_2_title') ?></h3>
                    <ul class="space-y-2 list-disc pl-5">
                        <li><?= t('privacy_s3_2_li1') ?></li>
                        <li><?= t('privacy_s3_2_li2') ?></li>
                    </ul>
                    <p class="small mt-4 mb-0 text-sm text-[var(--muted)]"><?= t('privacy_s3_2_note') ?></p>
                <?php endif; ?>
                <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]"><?= t('privacy_s3_3_title') ?></h3>
                <ul class="space-y-2 list-disc pl-5">
                    <li><?= t('privacy_s3_3_li1') ?></li>
                    <li><?= t('privacy_s3_3_li2') ?></li>
                    <li><?= $usesCookies ? t('privacy_s3_3_li3_cookie') : t('privacy_s3_3_li3_nocookie') ?></li>
                </ul>
                <?php if ($hasContactForm): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]"><?= t('privacy_s3_4_title') ?></h3>
                    <ul class="space-y-2 list-disc pl-5">
                        <li><?= t('privacy_s3_4_li1') ?></li>
                        <li><?= t('privacy_s3_4_li2') ?></li>
                    </ul>
                <?php endif; ?>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s4_title') ?></h2>
                <ul class="mt-4 space-y-2 list-disc pl-5">
                    <li><?= t('privacy_s4_li1') ?></li>
                    <li><?= t('privacy_s4_li2') ?></li>
                    <li><?= t('privacy_s4_li3') ?></li>
                </ul>
                <p class="small mt-4 mb-0 text-sm text-[var(--muted)]"><?= t('privacy_s4_note') ?></p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s5_title') ?></h2>
                <ol class="mt-4 space-y-3 list-decimal pl-5">
                    <li><?= t('privacy_s5_li1') ?></li>
                    <?php if ($storesNotes): ?>
                        <li><?= t('privacy_s5_li2') ?></li>
                    <?php endif; ?>
                    <li><?= t('privacy_s5_li3') ?></li>
                    <?php if ($hasContactForm): ?>
                        <li><?= t('privacy_s5_li4') ?></li>
                    <?php endif; ?>
                </ol>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s6_title') ?></h2>
                <ul class="mt-4 space-y-2 list-disc pl-5">
                    <?php if ($hasRegistration): ?>
                        <li><?= t('privacy_s6_li_account') ?></li>
                    <?php endif; ?>
                    <?php if ($storesNotes): ?>
                        <li><?= t('privacy_s6_li_notes') ?></li>
                    <?php endif; ?>
                    <li><?= t('privacy_s6_li_logs') ?></li>
                    <?php if ($hasContactForm): ?>
                        <li><?= t('privacy_s6_li_contact') ?></li>
                    <?php endif; ?>
                </ul>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s7_title') ?></h2>
                <p class="mt-4 mb-0"><?= t('privacy_s7_p') ?></p>
                <?php if (!empty($processors)): ?>
                    <h3 class="mt-6 mb-3 text-base sm:text-lg font-extrabold text-[var(--primary)]"><?= t('privacy_s7_1_title') ?></h3>
                    <ul class="space-y-2 list-disc pl-5">
                        <?php foreach ($processors as $proc): ?>
                            <li>
                                <strong><?php echo h($proc['name'] ?? ''); ?></strong> – <?php echo h($proc['purpose'] ?? ''); ?>
                                <?php if (!empty($proc['location'])): ?> (<?= t('privacy_s7_location') ?> <?php echo h($proc['location']); ?>)<?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <p class="small mt-4 mb-0 text-sm text-[var(--muted)]"><?= t('privacy_s7_note') ?></p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s8_title') ?></h2>
                <p class="mt-4 mb-0"><?= t('privacy_s8_intro') ?></p>
                <ul class="mt-4 space-y-2 list-disc pl-5">
                    <li><?= t('privacy_s8_li1') ?></li>
                    <li><?= t('privacy_s8_li2') ?></li>
                    <li><?= t('privacy_s8_li3') ?></li>
                    <li><?= t('privacy_s8_li4') ?></li>
                    <li><?= t('privacy_s8_li5') ?></li>
                    <li><?= t('privacy_s8_li6') ?></li>
                    <li><?= t('privacy_s8_li7') ?></li>
                </ul>
                <p class="mt-4 mb-0"><?= sprintf(t('privacy_s8_contact'), h($contactEmail), h($contactEmail)) ?></p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s9_title') ?></h2>
                <p class="mt-4 mb-0"><?= t('privacy_s9_intro') ?></p>
                <p class="mt-4 mb-0">
                    <strong>Nemzeti Adatvédelmi és Információszabadság Hatóság (NAIH)</strong><br>
                    1055 Budapest, Falk Miksa utca 9-11<br>
                    E-mail: ugyfelszolgalat@naih.hu<br>
                    Levelezési cím: 1363 Budapest, Pf.: 9.<br>
                    Honlap: <a href="https://www.naih.hu" rel="noopener">www.naih.hu</a>
                </p>
                <p class="mt-4 mb-0"><?= t('privacy_s9_court') ?></p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s10_title') ?></h2>
                <p class="mt-4 mb-0"><?= sprintf(t('privacy_s10_p'), h($siteName)) ?></p>
            </section>
            <section class="card hover:translate-y-0">
                <h2 class="text-xl sm:text-2xl font-extrabold m-0"><?= t('privacy_s11_title') ?></h2>
                <ul class="mt-4 space-y-2 list-disc pl-5">
                    <li><?= t('privacy_s11_li1') ?></li>
                    <li><?= t('privacy_s11_li2') ?> <a href="mailto:<?php echo h($contactEmail); ?>"><?php echo h($contactEmail); ?></a></li>
                </ul>
            </section>
        </main>
    </div>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>