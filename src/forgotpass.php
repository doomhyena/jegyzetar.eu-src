<!DOCTYPE html>
<html lang="hu">
   <head>
       <title>Elfelejtett Jelszó</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Iskolai jegyzeteket megosztó oldal'>
       <meta name='keywords' content='iskola, jegyzet, megosztás, tanulás'>
       <meta name='author' content='Baranyai Norbert, Csontos Kincső, Szekeres Levente'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
       <link rel="stylesheet" href="assets/css/styles.aurora.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
       <script src="assets/js/script.js"></script>
   </head>
   <body>
        <?php 
            require "assets/php/db.php";
        ?>
        
        <div class="main" style="max-width: 600px;">
            <?php
            if (isset($_POST['forg-btn'])) {
                $username = $_POST['username'];
                $security_answer = $_POST['security_answer'];
                $sql = "SELECT * FROM users WHERE username='$username'";
                $found_user = $conn->query($sql);

                if (mysqli_num_rows($found_user) > 0) {
                    $user = $found_user->fetch_assoc();

                    if ($security_answer == $user['security_answer']) {
                        echo "<h1>Új jelszó beállítása</h1>";
                        echo "<form class='card' method='post' action='forgotpass.php?userid=$user[id]'>";
                        echo '    <label for="password1">Új jelszó:</label>';
                        echo '    <input class="input" type="password" name="password1" placeholder="Jelszó" required>';
                        echo '    <label for="password2">Jelszó újra:</label>';
                        echo '    <input class="input" type="password" name="password2" placeholder="Jelszó újra" required>';
                        echo '    <button type="submit" name="new-pass-btn" class="btn-cta">Jelszó módosítása</button>';
                        echo '</form>';
                    } else {
                        echo "<script>alert('Helytelen biztonsági válasz!')</script>";
                        echo "<meta http-equiv='refresh' content='0;url=forgotpass.php'>";
                    }
                } else {
                    echo "<script>alert('Nincs ilyen felhasználó!')</script>";
                    echo "<meta http-equiv='refresh' content='0;url=forgotpass.php'>";
                }
            } else if (isset($_POST['new-pass-btn'])) {
                if (!isset($_GET['userid'])) {
                    echo "<script>alert('Hiányzó vagy érvénytelen felhasználó azonosító!')</script>";
                } else {
                    $userid = intval($_GET['userid']);

                    if ($_POST['password1'] == $_POST['password2']) {
                        $sql = "SELECT * FROM users WHERE id=$userid";
                        $found_user = $conn->query($sql);
                        $user = $found_user->fetch_assoc();

                        if ($_POST['password1'] != $user['password']) {
                            $password = $_POST['password1'];
                            $titkositott_jelszo = password_hash($password, PASSWORD_DEFAULT);
                            $conn->query("UPDATE users SET password='$titkositott_jelszo' WHERE id=$userid");

                            echo "<div class='card'>";
                            echo "<h3>Sikeres módosítás!</h3>";
                            echo "<p>A jelszavad sikeresen megváltozott!</p>";
                            echo "<a class='btn-cta' href='reglog.php'>Bejelentkezés</a>";
                            echo "</div>";
                        } else {
                            echo "<script>alert('Az új jelszavad nem egyezhet a régivel.')</script>";
                            echo "<meta http-equiv='refresh' content='0;url=forgotpass.php'>";
                        }
                    } else {
                        echo "<script>alert('A két jelszó nem egyezik!')</script>";
                        echo "<meta http-equiv='refresh' content='0;url=forgotpass.php'>";
                    }
                }
            } else {
                echo '<h1>Jelszó visszaállítása</h1>';
                echo '<form class="card" method="post">';
                echo '    <label for="username">Felhasználónév:</label>';
                echo '    <input class="input" type="text" name="username" placeholder="Felhasználónév" required>';
                echo '    <label for="security_answer">Biztonsági kérdés válasza:</label>';
                echo '    <input class="input" type="text" name="security_answer" placeholder="Válasz" required>';
                echo '    <button type="submit" name="forg-btn" class="btn-cta">Elküldés</button>';
                echo '    <a class="btn-ghost" href="reglog.php">Vissza a bejelentkezéshez</a>';
                echo '</form>';
            }
            ?>
        </div>

        <?php include 'assets/php/footer.php'; ?>
   </body>
</html>