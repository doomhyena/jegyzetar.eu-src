<?php 

    require  "assets/php/db.php";

    if(!isset($_COOKIE['id'])){
        header("Location: reglog.php");
    }

    $sql = "SELECT * FROM users WHERE id='" . $_COOKIE['id'] . "'";
    $found_user = $conn->query($sql);
    $user = $found_user->fetch_assoc();

    if (isset($_POST['send_message'])) {
        $toid = $_POST['toid'];
        
        if (empty($_POST['message'])) {
            echo "<script>alert('Az üzenet nem lehet üres!');</script>";
        } else {
            $message = $_POST['message'];
            $fromid = $_COOKIE['id'];
                
            $sql = "INSERT INTO messages (fromid, toid, content, sent_at) VALUES ($fromid, $toid, '$message', NOW())";
                
            if ($conn->query($sql)) {
                header("Location: messages.php?friendid=$toid");
            } else {
                echo "Hiba történt az üzenet küldésekor: " . $conn->error;
            }
        }
    }
	
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title>Üzenetek</title>
    <meta charset="UTF-8">
    <meta name="description" content="Iskolai jegyzeteket megosztó oldal">
    <meta name="keywords" content="iskola, jegyzet, megosztás, tanulás">
    <meta name='author' content='Baranyai Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.aurora.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="assets/js/script.js"></script>
</head>
<body>
    <?php include 'assets/php/navbar.php'; ?>
    
    <div class="main">
        <h1>Üzenetek</h1>
        
        <div class="home-grid">
            <aside class="content-aside">
                <div class="card">
                    <h3>Barátaid</h3>
                    <div class="list-compact">
                        <?php
                        $query = "SELECT * FROM friends WHERE (fromid=" . intval($_COOKIE['id']) . " AND status=1) OR (toid=" . intval($_COOKIE['id']) . " AND status=1)";
                        $found_friends = $conn->query($query);
                        
                        if($found_friends && $found_friends->num_rows > 0):
                            while ($friendship = $found_friends->fetch_assoc()):
                                $friendid = ($friendship['fromid'] != $_COOKIE['id']) ? $friendship['fromid'] : $friendship['toid'];
                                $query = "SELECT * FROM users WHERE id=$friendid";
                                $found_friend = $conn->query($query);
                                $friend = $found_friend->fetch_assoc();
                                
                                $active = (isset($_GET['friendid']) && $_GET['friendid'] == $friendid) ? 'style="border-color: var(--primary);"' : '';
                        ?>
                                <a href='messages.php?friendid=<?= $friendid ?>' class="mini-card" <?= $active ?>>
                                    <div class="mini-main">
                                        <h4 class="mini-title"><?= htmlspecialchars($friend['username']) ?></h4>
                                    </div>
                                </a>
                        <?php 
                            endwhile;
                        else:
                            echo "<p>Még nincsenek barátaid.</p>";
                        endif;
                        ?>
                    </div>
                </div>
            </aside>
            
            <section class="content-main">
                <?php if (isset($_GET['friendid'])): 
                    $friendid = $_GET['friendid'];
                    $query = "SELECT * FROM users WHERE id=$friendid";
                    $found_friend = $conn->query($query);
                    $friend = $found_friend->fetch_assoc();
                ?>
                    <div class="card">
                        <h2><?= htmlspecialchars($friend['username']) ?></h2>
                        <div id='message-container' style="max-height: 500px; overflow-y: auto; margin: 18px 0;">
                            <?php include 'assets/php/loadmessages.php'; ?>
                        </div>
                        
                        <form method="post" action="?friendid=<?= $friendid ?>" class="filters-inner" style="margin-top: 12px;">
                            <input type="hidden" name="toid" value="<?= $friendid ?>">
                            <input class="input" type="text" name="message" placeholder="Írj egy üzenetet..." required>
                            <button type="submit" name="send_message" class="btn-cta">Küldés</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <p>Válassz egy barátot az üzenetküldéshez.</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
    
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>