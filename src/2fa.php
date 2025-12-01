<!DOCTYPE html>
<html lang="hu">

<head>
    <title>Kétlépcsős azonosítás</title>
    <meta name='description' content='Iskolai jegyzeteket megosztó oldal'>
    <meta name='keywords' content='iskola, jegyzet, megosztás, tanulás'>
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <?php
    //norbi: 2fa
    require "assets/php/db.php";
    require_once "assets/php/lang.php";
    session_start();

    if (!isset($_SESSION['tries'])) {
        $_SESSION['tries'] = 0;
    }

    if (!isset($_SESSION['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $userid = $_SESSION['id'];
    $email = $_SESSION['email'];

    include 'assets/php/navbar.php';

    if (isset($_POST['code'])) {

        $lekerdezes = "SELECT * FROM 2fa_codes WHERE userid='$userid' AND code='$_POST[code]'";
        $talalt_sorok = $conn->query($lekerdezes);
        while ($sor = $talalt_sorok->fetch_assoc()) {
            if ($sor['code'] == $_POST['code']) {
                $conn->query("DELETE FROM 2fa_codes WHERE userid='$userid' AND code='$_POST[code]'");
                setcookie("id", $userid, time() + 3600, "/");
                session_destroy();
                header("Location: index.php");
            }
        }

        echo "<script>alert('Helytelen kód')</script>";
        //opcionálisan kidobhatjuk a felhasználót 3 rossz proba után
        $_SESSION['tries']++;
        if ($_SESSION['tries'] >= 3) {
            session_destroy();
            header("Location: reglog.php");
            exit;
        }
    }
    ?>

    <div class="main">
        <div class="auth-wrap">
            <div class="auth-head">
                <h1>Kétlépcsős azonosítás</h1>
                <p class="auth-note">Kérlek írd be az e-mailben kapott kódot!</p>
            </div>
            <div class="auth-grid">
                <form class="auth-card" method="post">
                    <input class="input" type="text" name="code" id="code" placeholder="Kód" required>
                    <div class="auth-actions" style="margin-top:12px;">
                        <button class="btn-cta" type="submit">Ellenőrzés</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <?php include 'assets/php/footer.php'; ?>
</body>

</html>