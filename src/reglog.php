<?php
    require "assets/php/db.php";
    require_once "assets/php/lang.php";

    include 'assets/php/navbar.php';

    $security_questions = [
            t('sec_q_favorite_book'),
            t('sec_q_first_pet_name'),
            t('sec_q_mother_maiden_name'),
            t('sec_q_birth_city'),
            t('sec_q_favorite_food')
    ];

    $selected_question = $security_questions[array_rand($security_questions)];
    $currentForm = 'login';

    if (isset($_POST['reg-btn'])) {
        $lastname = $_POST['lastname']   ?? '';
        $firstname = $_POST['firstname']  ?? '';
        $username= $_POST['username']   ?? '';
        $birthdate = $_POST['birthdate']  ?? '';
        $gender = $_POST['gender']     ?? '';
        $email = $_POST['email']      ?? '';
        $password = $_POST['password1']  ?? '';
        $passwordtwo= $_POST['password2']  ?? '';
        $registration_date = date('Y-m-d H:i:s');
        $security_question = $_POST['security_question'] ?? '';
        $security_answer = $_POST['security_answer']   ?? '';

        $currentForm = 'reg';

        // nagyon basic sanitize – élesben prepared statement kéne
        $username_esc = $conn->real_escape_string($username);
        $email_esc = $conn->real_escape_string($email);

        $sql = "SELECT * FROM users WHERE username='{$username_esc}'";
        $found_user = $conn->query($sql);

        if ($found_user && $found_user->num_rows == 0) {
            $sql = "SELECT * FROM users WHERE email='{$email_esc}'";
            $found_email = $conn->query($sql);

            if ($found_email && $found_email->num_rows == 0) {
                if ($password === $passwordtwo) {
                    $titkositott_jelszo = password_hash($password, PASSWORD_DEFAULT);

                    $lastname_esc = $conn->real_escape_string($lastname);
                    $firstname_esc = $conn->real_escape_string($firstname);
                    $birthdate_esc = $conn->real_escape_string($birthdate);
                    $gender_esc = $conn->real_escape_string($gender);
                    $sec_q_esc = $conn->real_escape_string($security_question);
                    $sec_a_esc = $conn->real_escape_string($security_answer);
                    $regdate_esc  = $conn->real_escape_string($registration_date);
                    $password_esc = $conn->real_escape_string($titkositott_jelszo);

                    // norbi: admin mező hozzáadása (0 = nem admin), 
                    //sql-be kivan véve az auto increment (???)
                    //hogy akarunk igy egyedi id-t?
                    $sql = "
                            INSERT INTO users
                                (lastname, firstname, username, birthdate, gender, email, password, security_question, security_answer, registration_date, admin)
                            VALUES
                                ('{$lastname_esc}', '{$firstname_esc}', '{$username_esc}', '{$birthdate_esc}', '{$gender_esc}',
                                 '{$email_esc}', '{$password_esc}', '{$sec_q_esc}', '{$sec_a_esc}', '{$regdate_esc}', 0)
                        ";
                    if ($conn->query($sql)) {
                        $folder = getcwd();
                        $path = $folder . DIRECTORY_SEPARATOR . "users" . DIRECTORY_SEPARATOR . $username;
                        if (!is_dir($path) && mkdir($path, 0777, true)) {
                            echo "<script>alert('".t('msg_storage_created')."');</script>";
                            header("Location: reglog.php");
                            exit;
                        } else {
                            echo "<script>alert('".t('msg_storage_failed')."');</script>";
                        }
                        $currentForm = 'login';
                    } else {
                        echo "<script>alert('".t('msg_registration_failed')."');</script>";
                    }
                } else {
                    echo "<script>alert('".t('msg_passwords_not_match')."');</script>";
                }
            } else {
                echo "<script>alert('".t('msg_email_exists')."');</script>";
            }
        } else {
            echo "<script>alert('".t('msg_username_exists')."');</script>";
        }
    }

    if (isset($_POST['login-btn'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $currentForm = 'login';

        $username_esc = $conn->real_escape_string($username);
        $sql = "SELECT * FROM users WHERE username='{$username_esc}'";
        $found_user = $conn->query($sql);

        if ($found_user && $found_user->num_rows > 0) {
            $user = $found_user->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                setcookie("id", $user['id'], time() + 3600, "/");
                header("Location: index.php");
                exit;
            } else {
                echo "<script>alert('".t('msg_wrong_password')."');</script>";
            }
        } else {
            echo "<script>alert('".t('msg_user_not_found')."');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title><?= t('auth_page_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyai Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.aurora.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="main">
    <div class="auth-wrap">
        <div class="auth-head">
            <h1><?= t('auth_welcome_title') ?></h1>
            <p class="auth-note"><?= t('auth_welcome_subtitle') ?></p>
        </div>
        <div class="auth-grid">
            <form class="auth-card" id="login" method="post" style="<?= $currentForm==='login' ? '' : 'display:none;' ?>">
                <h1><?= t('auth_login_heading') ?></h1>
                <label for="login_username"><?= t('label_username') ?></label>
                <input class="input" type="text" name="username" id="login_username" required>
                <label for="login_password"><?= t('label_password') ?></label>
                <input class="input" type="password" name="password" id="login_password" required>
                <div class="auth-actions" style="margin-top:12px;">
                    <button class="btn-cta" type="submit" name="login-btn"><?= t('auth_btn_login') ?></button>
                    <a class="btn-ghost" href="forgotpass.php"><?= t('auth_forgot_password') ?></a>
                </div>
                <p class="auth-note" style="margin-top:16px;">
                    <?= t('auth_no_account') ?>
                    <a class="switcher" href="#" data-switch="reg"><?= t('auth_link_register') ?></a>
                </p>
            </form>
            <form class="auth-card" id="reg" method="post" style="<?= $currentForm==='reg' ? '' : 'display:none;' ?>">
                <h1><?= t('auth_register_heading') ?></h1>
                <label for="lastname"><?= t('label_lastname') ?></label>
                <input class="input" type="text" name="lastname" id="lastname" required>
                <label for="firstname"><?= t('label_firstname') ?></label>
                <input class="input" type="text" name="firstname" id="firstname" required>
                <label for="username"><?= t('label_username') ?></label>
                <input class="input" type="text" name="username" id="username" required>
                <label for="birthdate"><?= t('label_birthdate') ?></label>
                <input class="input" type="date" name="birthdate" id="birthdate" required>
                <label for="gender"><?= t('label_gender') ?></label>
                <select class="select" name="gender" id="gender" required>
                    <option value="male"><?= t('gender_male') ?></option>
                    <option value="female"><?= t('gender_female') ?></option>
                    <option value="other"><?= t('gender_other') ?></option>
                </select>
                <label for="email"><?= t('label_email') ?></label>
                <input class="input" type="email" name="email" id="email" required>
                <label for="password1"><?= t('label_password') ?></label>
                <input class="input" type="password" name="password1" id="password1" required>
                <label for="password2"><?= t('label_password_again') ?></label>
                <input class="input" type="password" name="password2" id="password2" required>
                <p class="auth-note">
                    <strong><?= t('auth_security_question_label') ?></strong>
                    <?= htmlspecialchars($selected_question) ?>
                </p>
                <input type="hidden" name="security_question" value="<?= htmlspecialchars($selected_question) ?>">
                <label for="security_answer"><?= t('auth_security_answer_label') ?></label>
                <input class="input" type="text" name="security_answer" id="security_answer" required>
                <div class="auth-actions" style="margin-top:12px;">
                    <button class="btn-cta" type="submit" name="reg-btn"><?= t('auth_btn_register') ?></button>
                </div>
                <p class="auth-note" style="margin-top:16px;">
                    <?= t('auth_have_account') ?>
                    <a class="switcher" href="#" data-switch="login"><?= t('auth_link_login') ?></a>
                </p>
            </form>
            <div class="auth-actions" style="margin-top:12px;">
                <a class="btn-ghost disabled" href="oauth/discord-login.php">
                    <?= t('auth_continue_with_discord') ?>
                </a>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/script.js"></script>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
