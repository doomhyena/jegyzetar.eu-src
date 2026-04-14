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
            'title' => t('exam_sw_title'),
            'subtitle' => t('exam_sw_subtitle'),
            'sections' => [
                [
                    'h' => t('exam_sw_section1_heading'),
                    'list' => [
                        t('exam_sw_section1_item1'),
                        t('exam_sw_section1_item2'),
                        t('exam_sw_section1_item3'),
                        t('exam_sw_section1_item4'),
                        t('exam_sw_section1_item5'),
                        t('exam_sw_section1_item6'),
                        t('exam_sw_section1_item7')
                    ]
                ],
                [
                    'h' => t('exam_sw_section2_heading'),
                    'list' => [
                        t('exam_sw_section2_item1'),
                        t('exam_sw_section2_item2'),
                        t('exam_sw_section2_item3'),
                        t('exam_sw_section2_item4'),
                        t('exam_sw_section2_item5')
                    ]
                ],
                [
                    'h' => t('exam_sw_section3_heading'),
                    'list' => [
                        t('exam_sw_section3_item1'),
                        t('exam_sw_section3_item2'),
                        t('exam_sw_section3_item3'),
                        t('exam_sw_section3_item4'),
                        t('exam_sw_section3_item5')
                    ]
                ],
                [
                    'h' => t('exam_sw_section4_heading'),
                    'list' => [
                        t('exam_sw_section4_item1'),
                        t('exam_sw_section4_item2'),
                        t('exam_sw_section4_item3'),
                        t('exam_sw_section4_item4'),
                        t('exam_sw_section4_item5')
                    ]
                ],
                [
                    'h' => t('exam_sw_section5_heading'),
                    'list' => [
                        t('exam_sw_section5_item1'),
                        t('exam_sw_section5_item2'),
                        t('exam_sw_section5_item3'),
                        t('exam_sw_section5_item4'),
                        t('exam_sw_section5_item5'),
                        t('exam_sw_section5_item6')
                    ]
                ],
                [
                    'h' => t('exam_sw_section6_heading'),
                    'p' => t('exam_sw_section6_paragraph')
                ],
                [
                    'h' => t('exam_sw_section7_heading'),
                    'list' => [
                        t('exam_sw_section7_item1'),
                        t('exam_sw_section7_item2'),
                        t('exam_sw_section7_item3')
                    ]
                ],
                [
                    'h' => t('exam_sw_section8_heading'),
                    'list' => [
                        t('exam_sw_section8_item1'),
                        t('exam_sw_section8_item2'),
                        t('exam_sw_section8_item3'),
                        t('exam_sw_section8_item4'),
                        t('exam_sw_section8_item5')
                    ]
                ],
                [
                    'h' => t('exam_sw_section9_heading'),
                        'list' => [
                        t('exam_sw_section9_item1'),
                        t('exam_sw_section9_item2'),
                        t('exam_sw_section9_item3'),
                        t('exam_sw_section9_item4'),
                        t('exam_sw_section9_item5'),
                        t('exam_sw_section9_item6')
                    ]
                ],
            ],
        ],
        'sys' => [
            'title' => t('exam_sys_title'),
            'subtitle' => t('exam_sys_subtitle'),
            'sections' => [
                [
                    'h' => t('exam_sys_section1_heading'),
                    'p' => t('exam_sys_section1_paragraph')
                ],
                [
                    'h' => t('exam_sys_section2_heading'),
                    'list' => [
                        t('exam_sys_section2_item1'),
                        t('exam_sys_section2_item2'),
                        t('exam_sys_section2_item3'),
                        t('exam_sys_section2_item4'),
                        t('exam_sys_section2_item5'),
                        t('exam_sys_section2_item6'),
                        t('exam_sys_section2_item7')
                    ]
                ],
                [
                    'h' => t('exam_sys_section3_heading'),
                    'list' => [
                        t('exam_sys_section3_item1'),
                        t('exam_sys_section3_item2'),
                        t('exam_sys_section3_item3'),
                        t('exam_sys_section3_item4'),
                        t('exam_sys_section3_item5')
                    ]
                ],
                [
                    'h' => t('exam_sys_section4_heading'),
                    'list' => [
                        t('exam_sys_section4_item1'),
                        t('exam_sys_section4_item2'),
                        t('exam_sys_section4_item3'),
                        t('exam_sys_section4_item4'),
                        t('exam_sys_section4_item5')
                    ]
                ],
                [
                    'h' => t('exam_sys_section5_heading'),
                    'list' => [
                        t('exam_sys_section5_item1'),
                        t('exam_sys_section5_item2'),
                        t('exam_sys_section5_item3'),
                        t('exam_sys_section5_item4'),
                        t('exam_sys_section5_item5')
                    ]
                ],
                [
                    'h' => t('exam_sys_section6_heading'),
                    'list' => [
                        t('exam_sys_section6_item1'),
                        t('exam_sys_section6_item2'),
                        t('exam_sys_section6_item3'),
                        t('exam_sys_section6_item4'),
                        t('exam_sys_section6_item5'),
                        t('exam_sys_section6_item6')
                    ]
                ],
                [
                    'h' => t('exam_sys_section7_heading'),
                    'p' => t('exam_sys_section7_paragraph')
                ],
                [
                    'h' => t('exam_sys_section8_heading'),
                    'list' => [
                        t('exam_sys_section8_item1'),
                        t('exam_sys_section8_item2'),
                        t('exam_sys_section8_item3'),
                        t('exam_sys_section8_item4')
                    ]
                ],
                [
                    'h' => t('exam_sys_section9_heading'),
                    'list' => [
                        t('exam_sys_section9_item1'),
                        t('exam_sys_section9_item2'),
                        t('exam_sys_section9_item3'),
                        t('exam_sys_section9_item4')
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



