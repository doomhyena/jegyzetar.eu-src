<!DOCTYPE html>
<html lang="hu">
<head>
    <title><?= t('2fa_title') ?></title>
    <meta name='description' content='Iskolai jegyzeteket megosztó oldal'>
    <meta name='keywords' content='iskola, jegyzet, megosztás, tanulás'>
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body class="no-ads admin-page">
    <?php

        header("X-Frame-Options: DENY");
        header("X-Content-Type-Options: nosniff");
        header("Referrer-Policy: no-referrer");

        // norbi: 2fa
        require "assets/php/db.php";
        require_once "assets/php/lang.php";
        require_once "assets/php/functions.php";

        if (!isset($_SESSION['tries'])) {
                $_SESSION['tries'] = 0;
        }

        require_login();

        $userid = auth_user_id();
        $user = auth_user($conn);

        include 'assets/php/navbar.php';

        if (isset($_POST['code'])) {

            $code = trim($_POST['code']);

            if ($code !== '') {
                // Először ellenőrizzük az email-ben kapott kódot
                $talalt_sorok = db_query($conn, "SELECT id FROM 2fa_codes WHERE userid = ? AND code = ? LIMIT 1", "is", [$userid, $code]);

                if ($talalt_sorok && $talalt_sorok->num_rows === 1) {
                    db_exec($conn, "DELETE FROM 2fa_codes WHERE userid = ? AND code = ?", "is", [$userid, $code]);
                    if ($user) {
                        auth_login_user($user); // renew session flags
                    }
                    header("Location: index.php");
                    exit;
                }
                
                // Ha nem email-s kód, próbáljuk meg backup kódként
                if (verify_backup_code($conn, $userid, $code)) {
                    if ($user) {
                        auth_login_user($user);
                    }
                    header("Location: index.php");
                    exit;
                }
            }

            echo "<script>alert('" . addslashes(t('2fa_error_invalid_code')) . "')</script>";
            $_SESSION['tries']++;
            if ($_SESSION['tries'] >= 3) {
                session_destroy();
                header("Location: reglog.php");
                exit;
            }
        }
    ?>
    <div class="main w-full max-w-lg mx-auto px-4 md:px-6 lg:px-8 py-6">
        <div class="auth-wrap">
            <div class="auth-head mb-6">
                <h1 class="text-2xl md:text-3xl lg:text-4xl mb-2"><?= t('2fa_title') ?></h1>
                <p class="auth-note text-sm md:text-base"><?= t('2fa_description') ?></p>
            </div>
            <div class="auth-grid">
                <form class="auth-card p-6 md:p-8 flex flex-col gap-4" method="post">
                    <input class="input w-full text-sm md:text-base" type="text" name="code" id="code" placeholder="<?= t('2fa_placeholder_code') ?>" required>
                    <div class="auth-actions flex flex-col md:flex-row gap-3">
                        <button class="btn-cta w-full md:w-auto text-sm md:text-base" type="submit"><?= t('2fa_submit') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>
