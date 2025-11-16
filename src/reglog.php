<?php
    require "assets/php/db.php";

    $security_questions = [
            "Mi a kedvenc könyved?",
            "Mi volt az első háziállatod neve?",
            "Mi az édesanyád leánykori neve?",
            "Mi a születési városod?",
            "Mi a kedvenc ételed?"
    ];

    $selected_question = $security_questions[array_rand($security_questions)];

    if (isset($_POST['reg-btn'])) {
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $username = $_POST['username'];
        $birthdate = $_POST['birthdate'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $password = $_POST['password1'];
        $passwordtwo = $_POST['password2'];
        $registration_date = date('Y-m-d H:i:s');
        $security_question = $_POST['security_question'];
        $security_answer = $_POST['security_answer'];

        $sql = "SELECT * FROM users WHERE username='$username'";
        $found_user = $conn->query($sql);

        if (mysqli_num_rows($found_user) == 0) {
            $sql = "SELECT * FROM users WHERE email='$email'";
            $found_email = $conn->query($sql);

            if (mysqli_num_rows($found_email) == 0) {
                if ($password == $passwordtwo) {
                    $titkositott_jelszo = password_hash($password, PASSWORD_DEFAULT);
                    $sql = $conn->query("INSERT INTO users (lastname, firstname, username, birthdate, gender, email, password, security_question, security_answer, registration_date) VALUES ('$lastname', '$firstname', '$username', '$birthdate', '$gender', '$email', '$titkositott_jelszo', '$security_question', '$security_answer', '$registration_date')");

                    $folder = getcwd();
                    $path = $folder . DIRECTORY_SEPARATOR . "users" . DIRECTORY_SEPARATOR . $username;
                    if (!is_dir($path) && mkdir($path, 0777, true)) {
                        echo "<script>alert('Tárhely sikeresen létrehozva!')</script>";
                    } else {
                        echo "<script>alert('Nem sikerült létrehozni a tárhelyet!')</script>";
                    }
                } else {
                    echo "<script>alert('A jelszavak nem egyeznek!')</script>";
                }
            } else {
                echo "<script>alert('Már létezik ilyen email cím!')</script>";
            }
        } else {
            echo "<script>alert('Már létezik ilyen felhasználó!')</script>";
        }
    }

    if (isset($_POST['login-btn'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM users WHERE username='$username'";
        $found_user = $conn->query($sql);

        if (mysqli_num_rows($found_user) > 0) {
            $user = $found_user->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                setcookie("id", $user['id'], time() + 3600, "/");
                header("Location: index.php");
                exit;
            } else {
                echo "<script>alert('Hibás jelszó!')</script>";
            }
        } else {
            echo "<script>alert('Nincs ilyen felhasználó!')</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title>Bejelentkezés</title>
    <meta charset="UTF-8">
    <meta name="description" content="Iskolai jegyzeteket megosztó oldal">
    <meta name="keywords" content="iskola, jegyzet, megosztás, tanulás">
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
            <h1>Üdvözlünk a Jegyzetár rendszerében! </h1>
            <p class="auth-note">Jelentkezz be vagy hozz létre új fiókot az induláshoz.</p>
        </div>
        <div class="auth-grid">
            <form class="auth-card" id="login" method="post" style="<?= $currentForm==='login'?'':'display:none;' ?>">
                <h1>Bejelentkezés</h1>
                <label for="login_username">Felhasználónév</label>
                <input class="input" type="text" name="username" id="login_username" required>
                <label for="login_password">Jelszó</label>
                <input class="input" type="password" name="password" id="login_password" required>
                <div class="auth-actions" style="margin-top:12px;">
                    <button class="btn-cta" type="submit" name="login-btn">Belépés</button>
                    <a class="btn-ghost" href="forgotpass.php">Elfelejtetted a jelszavad?</a>
                </div>
                <p class="auth-note" style="margin-top:16px;">Még nincs fiókod? <a class="switcher" href="#" data-switch="reg">Regisztrálj!</a></p>
            </form>
            <form class="auth-card" id="reg" method="post" style="<?= $currentForm==='reg'?'':'display:none;' ?>">
                <h1>Regisztráció</h1>
                <label for="lastname">Vezetéknév</label>
                <input class="input" type="text" name="lastname" id="lastname" required>
                <label for="firstname">Keresztnév</label>
                <input class="input" type="text" name="firstname" id="firstname" required>
                <label for="username">Felhasználónév</label>
                <input class="input" type="text" name="username" id="username" required>
                <label for="birthdate">Születési dátum</label>
                <input class="input" type="date" name="birthdate" id="birthdate" required>
                <label for="gender">Nem</label>
                <select class="select" name="gender" id="gender" required>
                    <option value="male">Férfi</option>
                    <option value="female">Nő</option>
                    <option value="other">Egyéb</option>
                </select>
                <label for="email">Email</label>
                <input class="input" type="email" name="email" id="email" required>
                <label for="password1">Jelszó</label>
                <input class="input" type="password" name="password1" id="password1" required>
                <label for="password2">Jelszó újra</label>
                <input class="input" type="password" name="password2" id="password2" required>
                <p class="auth-note"><strong>Biztonsági kérdés:</strong> <?= htmlspecialchars($selected_question) ?></p>
                <input type="hidden" name="security_question" value="<?= htmlspecialchars($selected_question) ?>">
                <label for="security_answer">Válasz</label>
                <input class="input" type="text" name="security_answer" id="security_answer" required>
                <div class="auth-actions" style="margin-top:12px;">
                    <button class="btn-cta" type="submit" name="reg-btn">Regisztráció</button>
                </div>
                <p class="auth-note" style="margin-top:16px;">Már van fiókod? <a class="switcher" href="#" data-switch="login">Lépj be!</a></p>
            </form>
            <div class="auth-actions" style="margin-top:12px;">
                <a class="btn-ghost disabled" href="oauth/discord-login.php">Folytatás Discorddal</a>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/script.js"></script>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
