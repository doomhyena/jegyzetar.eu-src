<?php
    require "assets/php/db.php";
    require "assets/php/lang.php";
    require "assets/php/premium.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php?mode=login");
        exit;
    }

    $uid = (int)$_COOKIE['id'];
    $premium_van = user_premium($conn, $uid);
    $premium_ig  = premium_datum($conn, $uid);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<title>Prémium</title>
<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<?php include "assets/php/navbar.php"; ?>
<div class="main">
<div class="card">
<h1>Prémium</h1>
<?php if (isset($_GET['paid']) && $_GET['paid'] == '1'): ?>
    <p class="entry-meta">✅ Sikeres DEMO fizetés! Prémium aktiválva.</p>
<?php endif; ?>
<?php if ($premium_van): ?>
    <p class="entry-meta">✅ Prémium aktív eddig: <b><?= htmlspecialchars($premium_ig) ?></b></p>
    <button class="btn-ghost" disabled>Már prémium vagy</button>
<?php else: ?>
    <p class="entry-meta">❌ Jelenleg nincs prémiumod.</p>
    <ul>
        <li>Reklámmentesség</li>
        <li>Nagyobb feltöltési limit</li>
		<li>offline saját felület(hamarosan)</li>
        <li>Privát jegyzet</li>
        <li>Prémium badge(hamarosan)</li>
    </ul>
    <form action="payment_demo.php" method="get">
        <button class="btn-cta">Prémium aktiválása (DEMO fizetés)</button>
    </form>
<?php endif; ?>
</div>
</div>
<?php include "assets/php/footer.php"; ?>
</body>
</html>