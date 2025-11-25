<?php
    require "assets/php/db.php";
    require "assets/php/lang.php";


    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $uid = (int)$_COOKIE['id'];

    $sql = "SELECT * FROM users WHERE id={$uid} LIMIT 1";
    $found_user = $conn->query($sql);
    $user = $found_user ? $found_user->fetch_assoc() : null;

    if (!$user) {
        // ha valami nagyon félremegy, inkább dobjunk hibát
        exit('User not found.');
    }

    $sql = "SELECT * FROM notifys WHERE toid = {$user['id']} AND readed = 0";
    $founded_notify = $conn->query($sql);
    $notify_number = $founded_notify ? $founded_notify->num_rows : 0;

?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title><?= t('notify_title') ?></title>
    <meta charset='UTF-8'>
    <meta name='description' content='<?= t('meta_description_home') ?>'>
    <meta name='keywords' content='<?= t('meta_keywords_home') ?>'>
    <meta name='author' content='Baranyai Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.aurora.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
<?php
include 'assets/php/navbar.php';

if (isset($_POST['del-notifs-btn'])) {
    $conn->query("DELETE FROM notifys WHERE toid = {$user['id']}");
    header("Location: notify.php");
    exit;
}

$sql = "SELECT * FROM notifys WHERE toid = {$user['id']} ORDER BY id DESC";
$founded_notifys = $conn->query($sql);
?>
<div class="main">
    <h1><?= t('notify_title') ?></h1>
    <?php if ($founded_notifys && $founded_notifys->num_rows > 0): ?>
        <div class="content-grid">
            <?php while ($ertesites = $founded_notifys->fetch_assoc()):
                $from = (int)$ertesites['fromid'];
                $sql = "SELECT * FROM users WHERE id={$from} LIMIT 1";
                $founded_notifyer = $conn->query($sql);
                $notifyer = $founded_notifyer ? $founded_notifyer->fetch_assoc() : null;
                if (!$notifyer) continue;
                ?>
                <article class="card">
                    <?php if ($ertesites['notifytype'] === "friend"): ?>
                        <h4 class="entry-title"><?= t('notif_friend_request_title') ?></h4>
                        <p>
                            <a class="uploader-name" href="profile.php?userid=<?= (int)$notifyer['id'] ?>">
                                <?= htmlspecialchars($notifyer['username']) ?>
                            </a>
                            <?= t('notif_friend_marked_you') ?>
                        </p>
                        <?php
                        $check = $conn->query(
                                "SELECT * FROM friends 
                                     WHERE fromid = {$notifyer['id']} 
                                       AND toid = {$user['id']} 
                                       AND status = 0
                                     LIMIT 1"
                        );
                        if ($check && $check->num_rows > 0): ?>
                            <form method="post" action="assets/php/accept_friend.php">
                                <input type="hidden" name="fromid" value="<?= (int)$notifyer['id'] ?>">
                                <button type="submit" class="btn-cta">
                                    <?= t('btn_accept_friend') ?>
                                </button>
                            </form>
                        <?php else: ?>
                            <p class="entry-meta"><?= t('notif_friend_already_processed') ?></p>
                        <?php endif; ?>
                    <?php elseif ($ertesites['notifytype'] === 'comment'): ?>
                        <h4 class="entry-title"><?= t('notif_new_comment_title') ?></h4>
                        <p>
                            <a class="uploader-name" href="profile.php?userid=<?= (int)$notifyer['id'] ?>">
                                <?= htmlspecialchars($notifyer['username']) ?>
                            </a>
                            <?= t('notif_comment_your_post') ?>
                        </p>
                    <?php endif; ?>
                </article>
            <?php endwhile; ?>
        </div>
        <form method="post" style="margin-top: 24px;">
            <button type="submit" name="del-notifs-btn" class="btn-ghost">
                <?= t('btn_delete_all_notifications') ?>
            </button>
        </form>
    <?php else: ?>
        <div class="card">
            <p><?= t('empty_no_notifications') ?></p>
        </div>
    <?php endif; ?>
    <?php
    $conn->query("UPDATE notifys SET readed = 1 WHERE toid = {$user['id']}");
    ?>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>