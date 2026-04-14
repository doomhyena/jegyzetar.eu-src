<?php
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");

require_once "assets/php/db.php";
require_once "assets/php/lang.php";
require_once 'assets/php/functions.php';

require_login();
if ($_GET['profanity'] ?? '' === '1') {
    echo "<script>alert('" . addslashes(t('create_group_profanity_alert')) . "')</script>";
}

$bejelentkezett_felhasznalo_id = (int)auth_user_id();

if (isset($_POST['letrehozas'])) {

    $csoport_nev    = trim($_POST['name'] ?? '');
    $csoport_leiras = trim($_POST['description'] ?? '');

    // norbi: profanity filter (egyelőre csak a névre és leírásra élesitem be)
    $lekerdezes = "SELECT * FROM profanity_filter";
    $talalt_sorok = $conn->query($lekerdezes);
    while ($sor = $talalt_sorok->fetch_assoc()) {
        $badword = $sor['words'];
        if (stripos((string)$_POST['name'], $badword) !== false || stripos((string)$_POST['description'], $badword) !== false) {
            header("Location: create_group.php?profanity=1");
            exit;
        }
    }

    $privat = isset($_POST['is_private']) ? 1 : 0;
    $tulaj_id = $bejelentkezett_felhasznalo_id;

    if ($csoport_nev === '') {
        echo "<script>alert('" . addslashes(t('create_group_name_required')) . "');</script>";
    } else {

        $inserted = db_exec($conn,"INSERT INTO groups (name, description, owner_id, is_private, status) VALUES (?, ?, ?, ?, 'pending')","ssii",[$csoport_nev, $csoport_leiras, $tulaj_id, $privat]);
        if ($inserted > 0) {
            $uj_csoport_id = $conn->insert_id;
            db_exec($conn, "INSERT INTO group_members (group_id, user_id, role, status)  VALUES (?, ?, 'owner', 'accepted')", "ii", [$uj_csoport_id, $tulaj_id]);
            echo "<script>alert('" . addslashes(t('create_group_success')) . "');</script>";
            header("Location: groups.php?pending=1");
            exit;
        } else {
            echo "<script>alert('" . addslashes(t('create_group_error')) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <title><?= t('create_group_title') ?></title>
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
        <div class="main w-full max-w-3xl mx-auto px-4 md:px-6 lg:px-8 py-6">
            <div class="section-titlebar mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl lg:text-4xl mb-2"><?= t('create_group_heading') ?></h1>
                    <p class="entry-meta text-sm md:text-base">
                        <?= t('create_group_description') ?>
                    </p>
                </div>
            </div>
            <div class="auth-grid w-full max-w-2xl mx-auto">
                <section class="auth-card p-6 md:p-8">
                    <h1 class="text-xl md:text-2xl mb-4"><?= t('create_group_section_title') ?></h1>
                    <form method="post" class="form-grid flex flex-col gap-4">
                        <div class="form-field">
                            <label for="group-name" class="text-sm md:text-base font-semibold"><?= t('create_group_field_name') ?></label>
                            <input type="text" id="group-name" name="name" class="input w-full text-sm md:text-base" placeholder="<?= t('create_group_placeholder_name') ?>">
                        </div>
                        <div class="form-field">
                            <label for="group-description" class="text-sm md:text-base font-semibold"><?= t('create_group_field_description') ?></label>
                            <textarea id="group-description" name="description" rows="4" class="input w-full text-sm md:text-base" placeholder="<?= t('create_group_placeholder_description') ?>"></textarea>
                        </div>
                        <div class="form-field mt-2">
                            <label class="checkbox-label text-sm md:text-base font-semibold">
                                <input type="checkbox" name="is_private" class="styled-checkbox">
                                <span><?= t('create_group_private_label') ?></span>
                            </label>
                            <p class="auth-note text-xs md:text-sm mt-2 ml-7">
                                <?= t('create_group_private_help') ?>
                            </p>
                        </div>

                        <div class="auth-actions flex flex-col md:flex-row gap-3 mt-3">
                            <button type="submit" name="letrehozas" class="btn-cta w-full md:w-auto text-sm md:text-base">
                                <?= t('create_group_submit') ?>
                            </button>
                            <a href="groups.php" class="btn-ghost w-full md:w-auto text-center text-sm md:text-base">
                                <?= t('create_group_cancel') ?>
                            </a>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
    <?php include 'assets/php/footer.php'; ?>
</body>

</html>