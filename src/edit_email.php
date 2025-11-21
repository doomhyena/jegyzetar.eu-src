<?php
    require "assets/php/db.php";
    require "assets/php/lang.php";
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title><?= t('email_edit_title') ?></title>
    <meta charset='UTF-8'>
    <meta name='description' content='<?= t('meta_description_home') ?>'>
    <meta name='keywords' content='<?= t('meta_keywords_home') ?>'>
    <meta name='author' content='Baranyai Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.aurora.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
<div class="main" style="max-width: 600px;">
    <?php
    if (isset($_POST['forg-btn'])) {
        $username = $_POST['username'] ?? '';
        $security_answer = $_POST['security_answer'] ?? '';

        $username_esc = $conn->real_escape_string($username);
        $sql = "SELECT * FROM users WHERE username='$username_esc'";
        $found_user = $conn->query($sql);

        if ($found_user && $found_user->num_rows > 0) {
            $user = $found_user->fetch_assoc();

            if ($security_answer === $user['security_answer']) {
                ?>
                <h1><?= t('email_edit_heading_new') ?></h1>
                <form class="card" method="post" action="edit_email.php?userid=<?= (int)$user['id'] ?>">
                    <label for="email1"><?= t('label_new_email') ?></label>
                    <input class="input" type="email" name="email1"
                           id="email1"
                           placeholder="<?= t('placeholder_email') ?>" required>

                    <label for="email2"><?= t('label_new_email_again') ?></label>
                    <input class="input" type="email" name="email2"
                           id="email2"
                           placeholder="<?= t('placeholder_email_again') ?>" required>

                    <button type="submit" name="new-email-btn" class="btn-cta">
                        <?= t('btn_change_email') ?>
                    </button>
                </form>
                <?php
            } else {
                echo "<script>alert('".t('msg_wrong_security_answer')."');</script>";
                echo "<meta http-equiv='refresh' content='0;url=edit_email.php'>";
            }
        } else {
            echo "<script>alert('".t('msg_user_not_found')."');</script>";
            echo "<meta http-equiv='refresh' content='0;url=edit_email.php'>";
        }

    } elseif (isset($_POST['new-email-btn'])) {
        if (!isset($_GET['userid'])) {
            echo "<script>alert('".t('msg_invalid_user_id')."');</script>";
            echo "<meta http-equiv='refresh' content='0;url=edit_email.php'>";
        } else {
            $userid = (int)$_GET['userid'];
            $email1 = $_POST['email1'] ?? '';
            $email2 = $_POST['email2'] ?? '';

            if ($email1 === $email2) {
                $sql = "SELECT * FROM users WHERE id=$userid";
                $found_user = $conn->query($sql);
                if ($found_user && $found_user->num_rows > 0) {
                    $user = $found_user->fetch_assoc();

                    if ($email1 !== $user['email']) {
                        $email_esc = $conn->real_escape_string($email1);
                        $conn->query("UPDATE users SET email='$email_esc' WHERE id=$userid");
                        ?>
                        <div class="card">
                            <h3><?= t('change_success_title') ?></h3>
                            <p><?= t('email_edit_success_text') ?></p>
                            <a class="btn-cta" href="index.php">
                                <?= t('btn_back_home') ?>
                            </a>
                        </div>
                        <?php
                    } else {
                        echo "<script>alert('".t('msg_email_same_as_old')."');</script>";
                        echo "<meta http-equiv='refresh' content='0;url=edit_email.php'>";
                    }
                } else {
                    echo "<script>alert('".t('msg_user_not_found')."');</script>";
                    echo "<meta http-equiv='refresh' content='0;url=edit_email.php'>";
                }
            } else {
                echo "<script>alert('".t('msg_emails_not_match')."');</script>";
                echo "<meta http-equiv='refresh' content='0;url=edit_email.php'>";
            }
        }
    } else {
        ?>
        <h1><?= t('email_edit_heading_main') ?></h1>
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
            <a class="btn-ghost" href="profile.php?userid=<?= (int)($_COOKIE['id'] ?? 0) ?>">
                <?= t('btn_back_profile') ?>
            </a>
        </form>
        <?php
    }
    ?>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
