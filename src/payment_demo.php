<?php
    require "assets/php/db.php";
    require "assets/php/lang.php";
    require "assets/php/functions.php";
    require "assets/php/premium.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }
    $uid = (int)$_COOKIE['id'];

    $hibak = [];

    if (isset($_POST['pay'])) {
        $kartya = preg_replace('/\D+/', '', $_POST['card'] ?? '');
        $cvv = $_POST['cvv'] ?? '';

        if ($kartya !== "4242424242424242") {
            $hibak[] = "Csak DEMO kártyaszám engedélyezett: 4242 4242 4242 4242";
        }
        if (!preg_match('/^\d{3}$/', $cvv)) {
            $hibak[] = "CVV hibás (DEMO)";
        }

        if (!$hibak) {
            premium_aktivalas_30nap($conn, $uid);
            header("Location: premium.php?paid=1");
            exit;
        }
    }
?>
<!doctype html>
<html lang="hu">
<head><meta charset="utf-8"><title>DEMO Bank</title></head>
<link rel="stylesheet" href="assets/css/styles.css">
<body>
    <?php include 'assets/php/navbar.php'; ?>
    <div style="border:2px solid red;padding:10px;">
        <b>DEMO BANK</b><br>
        Ez NEM valódi fizetés. Ne adj meg valódi bankkártya adatot.
    </div>

    <?php foreach ($hibak as $h): ?>
    <p style="color:red"><?= htmlspecialchars($h) ?></p>
    <?php endforeach; ?>

    <form method="post">
        <input name="card" placeholder="4242 4242 4242 4242"><br><br>
        <input name="cvv" placeholder="123"><br><br>
        <button name="pay">Fizetés (DEMO)</button>
        <a href="premium.php">Mégse</a>
    </form>
    <?php include "assets/php/footer.php"; ?>
</body>
</html>