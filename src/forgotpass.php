<?php
    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once 'assets/php/functions.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title><?= t('password_forgot_title') ?></title>
    <meta charset='UTF-8'>
    <meta name='description' content='<?= t('meta_description_home') ?>'>
    <meta name='keywords' content='<?= t('meta_keywords_home') ?>'>
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
<div class="main" style="max-width: 600px;">
    <?php
    if (isset($_POST['forg-btn'])) {
        $username        = $_POST['username'] ?? '';
        $security_answer = $_POST['security_answer'] ?? '';

        $username_esc = $conn->real_escape_string($username);
        $sql = "SELECT * FROM users WHERE username='$username_esc'";
        $found_user = $conn->query($sql);

        if ($found_user && $found_user->num_rows > 0) {
            $user = $found_user->fetch_assoc();

            if ($security_answer === $user['security_answer']) {
                ?>
                <h1><?= t('password_reset_heading_new') ?></h1>
                <form class="card" method="post" action="forgotpass.php?userid=<?= (int)$user['id'] ?>">
                    <label for="password1"><?= t('label_new_password') ?></label>
                    <input class="input" type="password" name="password1" id="password1"
                           placeholder="<?= t('placeholder_password') ?>" required>
                    <label for="password2"><?= t('label_password_again') ?></label>
                    <input class="input" type="password" name="password2" id="password2"
                           placeholder="<?= t('placeholder_password_again') ?>" required>
                    <button type="submit" name="new-pass-btn" class="btn-cta">
                        <?= t('btn_change_password') ?>
                    </button>
                </form>
                <?php
            } else {
                echo "<script>alert('".t('msg_wrong_security_answer')."');</script>";
                echo "<meta http-equiv='refresh' content='0;url=forgotpass.php'>";
            }
        } else {
            echo "<script>alert('".t('msg_user_not_found')."');</script>";
            echo "<meta http-equiv='refresh' content='0;url=forgotpass.php'>";
        }
    } elseif (isset($_POST['new-pass-btn'])) {
        if (!isset($_GET['userid'])) {
            echo "<script>alert('".t('msg_invalid_user_id')."');</script>";
            echo "<meta http-equiv='refresh' content='0;url=forgotpass.php'>";
        } else {
            $userid = (int)$_GET['userid'];
            $pass1  = $_POST['password1'] ?? '';
            $pass2  = $_POST['password2'] ?? '';

            if ($pass1 === $pass2) {
                $sql = "SELECT * FROM users WHERE id=$userid";
                $found_user = $conn->query($sql);

                if ($found_user && $found_user->num_rows > 0) {
                    $user = $found_user->fetch_assoc();

                    if ($pass1 !== $user['password']) {
                        $titkositott_jelszo = password_hash($pass1, PASSWORD_DEFAULT);
                        $pass_esc = $conn->real_escape_string($titkositott_jelszo);
                        $conn->query("UPDATE users SET password='$pass_esc' WHERE id=$userid");
                        ?>
                        <div class="card">
                            <h3><?= t('change_success_title') ?></h3>
                            <p><?= t('password_change_success_text') ?></p>
                            <a class="btn-cta" href="reglog.php">
                                <?= t('btn_go_to_login') ?>
                            </a>
                        </div>
                        <?php
                    } else {
                        echo "<script>alert('".t('msg_password_same_as_old')."');</script>";
                        echo "<meta http-equiv='refresh' content='0;url=forgotpass.php'>";
                    }
                } else {
                    echo "<script>alert('".t('msg_user_not_found')."');</script>";
                    echo "<meta http-equiv='refresh' content='0;url=forgotpass.php'>";
                }
            } else {
                echo "<script>alert('".t('msg_passwords_not_match')."');</script>";
                echo "<meta http-equiv='refresh' content='0;url=forgotpass.php'>";
            }
        }
    } else {
        ?>
        <h1><?= t('password_reset_heading_main') ?></h1>
        <form class="card" method="post">
            <label for="username"><?= t('label_username') ?></label>
            <input class="input" type="text" name="username" id="username"
                   placeholder="<?= t('placeholder_username') ?>" required>

            <label for="security_answer"><?= t('label_security_answer_full') ?></label>
            <input class="input" type="text" name="security_answer" id="security_answer"
                   placeholder="<?= t('placeholder_security_answer') ?>" required>

            <button type="submit" name="forg-btn" class="btn-cta">
                <?= t('btn_submit') ?>
            </button>
            <a class="btn-ghost" href="reglog.php">
                <?= t('link_back_to_login') ?>
            </a>
        </form>
        <?php
    }
    ?>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>