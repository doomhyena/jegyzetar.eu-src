<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title><?= t('faq_title') ?></title>
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
    <main class="main flex flex-col gap-6">
        <section class="card">
            <h2 class="text-2xl font-semibold mb-4"><?= t('faq_heading') ?></h2>
            <div class="space-y-3" id="faq-accordion">
                <?php
                $faqs = [
                    ['q' => 'faq_q1', 'a' => 'faq_a1'],
                    ['q' => 'faq_q2', 'a' => 'faq_a2'],
                    ['q' => 'faq_q3', 'a' => 'faq_a3'],
                    ['q' => 'faq_q4', 'a' => 'faq_a4'],
                    ['q' => 'faq_q5', 'a' => 'faq_a5'],
                ];
                foreach ($faqs as $faq):
                ?>
                <div class="border border-neutral-200 rounded-xl overflow-hidden">
                    <button type="button"
                        class="faq-btn w-full flex items-center justify-between gap-4 p-4 text-left"
                        aria-expanded="false">
                        <span class="font-medium"><?= t($faq['q']) ?></span>
                        <span class="faq-icon text-xl leading-none select-none">+</span>
                    </button>
                    <div class="faq-panel max-h-0 overflow-hidden transition-all duration-300">
                        <div class="p-4 pt-0"><?= t($faq['a']) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>
