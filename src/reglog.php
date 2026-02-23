<?php
    session_start();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    $errors = [];
    $lang = $lang ?? 'hu';
    $prefillUsername = '';
    $prefillEmail = '';
    $currentForm = 'login';
    
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");
    header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://code.jquery.com;");

    require_once __DIR__ . "/assets/php/db.php";
    require_once __DIR__ . "/assets/php/lang.php";
    require_once __DIR__ . "/assets/php/functions.php";

    require_once __DIR__ . "/assets/phpmailer/src/PHPMailer.php";
    require_once __DIR__ . "/assets/phpmailer/src/Exception.php";
    require_once __DIR__ . "/assets/phpmailer/src/SMTP.php";

    $config = json_decode(file_get_contents("config.json"), true);

    if (!function_exists('csrf_check')) {
        die('Fatal: csrf_check function not found. Include path: ' . '/assets/php/functions.php');
    }

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
    $currentForm = ($prefillUsername || $prefillEmail) ? 'reg' : 'login';

    if (isset($_POST['reg-btn'])) $currentForm = 'reg';
    if (isset($_POST['login-btn'])) $currentForm = 'login';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrf_check()) {
            $errors[] = "Érvénytelen kérés (CSRF). Frissítsd az oldalt és próbáld újra.";
        }
    }

    if (isset($_POST['reg-btn']) && empty($errors)) {

        $uTmp = trim($_POST['username'] ?? '');
        $rlRegKey = rl_key('reg', strtolower($uTmp));
        $rl = rl_allow($rlRegKey, 5, 600);
        if (!$rl['ok']) {
            $errors[] = "Túl sok regisztrációs próbálkozás. Próbáld újra {$rl['retry_after']} mp múlva.";
        }

        $lastname = trim($_POST['lastname'] ?? '');
        $firstname = trim($_POST['firstname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $birthdate = (string)($_POST['birthdate'] ?? '');
        $gender = (string)($_POST['gender'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password1'] ?? '');
        $password2 = (string)($_POST['password2'] ?? '');
        $registration_date = date('Y-m-d H:i:s');
        $security_question = (string)($_POST['security_question'] ?? '');
        $security_answer_plain = trim($_POST['security_answer'] ?? '');
        $regCode = trim($_POST['reg_code'] ?? '');

        $ip = get_client_ip() ?? null;
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);


        if ($lastname === '' || $firstname === '' || $username === '' || $birthdate === '' || $gender === '' || $email === '' || $regCode === '' || $security_answer_plain === '') {
            $errors[] = "Minden mező kitöltése kötelező.";
        }

        if ($username !== '' && !valid_username($username)) {
            $errors[] = "A felhasználónév 3-20 karakter lehet, és csak betű/szám valamint . _ - engedélyezett.";
        }

        $allowedGenders = ['male', 'female', 'other'];
        if ($gender !== '' && !in_array($gender, $allowedGenders, true)) {
            $errors[] = "Érvénytelen nem érték.";
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Érvénytelen email cím.";
        }

        if ($birthdate !== '' && !age_at_least_13($birthdate)) {
            $errors[] = "Érvénytelen születési dátum vagy 13 év alatt nem lehet regisztrálni.";
        }

        if ($password !== $password2) {
            $errors[] = t('msg_passwords_not_match');
        }
        if ($password !== '' && !password_policy_ok($password)) {
            $errors[] = "A jelszó legyen legalább 8 karakter, és tartalmazzon kisbetűt, nagybetűt és számot.";
        }

        if (empty($errors)) {

            $codeRes = db_query($conn, "SELECT * FROM reg_codes WHERE code = ? AND active = 1 AND (expires_at IS NULL OR expires_at > NOW()) AND (max_uses IS NULL OR used < max_uses) LIMIT 1", "s", [$regCode]);

            if ($codeRes->num_rows !== 1) {
                $errors[] = "Érvénytelen vagy lejárt regisztrációs kód.";
            } else {

                $found_user = db_query($conn, "SELECT id FROM users WHERE username = ? LIMIT 1", "s", [$username]);
                if ($found_user->num_rows > 0) {
                    $errors[] = t('msg_username_exists');
                } else {
                    $found_email = db_query($conn, "SELECT id FROM users WHERE email = ? LIMIT 1", "s", [$email]);
                    if ($found_email->num_rows > 0) {
                        $errors[] = t('msg_email_exists');
                    }
                }

                if (empty($errors)) {
                    $security_answer = password_hash($security_answer_plain, PASSWORD_DEFAULT);
                    $hashed = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = db_stmt($conn, "INSERT INTO users (lastname, firstname, username, birthdate, gender, email, password, security_question, security_answer, registration_date, admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)", "ssssssssss", [$lastname, $firstname, $username, $birthdate, $gender, $email, $hashed, $security_question, $security_answer, $registration_date]);
                    $stmt->close();

                    $newUserId = (int)$conn->insert_id;
                    if ($newUserId <= 0) {
                        $errors[] = "Hiba történt a regisztráció során.";
                    } else {
                        $codeRow = $codeRes->fetch_assoc();
                        db_stmt($conn, "UPDATE reg_codes SET used = used + 1, active = CASE WHEN max_uses IS NOT NULL AND used + 1 >= max_uses THEN 0 ELSE active END WHERE id = ?", "i", [$codeRow['id']])->close();

                        $folder = getcwd();
                        $path = $folder . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $username;
                        if (!is_dir($path)) {
                            @mkdir($path, 0777, true);
                        }

                        $_SESSION["ver_id"] = $newUserId;
                        $_SESSION["email"]  = $email;

                        db_exec($conn,"INSERT INTO registration_code_uses (user_id, reg_code, used_ip, user_agent)VALUES (?, ?, ?, ?)","isss",[$newUserId, $regCode, $ip, $ua]);
                        db_exec($conn, "UPDATE users SET used_reg_code=?, used_reg_code_at=NOW() WHERE id=?", "si", [$regCode, $newUserId]);

                        rl_clear($rlRegKey);
                        flash_set('success', 'Sikeres regisztráció! Nemsokára kapsz egy aktiváló e-mailt. Nézd meg a SPAM mappát is.');
                        go("assets/php/mail-regver.php");
                    }
                }
            }
        }

        if (!empty($errors)) {
            rl_hit($rlRegKey);
            echo "<script>alert(" . json_encode(implode("\\n", $errors)) . ");</script>";
        }
    }

    if (isset($_POST['login-btn']) && empty($errors)) {

        $uTmp = trim($_POST['username'] ?? '');
        $rlLoginKey = rl_key('login', strtolower($uTmp));
        $rl = rl_allow($rlLoginKey, 8, 600);
        if (!$rl['ok']) {
            echo "<script>alert(" . json_encode("Túl sok bejelentkezési próbálkozás. Próbáld újra {$rl['retry_after']} mp múlva.") . ");</script>";
        } else {
            $username = trim($_POST['username'] ?? '');
            $password = (string)($_POST['password'] ?? '');

            if ($username === '' || $password === '') {
                rl_hit($rlLoginKey);
                echo "<script>alert(" . json_encode("Add meg a felhasználónevet és a jelszót.") . ");</script>";
            } else {
                $genericFail = t('msg_wrong_password');

                $found_user = db_query($conn, "SELECT * FROM users WHERE username = ? LIMIT 1", "s", [$username]);

                if ($found_user->num_rows <= 0) {
                    rl_hit($rlLoginKey);
                    echo "<script>alert(" . json_encode($genericFail) . ");</script>";
                } else {
                    $user = $found_user->fetch_assoc();

                    if (!password_verify($password, $user['password'])) {
                        rl_hit($rlLoginKey);
                        echo "<script>alert(" . json_encode($genericFail) . ");</script>";
                    } else {
                        if ((int)($user['email_verified'] ?? 0) === 0) {
                            rl_hit($rlLoginKey);
                            echo "<script>alert(" . json_encode("Kérlek aktiváld a fiókodat!") . ");</script>";
                        } else {
                            rl_clear($rlLoginKey);

                            if ((int)($user['twofa_enabled'] ?? 0) === 1) {
                                $_SESSION['id'] = $user['id'];
                                $_SESSION['email'] = $user['email'];
                                flash_set('success', 'Elküldtük a bejelentkezési kódot e-mailben. Ha nem találod, nézd meg a SPAM mappát is.');
                                go("assets/php/mail-2fa.php");

                            } else {
                                setcookie("id", (string)((int)$user['id']), [
                                    'expires' => time() + 60 * 60 * 24 * 30,
                                    'path' => '/',
                                    'secure' => is_https(),
                                    'httponly'=> true,
                                    'samesite' => 'Lax'
                                ]);
                                go("index.php");
                            }
                        }
                    }
                }
            }
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
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body class="no-ads admin-page">
<?php include 'assets/php/navbar.php'; $flash = flash_get(); ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php if (flash_has('success')): ?>
    <div class="alert alert-success">
        <p><?= htmlspecialchars(flash_get('success')) ?></p>
    </div>
<?php endif; ?>
<?php if ($flash): ?>
  <div class="toast <?= ($flash['type'] === 'success') ? 'toast-success' : 'toast-error'; ?>">
    <?= htmlspecialchars((string)$flash['text'], ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>
<div class="main w-full max-w-5xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-12">
    <div class="auth-wrap">
        <div class="auth-head text-center mb-8">
            <h1 class="text-3xl md:text-4xl lg:text-5xl mb-3"><?= t('auth_welcome_title') ?></h1>
            <p class="auth-note text-sm md:text-base opacity-80"><?= t('auth_welcome_subtitle') ?></p>
        </div>
        <div class="auth-grid grid grid-cols-1 lg:grid-cols-2 gap-6">
            <form class="auth-card p-6 md:p-8" id="login" method="post" style="<?= $currentForm==='login' ? '' : 'display:none;' ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']  ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <h1 class="text-2xl md:text-3xl mb-4"><?= t('auth_login_heading') ?></h1>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="login_username" class="text-sm md:text-base font-semibold"><?= t('label_username') ?></label>
                        <input class="input w-full text-sm md:text-base" type="text" name="username" id="login_username" required>
                    </div>
                    <div>
                        <label for="login_password" class="text-sm md:text-base font-semibold"><?= t('label_password') ?></label>
                        <div class="relative">
                            <input class="input w-full text-sm md:text-base pr-12" type="password" name="password" id="login_password" required>
                            <button type="button" class="toggle-pass absolute right-2 top-1/2 -translate-y-1/2 text-sm opacity-80 hover:opacity-100"
                                    data-target="login_password" aria-label="Jelszó megtekintése">
                                👁
                            </button>
                        </div>
                    </div>
                </div>
                <div class="auth-actions flex flex-col md:flex-row gap-3 mt-5">
                    <button class="btn-cta w-full md:w-auto text-sm md:text-base" type="submit" name="login-btn"><?= t('auth_btn_login') ?></button>
                    <a class="btn-ghost w-full md:w-auto text-center text-sm md:text-base" href="forgotpass.php"><?= t('auth_forgot_password') ?></a>
                </div>
                <p class="auth-note text-sm md:text-base text-center mt-5">
                    <?= t('auth_no_account') ?>
                    <a class="switcher underline" href="#" data-switch="reg"><?= t('auth_link_register') ?></a>
                </p>
            </form>
            <form class="auth-card p-6 md:p-8" id="reg" method="post" style="<?= $currentForm==='reg' ? '' : 'display:none;' ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="security_question"
                value="<?= htmlspecialchars((string)($selected_question ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                <h1 class="text-2xl md:text-3xl mb-4"><?= t('auth_register_heading') ?></h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="lastname" class="text-sm md:text-base font-semibold"><?= t('label_lastname') ?></label>
                        <input class="input w-full text-sm md:text-base" type="text" name="lastname" id="lastname" required>
                    </div>
                    <div>
                        <label for="firstname" class="text-sm md:text-base font-semibold"><?= t('label_firstname') ?></label>
                        <input class="input w-full text-sm md:text-base" type="text" name="firstname" id="firstname" required>
                    </div>
                    <div>
                        <label for="username" class="text-sm md:text-base font-semibold"><?= t('label_username') ?></label>
                        <input class="input w-full text-sm md:text-base" type="text" name="username" id="username"
                               value="<?= htmlspecialchars($prefillUsername, ENT_QUOTES, 'UTF-8') ?>" required
                               minlength="3" maxlength="20" pattern="[a-zA-Z0-9._-]+">
                        <p class="text-xs opacity-70 mt-1">3-20 karakter, betű/szám + . _ -</p>
                    </div>
                    <div>
                        <label for="birthdate" class="text-sm md:text-base font-semibold"><?= t('label_birthdate') ?></label>
                        <input class="input w-full text-sm md:text-base" type="date" name="birthdate" id="birthdate" required>
                        <p class="text-xs opacity-70 mt-1">13 év alatt nem lehet regisztrálni.</p>
                    </div>
                    <div>
                        <label for="gender" class="text-sm md:text-base font-semibold"><?= t('label_gender') ?></label>
                        <select class="select w-full text-sm md:text-base" name="gender" id="gender" required>
                            <option value="male"><?= t('gender_male') ?></option>
                            <option value="female"><?= t('gender_female') ?></option>
                            <option value="other"><?= t('gender_other') ?></option>
                        </select>
                    </div>
                    <div>
                        <label for="email" class="text-sm md:text-base font-semibold"><?= t('label_email') ?></label>
                        <input class="input w-full text-sm md:text-base" type="email" name="email" id="email"
                               value="<?= htmlspecialchars($prefillEmail, ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="reg_code" class="text-sm md:text-base font-semibold">Regisztrációs kód</label>
                        <input class="input w-full text-sm md:text-base" type="text" name="reg_code" id="reg_code" required>
                    </div>
                    <div>
                        <label for="password1" class="text-sm md:text-base font-semibold"><?= t('label_password') ?></label>
                        <div class="relative">
                            <input class="input w-full text-sm md:text-base pr-12" type="password" name="password1" id="password1" required>
                            <button type="button" class="toggle-pass absolute right-2 top-1/2 -translate-y-1/2 text-sm opacity-80 hover:opacity-100"
                                    data-target="password1" aria-label="Jelszó megtekintése">
                                👁
                            </button>
                        </div>
                        <div class="mt-2">
                            <div class="h-2 w-full bg-white/10 rounded">
                                <div id="pw_bar" class="h-2 rounded w-0 bg-white/60 transition-all"></div>
                            </div>
                            <p id="pw_label" class="text-xs opacity-80 mt-1">Jelszó erőssége: —</p>
                            <ul id="pw_hints" class="text-xs opacity-70 mt-1 list-disc pl-5">
                                <li>Min 8 karakter</li>
                                <li>Kisbetű + nagybetű + szám</li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <label for="password2" class="text-sm md:text-base font-semibold"><?= t('label_password_again') ?></label>
                        <div class="relative">
                            <input class="input w-full text-sm md:text-base pr-12" type="password" name="password2" id="password2" required>
                            <button type="button" class="toggle-pass absolute right-2 top-1/2 -translate-y-1/2 text-sm opacity-80 hover:opacity-100"
                                    data-target="password2" aria-label="Jelszó megtekintése">
                                👁
                            </button>
                        </div>
                        <p id="pw_match" class="text-xs opacity-80 mt-2"></p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="auth-note text-sm md:text-base p-3 bg-white/5 rounded-lg">
                            <strong><?= t('auth_security_question_label') ?></strong><br>
                            <?= htmlspecialchars($selected_question) ?>
                        </p>
                        <input type="hidden" name="security_question" value="<?= htmlspecialchars($selected_question) ?>">
                    </div>
                    <div class="md:col-span-2">
                        <label for="security_answer" class="text-sm md:text-base font-semibold"><?= t('auth_security_answer_label') ?></label>
                        <input class="input w-full text-sm md:text-base" type="text" name="security_answer" id="security_answer" required>
                    </div>
                </div>
                <div class="auth-actions flex flex-col gap-3 mt-5">
                    <button class="btn-cta w-full text-sm md:text-base" type="submit" name="reg-btn"><?= t('auth_btn_register') ?></button>
                </div>
                <p class="auth-note text-sm md:text-base text-center mt-5">
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
<?php include 'assets/php/footer.php'; ?>
</body>
</html>