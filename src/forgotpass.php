<?php
    session_start();

    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    $showSecurityForm = true;
    $showNewPassword  = false;
    $success          = false;

    define('MAX_ATTEMPTS', 5);
    define('LOCK_MINUTES', 15);

    function check_csrf(): void {
        if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
            exit('CSRF blocked');
        }
    }

    function get_ip(): string {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    if (isset($_POST['forg-btn'])) {

        check_csrf();

        $username = trim($_POST['username'] ?? '');
        $answer = trim($_POST['security_answer'] ?? '');
        $ip = get_ip();

        if ($username === '' || $answer === '') {
            alert_redirect(t('error_all_fields_required'));
        }

        $rlRes = db_query($conn, "SELECT attempts, locked_until FROM password_reset_attempts WHERE username = ? AND ip_address = ? LIMIT 1", "ss", [$username, $ip]);

        if ($rlRes && $rlRes->num_rows === 1) {
            $rl = $rlRes->fetch_assoc();

            if ($rl['locked_until'] && strtotime($rl['locked_until']) > time()) {
                alert_redirect(t('msg_too_many_attempts'));
            }
        }

        $res = db_query(
            $conn,
            "SELECT id, security_answer FROM users WHERE username = ? LIMIT 1",
            "s",
            [$username]
        );

        if (!$res || $res->num_rows !== 1) {
            alert_redirect(t('msg_user_not_found'));
        }

        $user = $res->fetch_assoc();

        if (!password_verify($answer, $user['security_answer'])) {

            if ($rlRes && $rlRes->num_rows === 1) {
                $attempts = (int)$rl['attempts'] + 1;
                $lockedUntil = $attempts >= MAX_ATTEMPTS
                    ? date('Y-m-d H:i:s', strtotime('+' . LOCK_MINUTES . ' minutes'))
                    : null;

                db_exec($conn, "UPDATE password_reset_attempts SET attempts = ?, locked_until = ?, last_attempt = NOW() WHERE username = ? AND ip_address = ?",  "isss",  [$attempts, $lockedUntil, $username, $ip]);
            } else {
                db_exec($conn, "INSERT INTO password_reset_attempts  (username, ip_address, attempts, last_attempt) VALUES (?, ?, 1, NOW())",  "ss",  [$username, $ip]);
            }

            alert_redirect(t('msg_wrong_security_answer'));
        }

        db_exec($conn, "DELETE FROM password_reset_attempts WHERE username = ? AND ip_address = ?", "ss", [$username, $ip]);

        $_SESSION['pw_reset_user'] = (int)$user['id'];
        $_SESSION['pw_reset_ok']   = true;

        $showSecurityForm = false;
        $showNewPassword  = true;
    }

    if (isset($_POST['new-pass-btn'])) {

        check_csrf();

        if (empty($_SESSION['pw_reset_user']) || empty($_SESSION['pw_reset_ok'])) {
            exit('Unauthorized');
        }

        $p1 = $_POST['password1'] ?? '';
        $p2 = $_POST['password2'] ?? '';

        if ($p1 !== $p2) {
            alert_redirect(t('msg_passwords_not_match'));
        }

        if (strlen($p1) < 8) {
            alert_redirect(t('msg_password_too_short'));
        }

        $hash = password_hash($p1, PASSWORD_DEFAULT);

        db_exec($conn, "UPDATE users SET password = ? WHERE id = ? LIMIT 1", "si", [$hash, (int)$_SESSION['pw_reset_user']]);

        session_unset();
        session_destroy();

        $success = true;
    }
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title><?= t('password_forgot_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name="author" content="Baranyi Norbert, Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<div class="main w-full max-w-lg mx-auto px-4 md:px-6 lg:px-8 py-6">
    <?php if ($success): ?>
        <div class="card p-6 md:p-8 flex flex-col gap-4 text-center">
            <h3 class="text-xl md:text-2xl"><?= t('change_success_title') ?></h3>
            <p class="text-sm md:text-base"><?= t('password_change_success_text') ?></p>
            <a class="btn-cta w-full md:w-auto text-sm md:text-base mx-auto" href="reglog.php"><?= t('btn_go_to_login') ?></a>
        </div>
    <?php elseif ($showNewPassword): ?>
        <h1 class="text-2xl md:text-3xl lg:text-4xl mb-6"><?= t('password_reset_heading_new') ?></h1>
        <form method="post" class="card p-6 md:p-8 flex flex-col gap-4">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
            <label class="text-sm md:text-base font-semibold"><?= t('label_new_password') ?></label>
            <input class="input w-full text-sm md:text-base" type="password" name="password1" required>
            <label class="text-sm md:text-base font-semibold"><?= t('label_password_again') ?></label>
            <input class="input w-full text-sm md:text-base" type="password" name="password2" required>
            <button class="btn-cta w-full md:w-auto text-sm md:text-base mt-2" name="new-pass-btn">
                <?= t('btn_change_password') ?>
            </button>
        </form>
    <?php else: ?>
        <h1 class="text-2xl md:text-3xl lg:text-4xl mb-6"><?= t('password_reset_heading_main') ?></h1>
        <form method="post" class="card p-6 md:p-8 flex flex-col gap-4">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
            <label class="text-sm md:text-base font-semibold"><?= t('label_username') ?></label>
            <input class="input w-full text-sm md:text-base" type="text" name="username" required>
            <label class="text-sm md:text-base font-semibold"><?= t('label_security_answer_full') ?></label>
            <input class="input w-full text-sm md:text-base" type="text" name="security_answer" required>
            <div class="flex flex-col md:flex-row gap-3 mt-2">
                <button class="btn-cta w-full md:w-auto text-sm md:text-base" name="forg-btn">
                    <?= t('btn_submit') ?>
                </button>
                <a class="btn-ghost w-full md:w-auto text-center text-sm md:text-base" href="reglog.php">
                    <?= t('link_back_to_login') ?>
                </a>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php include "assets/php/footer.php"; ?>
</body>
</html>
