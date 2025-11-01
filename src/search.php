<?php
    require  "assets/php/db.php";

    if(!isset($_COOKIE['id'])){
        header("Location: index.php");
    }

    $sql = "SELECT * FROM users WHERE id='" . $_COOKIE['id'] . "'";
    $found_user = $conn->query($sql);
    $user = $found_user->fetch_assoc();

    $sql = "SELECT * FROM notifys WHERE toid = $user[id] AND readed = 0";
    $founded_notify = $conn->query($sql);
    $notify_number = mysqli_num_rows($founded_notify);
?>
<!DOCTYPE html>
<html lang="hu">
   <head>
       <title>Keresés</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Iskolai jegyzeteket megosztó oldal'>
       <meta name='keywords' content='iskola, jegyzet, megosztás, tanulás'>
       <meta name='author' content='Csontos Kincső, Szekeres Levente'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
       <link rel="stylesheet" href="assets/css/styles.aurora.css">
	   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	   <script src="assets/js/script.js"></script>
   </head>
   <body>
        <?php include 'assets/php/navbar.php'; ?>
        <div class="main">
            <h1>Keresés</h1>
            <section class="filters card">
                <div class="filters-inner">
                    <input type="text" class="input" id="search-box" placeholder="Keresés jegyzet cím, tárgy vagy fájltípus alapján…">
                    <button class="btn-search" type="button">
                        <svg class="icon icon-search" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M15.5 15.5L21 21M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>Keresés</span>
                    </button>
                </div>
            </section>
            
            <div id="search" class="content-grid grid-large"></div>
        </div>
        <?php include 'assets/php/footer.php'; ?>
   </body>
</html>