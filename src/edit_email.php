<!DOCTYPE html>
<html lang="hu">
   <head>
       <title>Email Módosítás</title>
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
            require  "assets/php/db.php";
            require "assets/php/lang.php";
        ?>
        
        <div class="main" style="max-width: 600px;">
            <?php

            if (isset($_POST['forg-btn'])) { // Ellenőrzi, hogy elküldték-e az első űrlapot
                $username = $_POST['username']; // Felhasználónév lekérése az űrlapról
                $security_answer = $_POST['security_answer']; // Biztonsági válasz lekérése
                $sql = "SELECT * FROM users WHERE username='$username'"; // Felhasználó keresése adatbázisban
                $found_user = $conn->query($sql); // Lekérdezés futtatása

                if (mysqli_num_rows($found_user) > 0) { // Ha létezik ilyen felhasználó
                    $user = $found_user->fetch_assoc(); // Felhasználó adatainak lekérése

                    if ($security_answer == $user['security_answer']) { // Ha helyes a biztonsági válasz
                        echo "<h1>Új email cím</h1>";
                        echo "<form class='card' method='post' action='edit_email.php?userid=$user[id]'>";
                        echo '    <label for="email1">Új email cím:</label>';
                        echo '    <input class="input" type="email" name="email1" placeholder="Email" required>';
                        echo '    <label for="email2">Email cím újra:</label>';
                        echo '    <input class="input" type="email" name="email2" placeholder="Email újra" required>';
                        echo '    <button type="submit" name="new-email-btn" class="btn-cta">Email módosítása</button>';
                        echo '</form>';
                    } else {
                        echo "<script>alert('Helytelen biztonsági válasz!')</script>";
                        echo "<meta http-equiv='refresh' content='0;url=edit_email.php'>";
                    }
                } else {
                    // Hibajelzés, ha nincs ilyen felhasználó
                    echo "<script>alert('Nincs ilyen felhasználó!')</script>";
                }
            } else if (isset($_POST['new-email-btn'])) {
                if (!isset($_GET['userid'])) {
                    echo "<script>alert('Hiányzó vagy érvénytelen felhasználó azonosító!')</script>";
                    } else {
                        $userid = intval($_GET['userid']);

                        if ($_POST['email1'] == $_POST['email2']) {
                            $sql = "SELECT * FROM users WHERE id=$userid";
                            $found_user = $conn->query($sql);
                            $user = $found_user->fetch_assoc();

                            if ($_POST['email1'] != $user['email']) {
                                $email = $_POST['email1'];
                                $conn->query("UPDATE users SET email='$email' WHERE id=$userid");

                            echo "<div class='card'>";
                            echo "<h3>Sikeres módosítás!</h3>";
                            echo "<p>Az új email címed sikeresen megváltozott!</p>";
                            echo "<a class='btn-cta' href='index.php'>Vissza a főoldalra</a>";
                            echo "</div>";
                        } else {
                            echo "<script>alert('Az új email címed nem egyezhet a régivel.')</script>";
                            echo "<meta http-equiv='refresh' content='0;url=edit_email.php'>";
                        }
                    } else {
                        echo "<script>alert('A két email cím nem egyezik!')</script>";
                        echo "<meta http-equiv='refresh' content='0;url=edit_email.php'>";
                    }
                }
            } else {
                echo '<h1>Email cím módosítása</h1>';
                echo '<form class="card" method="post">';
                echo '    <label for="username">Felhasználónév:</label>';
                echo '    <input class="input" type="text" name="username" placeholder="Felhasználónév" required>';
                echo '    <label for="security_answer">Biztonsági kérdés válasza:</label>';
                echo '    <input class="input" type="text" name="security_answer" placeholder="Válasz" required>';
                echo '    <button type="submit" name="forg-btn" class="btn-cta">Elküldés</button>';
                echo '    <a class="btn-ghost" href="profile.php?userid='.$_COOKIE['id'].'">Vissza a profilhoz</a>';
                echo '</form>';
            }
            ?>
        </div>

        <?php include 'assets/php/footer.php'; ?>
   </body>
</html>
