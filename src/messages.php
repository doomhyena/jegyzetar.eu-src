<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once 'assets/php/functions.php';

    if (!isset($_COOKIE['id']) || !ctype_digit($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $uid = (int)$_COOKIE['id'];

    $found_user = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$uid]);
    $user = $found_user ? $found_user->fetch_assoc() : null;

    if (!$user) {
        exit('User not found.');
    }

    $notify_number = 0;
    $nf = db_query($conn, "SELECT id FROM notifys WHERE toid = ? AND readed = 0", "i", [$user['id']]);
    if ($nf) {
        $notify_number = $nf->num_rows;
    }

    if (isset($_POST['send_message'])) {
        $toid    = (int)($_POST['toid'] ?? 0);
        $message = trim($_POST['message'] ?? '');

        if ($toid <= 0) {
        } elseif ($message === '') {
            echo "<script>alert('".t('msg_message_empty')."');</script>";
        } else {
            $fromid = $uid;

            $ok = db_exec($conn, "INSERT INTO messages (fromid, toid, content, sent_at) VALUES (?, ?, ?, NOW())",  "iis", [$fromid, $toid, $message]);

            if ($ok > 0) {
                header("Location: messages.php?friendid=".$toid);
                exit;
            } else {
                echo "<p class='entry-meta'>".t('msg_message_send_error')."</p>";
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
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
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
                        $found_friends = db_query($conn, "SELECT * FROM friends WHERE (fromid = ? AND status = 1)     OR (toid   = ? AND status = 1)", "ii", [$me, $me]);

                        if ($found_friends && $found_friends->num_rows > 0):
                            while ($friendship = $found_friends->fetch_assoc()):
                                $friendid = ($friendship['fromid'] != $me) ? (int)$friendship['fromid']: (int)$friendship['toid'];

                                $found_friend = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$friendid]);
                                $friend = $found_friend ? $found_friend->fetch_assoc() : null;

                        if (!$friend) continue;
                            $active = (isset($_GET['friendid']) && (int)$_GET['friendid'] === $friendid) ? : '';
                            $user_picture_path = !empty($friend['profile_picture']) ? 'users/' . htmlspecialchars($friend['username']) . '/' . htmlspecialchars($friend['profile_picture'])  : 'assets/img/default_profile_picture.jpg';
                    ?>
                    <div class="mini-card <?= (isset($_GET['friendid']) && (int)$_GET['friendid'] === $friendid) ? 'active' : '' ?>">
                        <a href="messages.php?friendid=<?= $friendid ?>">
                            <img src="<?=  $user_picture_path?>" alt="avatar" class="avatar-sm">
                        </a>
                        <div class="friend-info">
                            <a href="profile.php?user=<?= urlencode($friend['username']) ?>"  class="friend-username">
                                @<?= htmlspecialchars($friend['username']) ?>
                            </a>
                        </div>
                    </div>
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
                $found_friend = db_query( $conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$friendid]);
                $friend = $found_friend ? $found_friend->fetch_assoc() : null;
            ?>
                <?php if ($friend): ?>
                <div class="card">
                    <div class="message-header">
                        <img src="<?=  $user_picture_path ?>" class="avatar-md" alt="avatar">
                            <a href="profile.php?user=<?= urlencode($friend['username']) ?>"
                               class="message-username">
                                @<?= htmlspecialchars($friend['username']) ?>
                            </a>
                    </div>
                    <div id="message-container" style="max-height: 500px; overflow-y: auto; margin: 18px 0;">
                        <?php include 'assets/php/loadmessages.php'; ?>
                    </div>
                    <form method="post" action="?friendid=<?= $friendid ?>" class="filters-inner" style="margin-top: 12px;">
                        <input type="hidden" name="toid" value="<?= $friendid ?>">
                        <input class="input" type="text" name="message" placeholder="<?= t('messages_placeholder') ?>" required>
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