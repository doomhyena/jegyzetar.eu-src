<?php
require "assets/php/db.php";
require "assets/php/lang.php";

if (!isset($_COOKIE['id'])) {
    header("Location: reglog.php");
    exit;
}

$aktualis_felhasznalo_id = $_COOKIE['id'];

// egyszerű lista, minden csoport
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>

<div class="main">
    <!-- Fejléc + új csoport gomb -->
    <div class="section-titlebar">
        <div>
            <h1>Tanuló csoportok</h1>
            <p class="entry-meta">
                Itt találod az összes elérhető tanuló csoportot.
            </p>
        </div>
        <div class="hero-actions">
            <a href="create_group.php" class="btn-cta">
                Új csoport létrehozása
            </a>
        </div>
    </div>

    <?php if ($csoportok_lekerdezes && $csoportok_lekerdezes->num_rows > 0): ?>
        <div class="content-grid grid-large" style="margin-top:16px;">
            <?php while ($egy_csoport = $csoportok_lekerdezes->fetch_assoc()):
                $csoport_id     = $egy_csoport['id'];
                $csoport_nev    = $egy_csoport['name'];
                $csoport_leiras = $egy_csoport['description'];
                $csoport_privat = $egy_csoport['is_private'];

                if ($csoport_privat == 1) {
                    $privat_szoveg = "Privát csoport";
                } else {
                    $privat_szoveg = "Nyilvános csoport";
                }
                ?>
                <article class="card">
                    <div class="card-head">
                        <h3 class="entry-title">
                            <a href="group.php?id=<?= (int)$csoport_id ?>" class="link-more" style="text-decoration:none;">
                                <?= htmlspecialchars($csoport_nev) ?>
                            </a>
                        </h3>
                        <span class="entry-meta">
                            <?= htmlspecialchars($privat_szoveg) ?>
                        </span>
                    </div>

                    <?php if (trim($csoport_leiras) !== ""): ?>
                        <p class="entry-meta" style="margin-top:8px;">
                            <?= nl2br(htmlspecialchars($csoport_leiras)) ?>
                        </p>
                    <?php else: ?>
                        <p class="entry-meta" style="margin-top:8px;">
                            Ehhez a csoporthoz még nincs leírás megadva.
                        </p>
                    <?php endif; ?>

                    <div style="margin-top:10px;">
                        <a href="group.php?id=<?= (int)$csoport_id ?>" class="btn-ghost">
                            Csoport megnyitása
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="card" style="margin-top:18px;">
            <p class="entry-meta">Nincs megjeleníthető csoport.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'assets/php/footer.php'; ?>
</body>
</html>