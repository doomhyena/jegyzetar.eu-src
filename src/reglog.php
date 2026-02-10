<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    session_start();

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    $prefillUsername = '';
    $prefillEmail = '';

    if (!empty($_SESSION['discord_prefill']) && is_array($_SESSION['discord_prefill'])) {
        $prefillUsername = $_SESSION['discord_prefill']['username'] ?? '';
        $prefillEmail = $_SESSION['discord_prefill']['email'] ?? '';
        unset($_SESSION['discord_prefill']);
    }

    $security_questions = [
        t('sec_q_favorite_book'),
        t('sec_q_first_pet_name'),
        t('sec_q_mother_maiden_name'),
        t('sec_q_birth_city'),
        t('sec_q_favorite_food')
    ];

    $selected_question = $security_questions[array_rand($security_questions)];

    // alap form: ha discordból jön prefill, akkor reg, különben login
    $currentForm = ($prefillUsername || $prefillEmail) ? 'reg' : 'login';
    if (isset($_POST['reg-btn'])) $currentForm = 'reg';
    if (isset($_POST['login-btn'])) $currentForm = 'login';

    /**
     * Helper: biztonságos redirect (ne maradjon futó kód)
     */
    function go($url) {
        header("Location: " . $url);
        exit;
    }

    /**
     * REGISTER
     */
    if (isset($_POST['reg-btn'])) {
        $lastname = trim($_POST['lastname'] ?? '');
        $firstname = trim($_POST['firstname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $birthdate = $_POST['birthdate'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password1'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $registration_date = date('Y-m-d H:i:s');
        $security_question = $_POST['security_question'] ?? '';
        $security_answer = password_hash(trim($_POST['security_answer']), PASSWORD_DEFAULT);
        $regCode = trim($_POST['reg_code'] ?? '');

        // 1) reg kód validálás
        if ($regCode === '') {
            echo "<script>alert('A regisztrációs kód megadása kötelező.');</script>";
        } else {
            $codeRes = db_query($conn, "SELECT * FROM reg_codes WHERE code = ? AND active = 1 AND (expires_at IS NULL OR expires_at > NOW()) AND (max_uses IS NULL OR used < max_uses) LIMIT 1",  "s",  [$regCode]);

            if ($codeRes->num_rows !== 1) {
                echo "<script>alert('Érvénytelen vagy lejárt regisztrációs kód.');</script>";
            } else {
                $codeRow = $codeRes->fetch_assoc();

                // 2) username / email unique
                $found_user = db_query($conn, "SELECT id FROM users WHERE username = ? LIMIT 1", "s", [$username]);
                if ($found_user->num_rows > 0) {
                    echo "<script>alert('" . t('msg_username_exists') . "');</script>";
                } else {
                    $found_email = db_query($conn, "SELECT id FROM users WHERE email = ? LIMIT 1", "s", [$email]);
                    if ($found_email->num_rows > 0) {
                        echo "<script>alert('" . t('msg_email_exists') . "');</script>";
                    } else {
						
					// birthday validation
					$birth = DateTime::createFromFormat('Y-m-d', $birthdate);
					$today = new DateTime();

					if (!$birth) {
					echo "<script>alert('Érvénytelen születési dátum.');</script>";
					} elseif ($birth->diff($today)->y < 13) {
					echo "<script>alert('13 év alatt nem lehet regisztrálni.');</script>";
					} else {
						
                        // 3) password match
                        if ($password !== $password2) {
                            echo "<script>alert('" . t('msg_passwords_not_match') . "');</script>";
                        } else {
                            $hashed = password_hash($password, PASSWORD_DEFAULT);
                            // 4) user insert
                            $stmt = db_stmt($conn, "INSERT INTO users (lastname, firstname, username, birthdate, gender, email, password, security_question, security_answer, registration_date, admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)", "ssssssssss", [$lastname, $firstname, $username, $birthdate, $gender, $email, $hashed, $security_question, $security_answer, $registration_date]);
                            $stmt->close();

                            $newUserId = (int)$conn->insert_id;
                            if ($newUserId <= 0) {
                                echo "<script>alert('Hiba történt a regisztráció során.');</script>";
                            } else {
                                // 5) reg kód használat növelés
                                db_stmt($conn, "UPDATE reg_codes SET used = used + 1, active = CASE WHEN max_uses IS NOT NULL AND used + 1 >= max_uses THEN 0 ELSE active END WHERE id = ?", "i",  [$codeRow['id']])->close();

                                // 6) user mappa létrehozás
                                $folder = getcwd();
                                $path = $folder . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $username;

                                if (!is_dir($path)) {
                                    if (!mkdir($path, 0777, true)) {
                                        echo "<script>alert('" . t('msg_storage_failed') . "');</script>";
                                        // nem állítjuk meg a reget, mert user már létrejött, csak storage nincs
                                    } else {
                                        // opcionális: csak akkor alertelj, ha akarod
                                        // echo "<script>alert('" . t('msg_storage_created') . "');</script>";
                                    }
                                }

                                // 7) verifikációs session (mail-regver.php használja)
                                $_SESSION["ver_id"] = $newUserId;
                                $_SESSION["email"]  = $email;

                                // ha nálad még kell cookie is:
                                setcookie("id", $newUserId, time() + 3600, "/");

                                go("mail-regver.php");
                            }
                        }
                    }
                }
            }
        }
    }
}
	
    /**
     * LOGIN
     */
    if (isset($_POST['login-btn'])) {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $found_user = db_query($conn, "SELECT * FROM users WHERE username = ? LIMIT 1", "s", [$username]);

        if ($found_user->num_rows <= 0) {
            echo "<script>alert('" . t('msg_user_not_found') . "');</script>";
        } else {
            $user = $found_user->fetch_assoc();

            if (!password_verify($password, $user['password'])) {
                echo "<script>alert('" . t('msg_wrong_password') . "');</script>";
            } else {
                if ((int)($user['email_verified'] ?? 0) === 0) {
                    echo "<script>alert('Kérlek aktiváld a fiókodat!');</script>";
                } else {

                    if ((int)$user['twofa_enabled'] === 1) {
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['email'] = $user['email'];
                        header("Location: mail-2fa.php");
                        exit;
                    } else {
                        setcookie("id", (int)$user['id'], ['expires' => time() + 60 * 60 * 24 * 30, 'path' => '/', 'secure' => isset($_SERVER['HTTPS']), 'httponly' => true, 'samesite' => 'Lax']);
                        go("index.php");
                    }
                }
            }
        }
    }

    include "assets/php/navbar.php";
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
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="main w-full max-w-5xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-12">
    <div class="auth-wrap">
        <div class="auth-head text-center mb-8">
            <h1 class="text-3xl md:text-4xl lg:text-5xl mb-3"><?= t('auth_welcome_title') ?></h1>
            <p class="auth-note text-sm md:text-base opacity-80"><?= t('auth_welcome_subtitle') ?></p>
        </div>
        <div class="auth-grid grid grid-cols-1 lg:grid-cols-2 gap-6">
            <form class="auth-card p-6 md:p-8 flex flex-col gap-4" id="login" method="post" style="<?= $currentForm==='login' ? '' : 'display:none;' ?>">
                <h1 class="text-2xl md:text-3xl mb-2"><?= t('auth_login_heading') ?></h1>
                <label for="login_username" class="text-sm md:text-base font-semibold"><?= t('label_username') ?></label>
                <input class="input w-full text-sm md:text-base" type="text" name="username" id="login_username" required>
                <label for="login_password" class="text-sm md:text-base font-semibold"><?= t('label_password') ?></label>
                <input class="input w-full text-sm md:text-base" type="password" name="password" id="login_password" required>
                <div class="auth-actions flex flex-col md:flex-row gap-3 mt-3">
                    <button class="btn-cta w-full md:w-auto text-sm md:text-base" type="submit" name="login-btn"><?= t('auth_btn_login') ?></button>
                    <a class="btn-ghost w-full md:w-auto text-center text-sm md:text-base" href="forgotpass.php"><?= t('auth_forgot_password') ?></a>
                </div>
                <p class="auth-note text-sm md:text-base text-center mt-4">
                    <?= t('auth_no_account') ?>
                    <a class="switcher underline" href="#" data-switch="reg"><?= t('auth_link_register') ?></a>
                </p>
            </form>
            <form class="auth-card p-6 md:p-8 flex flex-col gap-3" id="reg" method="post" style="<?= $currentForm==='reg' ? '' : 'display:none;' ?>">
                <h1 class="text-2xl md:text-3xl mb-2"><?= t('auth_register_heading') ?></h1>
                <label for="lastname" class="text-sm md:text-base font-semibold"><?= t('label_lastname') ?></label>
                <input class="input w-full text-sm md:text-base" type="text" name="lastname" id="lastname" required>
                <label for="firstname" class="text-sm md:text-base font-semibold"><?= t('label_firstname') ?></label>
                <input class="input w-full text-sm md:text-base" type="text" name="firstname" id="firstname" required>
                <label for="username" class="text-sm md:text-base font-semibold"><?= t('label_username') ?></label>
                <input class="input w-full text-sm md:text-base" type="text" name="username" id="username"
                       value="<?= htmlspecialchars($prefillUsername, ENT_QUOTES, 'UTF-8') ?>" required>
                <label for="birthdate" class="text-sm md:text-base font-semibold"><?= t('label_birthdate') ?></label>
                <input class="input w-full text-sm md:text-base" type="date" name="birthdate" id="birthdate" required>
                <label for="gender" class="text-sm md:text-base font-semibold"><?= t('label_gender') ?></label>
                <select class="select w-full text-sm md:text-base" name="gender" id="gender" required>
                    <option value="male"><?= t('gender_male') ?></option>
                    <option value="female"><?= t('gender_female') ?></option>
                    <option value="other"><?= t('gender_other') ?></option>
                </select>
                <label for="email" class="text-sm md:text-base font-semibold"><?= t('label_email') ?></label>
                <input class="input w-full text-sm md:text-base" type="email" name="email" id="email"
                       value="<?= htmlspecialchars($prefillEmail, ENT_QUOTES, 'UTF-8') ?>" required>
                <label for="reg_code" class="text-sm md:text-base font-semibold">Regisztrációs kód</label>
                <input class="input w-full text-sm md:text-base" type="text" name="reg_code" id="reg_code" required>
                <label for="password1" class="text-sm md:text-base font-semibold"><?= t('label_password') ?></label>
                <input class="input w-full text-sm md:text-base" type="password" name="password1" id="password1" required>
                <label for="password2" class="text-sm md:text-base font-semibold"><?= t('label_password_again') ?></label>
                <input class="input w-full text-sm md:text-base" type="password" name="password2" id="password2" required>
                <p class="auth-note text-sm md:text-base p-3 bg-white/5 rounded-lg">
                    <strong><?= t('auth_security_question_label') ?></strong><br>
                    <?= htmlspecialchars($selected_question) ?>
                </p>
                <input type="hidden" name="security_question" value="<?= htmlspecialchars($selected_question) ?>">
                <label for="security_answer" class="text-sm md:text-base font-semibold"><?= t('auth_security_answer_label') ?></label>
                <input class="input w-full text-sm md:text-base" type="text" name="security_answer" id="security_answer" required>
                <div class="auth-actions flex flex-col gap-3 mt-3">
                    <button class="btn-cta w-full text-sm md:text-base" type="submit" name="reg-btn"><?= t('auth_btn_register') ?></button>
                </div>
                <p class="auth-note text-sm md:text-base text-center mt-4">
                    <?= t('auth_have_account') ?>
                    <a class="switcher underline" href="#" data-switch="login"><?= t('auth_link_login') ?></a>
                </p>
            </form>
            <div class="auth-actions lg:col-span-2 flex justify-center mt-4">
                <a class="btn-ghost w-full md:w-auto text-center text-sm md:text-base" href="assets/oauth/discord-login.php">
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
