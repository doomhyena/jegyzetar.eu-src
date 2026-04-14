<?php
    require "assets/php/db.php";
    require "assets/php/lang.php";
    require "assets/php/functions.php";
    require "assets/php/premium.php";

    require_login();
    $uid = (int)auth_user_id();

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
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title>DEMO Fizetés - Prémium</title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-iconre" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
    <?php include 'assets/php/navbar.php'; ?>
    <div class="payment-wrapper">
        <div class="payment-card">
            <div class="demo-banner">
                <p class="demo-banner-text">
                    <span>DEMO FIZETÉS</span> - Ne adj meg valódi bankkártya adatot!
                </p>
            </div>
            <div class="payment-header">
                <h1 class="payment-title">
                    Prémium aktiválása
                </h1>
                <p class="payment-subtitle">30 napos prémium előfizetés - egyszeri DEMO fizetés</p>
                <div class="payment-amount-row">
                    <div>
                        <div class="payment-amount-label">Fizetendő összeg</div>
                        <div class="payment-amount-period">30 nap prémium hozzáférés</div>
                    </div>
                    <div class="payment-amount-value">DEMO</div>
                </div>
            </div>
            <div class="payment-body">
                <?php if (!empty($hibak)): ?>
                <div class="payment-errors">
                    <?php foreach ($hibak as $h): ?>
                    <div class="payment-error-item">
                        <span>✕</span>
                        <?= htmlspecialchars($h) ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <form method="post" style="display:contents;">
                    <div class="payment-field">
                        <label class="payment-label" for="card">Kártyaszám</label>
                        <div class="payment-input-wrap">
                            <input id="card" name="card" class="input" type="text" placeholder="4242 4242 4242 4242" maxlength="19" autocomplete="cc-number">
                        </div>
                        <span class="input-hint">Demo: 4242 4242 4242 4242</span>
                    </div>
                    <div class="payment-form-row">
                        <div class="payment-field">
                            <label class="payment-label" for="expiry">Lejárat</label>
                            <div class="payment-input-wrap">
                                <input id="expiry" name="expiry" class="input" type="text" placeholder="MM/ÉÉ" maxlength="5" autocomplete="cc-exp">
                            </div>
                        </div>
                        <div class="payment-field">
                            <label class="payment-label" for="cvv">CVV</label>
                            <div class="payment-input-wrap">
                                <input id="cvv" name="cvv" class="input" type="text" placeholder="123" maxlength="3" autocomplete="cc-csc">
                            </div>
                            <span class="input-hint">Demo: 123</span>
                        </div>
                    </div>
                    <button name="pay" class="btn-cta payment-submit">
                        Prémium aktiválása (DEMO)
                    </button>
                </form>
                <div class="payment-secure-row">
                    Biztonságos DEMO fizetési felület - valódi adatok nem kerülnek feldolgozásra
                </div>
                <div class="payment-cancel">
                    <a href="premium.php">Vissza a prémium oldalra</a>
                </div>
            </div>
        </div>
    </div>
    <?php include "assets/php/footer.php"; ?>
</body>
</html>