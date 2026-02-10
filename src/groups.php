<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require "assets/php/db.php";
    require "assets/php/lang.php";

    if (!isset($_COOKIE['id']) || !ctype_digit($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }
	
	
    $aktualis_felhasznalo_id = (int)$_COOKIE['id'];
    $csoportok_sql = "SELECT * FROM groups ORDER BY id DESC";
    $csoportok_lekerdezes = $conn->query($csoportok_sql);
	
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title><?= t('index_title') ?></title>
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
<div class="content-wrapper w-full">
    <?php include "assets/php/ads.php"; ?>

    <div class="main w-full max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6">
        <div class="section-titlebar flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div class="min-w-0">
                <h1 class="text-2xl md:text-3xl lg:text-4xl mb-2">Tanuló csoportok</h1>
                <p class="entry-meta text-sm md:text-base">
                    Itt találod az összes elérhető tanuló csoportot.
                </p>
            </div>
            <div class="hero-actions flex-shrink-0">
                <a href="create_group.php" class="btn-cta text-sm md:text-base whitespace-nowrap">
                    Új csoport létrehozása
                </a>
            </div>
        </div>
        <?php if ($csoportok_lekerdezes && $csoportok_lekerdezes->num_rows > 0): ?>
            <div class="content-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <?php while ($egy_csoport = $csoportok_lekerdezes->fetch_assoc()):
						$csoport_statusz = $egy_csoport['status'];
                        $csoport_id = $egy_csoport['id'];
                        $csoport_nev = $egy_csoport['name'];
                        $csoport_leiras = $egy_csoport['description'];
                        $csoport_privat = $egy_csoport['is_private'];

                        if ($csoport_privat == 1) {
                            $privat_szoveg = "Privát csoport";
                        } else {
                            $privat_szoveg = "Nyilvános csoport";
                        }
                    ?>
                    <article class="card p-4 md:p-6 flex flex-col break-words">
                        <div class="card-head mb-3">
                            <h3 class="entry-title text-lg md:text-xl lg:text-2xl mb-2">
                                <a href="group.php?id=<?= (int)$csoport_id ?>" class="link-more hover:underline">
                                    <?= htmlspecialchars($csoport_nev) ?>
                                </a>
                            </h3>
                            <span class="entry-meta text-xs md:text-sm inline-block px-2 py-1 rounded bg-white/10">
                                <?= htmlspecialchars($privat_szoveg) ?>
                            </span>
							<?php if ($csoport_statusz === 'pending'): ?>
							<span class="entry-meta text-xs md:text-sm inline-block px-2 py-1 rounded bg-yellow-500/20 text-yellow-300">
							Jóváhagyásra vár
							</span>
							<?php endif; ?>
                        </div>
                        <?php if (trim($csoport_leiras) !== ""): ?>
                            <p class="entry-meta text-sm md:text-base mb-3 break-words">
                                <?= nl2br(htmlspecialchars($csoport_leiras)) ?>
                            </p>
                        <?php else: ?>
                            <p class="entry-meta text-sm md:text-base mb-3 opacity-75">
                                Ehhez a csoporthoz még nincs leírás megadva.
                            </p>
                        <?php endif; ?>
                        <div class="mt-auto">
						<?php if ($csoport_statusz === 'approved'): ?>
							<a href="group.php?id=<?= (int)$csoport_id ?>" class="btn-ghost text-sm md:text-base">
								Csoport megnyitása
							</a>
						<?php else: ?>
							<span class="btn-ghost text-sm md:text-base opacity-50 cursor-not-allowed">
								A csoport még nem elérhető
							</span>
						<?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="card p-6">
                <p class="entry-meta text-sm md:text-base">Nincs megjeleníthető csoport.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
