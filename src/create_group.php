<?php
require "assets/php/db.php";
require "assets/php/lang.php";

if (!isset($_COOKIE['id'])) {
    header("Location: reglog.php");
    exit;
}

$bejelentkezett_felhasznalo_id = $_COOKIE['id'];

if (isset($_POST['letrehozas'])) {

    $csoport_nev    = $_POST['name'];
    $csoport_leiras = $_POST['description'];

    if (isset($_POST['is_private'])) {
        $privat = 1;
    } else {
        $privat = 0;
    }

    $tulaj_id = $bejelentkezett_felhasznalo_id;

    if ($csoport_nev == "") {
        echo "<script>alert('A csoport neve kötelező!');</script>";
    } else {

        $uj_csoport_sql = "
            INSERT INTO groups (name, description, owner_id, is_private)
            VALUES ('$csoport_nev', '$csoport_leiras', '$tulaj_id', '$privat')
        ";

        if ($conn->query($uj_csoport_sql)) {

            $uj_csoport_id = $conn->insert_id;

            $uj_tulaj_sql = "
                INSERT INTO group_members (group_id, user_id, role, status)
                VALUES ('$uj_csoport_id', '$tulaj_id', 'owner', 'accepted')
            ";
            $conn->query($uj_tulaj_sql);

            echo "<script>alert('Csoport sikeresen létrehozva!');</script>";
            header("Location: group.php?id=".$uj_csoport_id);
            exit;

        } else {
            echo "<script>alert('Hiba történt a csoport létrehozásakor.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title>Új csoport létrehozása – Jegyzetár</title>
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
    <div class="section-titlebar">
        <div>
            <h1>Új tanuló csoport létrehozása</h1>
            <p class="entry-meta">
                Hozz létre egy csoportot, ahol a tagok megoszthatják egymással a jegyzeteiket.
            </p>
        </div>
        <div class="hero-actions">
            <a href="groups.php" class="btn-ghost">
                Vissza a csoportokhoz
            </a>
        </div>
    </div>

    <div class="auth-grid" style="margin-top:18px; max-width:600px;">
        <section class="auth-card compact">
            <h1>Csoport adatai</h1>

            <form method="post" class="form-grid" style="grid-template-columns:1fr;">
                <div class="form-field">
                    <label for="group-name">Csoport neve</label>
                    <input
                        type="text"
                        id="group-name"
                        name="name"
                        class="input"
                        placeholder="Pl. C# dolgozat felkészítő"
                    >
                </div>

                <div class="form-field">
                    <label for="group-description">Leírás</label>
                    <textarea
                        id="group-description"
                        name="description"
                        rows="4"
                        class="input"
                        placeholder="Röviden írd le, mire való a csoport, kiknek szól, mit osztotok meg itt."
                    ></textarea>
                </div>

                <div class="form-field" style="margin-top:4px;">
                    <label class="entry-meta" style="font-weight:600;">
                        <input type="checkbox" name="is_private" style="margin-right:6px;">
                        Privát csoport (csak meghívással / jóváhagyással)
                    </label>
                    <p class="auth-note">
                        Privát csoport esetén a csatlakozási kérelmeket jóvá kell hagynod, 
                        és csak a tagok látják a tartalmat.
                    </p>
                </div>

                <div class="auth-actions" style="margin-top:10px;">
                    <button type="submit" name="letrehozas" class="btn-cta">
                        Csoport létrehozása
                    </button>
                    <a href="groups.php" class="btn-ghost">
                        Mégse, vissza a listához
                    </a>
                </div>
            </form>
        </section>
    </div>
</div>

<?php include 'assets/php/footer.php'; ?>
</body>
</html>