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
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body class="no-ads admin-page">
<?php include 'assets/php/navbar.php'; ?>
<div class="main w-full max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6">
    <h1 class="text-2xl md:text-3xl lg:text-4xl mb-6"><?= t('messages_title') ?></h1>
    <div class="home-grid flex flex-col lg:flex-row gap-6">
        <aside class="content-aside w-full lg:w-80 flex-shrink-0">
            <div class="card p-4 md:p-6">
                <h3 class="text-xl md:text-2xl mb-4"><?= t('messages_friends_heading') ?></h3>
                <div class="list-compact flex flex-col gap-2">
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
                    <div class="mini-card flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 transition-colors <?= (isset($_GET['friendid']) && (int)$_GET['friendid'] === $friendid) ? 'bg-white/10' : '' ?>">
                        <a href="messages.php?friendid=<?= $friendid ?>" class="flex-shrink-0">
                            <img src="<?=  $user_picture_path?>" alt="avatar" class="avatar-sm w-10 h-10 md:w-12 md:h-12 rounded-full object-cover">
                        </a>
                        <div class="friend-info flex-1 min-w-0">
                            <a href="profile.php?user=<?= urlencode($friend['username']) ?>"  class="friend-username text-sm md:text-base truncate block hover:underline">
                                @<?= htmlspecialchars($friend['username']) ?>
                            </a>
                        </div>
                    </div>
                     <?php
                            endwhile;
                        else:
                            echo "<p class='text-sm md:text-base'>".t('messages_no_friends')."</p>";
                        endif;
                    ?>
                </div>
            </div>
        </aside>
        <section class="content-main flex-1 min-w-0">
            <?php if (isset($_GET['friendid'])):
                $friendid = (int)$_GET['friendid'];
                $found_friend = db_query( $conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$friendid]);
                $friend = $found_friend ? $found_friend->fetch_assoc() : null;
            ?>
                <?php if ($friend): ?>
                <div class="card p-4 md:p-6">
                    <div class="message-header flex items-center gap-3 mb-4 pb-4 border-b border-white/10">
                        <img src="<?=  $user_picture_path ?>" class="avatar-md w-12 h-12 md:w-14 md:h-14 rounded-full object-cover" alt="avatar">
                            <a href="profile.php?user=<?= urlencode($friend['username']) ?>"
                               class="message-username text-lg md:text-xl font-semibold hover:underline">
                                @<?= htmlspecialchars($friend['username']) ?>
                            </a>
                    </div>
                    <div id="message-container" class="max-h-[400px] md:max-h-[500px] overflow-y-auto my-4 space-y-2">
                        <!-- üzenetek JS-sel töltődnek be -->
                    </div>
                    <form id="msg-send-form" class="filters-inner flex flex-col md:flex-row gap-3 mt-4" autocomplete="off">
                        <input type="hidden" name="toid" value="<?= $friendid ?>">
                        <input id="msg-input" class="input flex-1 text-sm md:text-base" type="text" name="message" placeholder="<?= t('messages_placeholder') ?>" required autocomplete="off">
                        <button type="submit" class="btn-cta text-sm md:text-base flex-shrink-0">
                            <?= t('btn_send') ?>
                        </button>
                    </form>
                    <div id="msg-status" style="margin-top:6px; font-size:.85rem;"></div>
                </div>
            <?php else: ?>
                <div class="card p-6">
                    <p class="text-sm md:text-base"><?= t('messages_friend_not_found') ?></p>
                </div>
            <?php endif; ?>
            <?php else: ?>
                <div class="card p-6">
                    <p class="text-sm md:text-base"><?= t('messages_choose_friend') ?></p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>