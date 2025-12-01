<?php
    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once 'assets/php/functions.php';

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $uid = (int)$_COOKIE['id'];

    // aktuális user lekérése
    $sql = "SELECT * FROM users WHERE id = {$uid} LIMIT 1";
    $found_user = $conn->query($sql);
    $user = $found_user ? $found_user->fetch_assoc() : null;

    if (!$user) {
        exit('User not found.');
    }

    // értesítések száma a navbarhoz
    $notify_number = 0;
    if ($nf = $conn->query("SELECT id FROM notifys WHERE toid = {$user['id']} AND readed = 0")) {
        $notify_number = $nf->num_rows;
    }

    // üzenet küldés
    if (isset($_POST['send_message'])) {
        $toid = (int)($_POST['toid'] ?? 0);
        $message = trim($_POST['message'] ?? '');

        if ($toid <= 0) {
            // invalid friend id – most nem üzenünk külön hibát, csak nem küldünk
        } elseif ($message === '') {
            // üres üzenet
            echo "<script>alert('".t('msg_message_empty')."');</script>";
        } else {
            $fromid = $uid;
            $safeMessage = $conn->real_escape_string($message);

            $sql = "
                    INSERT INTO messages (fromid, toid, content, sent_at)
                    VALUES ({$fromid}, {$toid}, '{$safeMessage}', NOW())
                ";

            if ($conn->query($sql)) {
                header("Location: messages.php?friendid=".$toid);
                exit;
            } else {
                echo "<p class='entry-meta'>".t('msg_message_send_error')." ".$conn->error."</p>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title><?= t('messages_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyai Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="main">
    <h1><?= t('messages_title') ?></h1>

    <div class="home-grid">
        <aside class="content-aside">
            <div class="card">
                <h3><?= t('messages_friends_heading') ?></h3>
                <div class="list-compact">
                    <?php
                    $me = $uid;
                    $query = "
                            SELECT * FROM friends
                            WHERE (fromid = {$me} AND status = 1)
                               OR (toid   = {$me} AND status = 1)
                        ";
                    $found_friends = $conn->query($query);

                    if ($found_friends && $found_friends->num_rows > 0):
                        while ($friendship = $found_friends->fetch_assoc()):
                            $friendid = ($friendship['fromid'] != $me)
                                    ? (int)$friendship['fromid']
                                    : (int)$friendship['toid'];

                            $query = "SELECT * FROM users WHERE id = {$friendid} LIMIT 1";
                            $found_friend = $conn->query($query);
                            $friend = $found_friend ? $found_friend->fetch_assoc() : null;
                            if (!$friend) continue;

                            $active = (isset($_GET['friendid']) && (int)$_GET['friendid'] === $friendid)
                                    ? 'style="border-color: var(--primary);"'
                                    : '';
                            ?>
                            <a href="messages.php?friendid=<?= $friendid ?>" class="mini-card" <?= $active ?>>
                                <div class="mini-main">
                                    <h4 class="mini-title"><?= htmlspecialchars($friend['username']) ?></h4>
                                </div>
                            </a>
                        <?php
                        endwhile;
                    else:
                        echo "<p>".t('messages_no_friends')."</p>";
                    endif;
                    ?>
                </div>
            </div>
        </aside>
        <section class="content-main">
            <?php if (isset($_GET['friendid'])):
                $friendid = (int)$_GET['friendid'];
                $query = "SELECT * FROM users WHERE id = {$friendid} LIMIT 1";
                $found_friend = $conn->query($query);
                $friend = $found_friend ? $found_friend->fetch_assoc() : null;
                ?>
                <?php if ($friend): ?>
                <div class="card">
                    <h2><?= htmlspecialchars($friend['username']) ?></h2>
                    <div id="message-container" style="max-height: 500px; overflow-y: auto; margin: 18px 0;">
                        <?php include 'assets/php/loadmessages.php'; ?>
                    </div>
                    <form method="post"
                          action="?friendid=<?= $friendid ?>"
                          class="filters-inner"
                          style="margin-top: 12px;">
                        <input type="hidden" name="toid" value="<?= $friendid ?>">
                        <input class="input"
                               type="text"
                               name="message"
                               placeholder="<?= t('messages_placeholder') ?>"
                               required>
                        <button type="submit" name="send_message" class="btn-cta">
                            <?= t('btn_send') ?>
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="card">
                    <p><?= t('messages_friend_not_found') ?></p>
                </div>
            <?php endif; ?>
            <?php else: ?>
                <div class="card">
                    <p><?= t('messages_choose_friend') ?></p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
