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
        exit('User not found.');
    }

    $sql = "SELECT * FROM notifys WHERE toid = {$user['id']} AND readed = 0";
    $founded_notify = $conn->query($sql);
    $notify_number = $founded_notify ? $founded_notify->num_rows : 0;

	 // ==== Csoport meghívó elfogadása ====
    if (isset($_POST['group_invite_accept'])) {

        $ertesites_id = (int)$_POST['notif_id'];
        $csoport_id   = (int)$_POST['group_id'];

        if ($csoport_id > 0) {

            // létezik-e a csoport
            $csoport_lekerdezes = $conn->query("
                SELECT * FROM groups 
                WHERE id = $csoport_id
                LIMIT 1
            ");

            if ($csoport_lekerdezes && $csoport_lekerdezes->num_rows > 0) {

                // benne van-e már a group_members-ben
                $tagsag_ellenorzes = $conn->query("
                    SELECT id 
                    FROM group_members
                    WHERE group_id = $csoport_id
                      AND user_id = {$user['id']}
                    LIMIT 1
                ");

                if (!$tagsag_ellenorzes || $tagsag_ellenorzes->num_rows == 0) {
                    // ha még nem tag, most felvesszük
                    $conn->query("
                        INSERT INTO group_members (group_id, user_id, role, status)
                        VALUES ($csoport_id, {$user['id']}, 'member', 'accepted')
                    ");
                }
            }
        }

        // az értesítést töröljük
        $conn->query("DELETE FROM notifys WHERE id = $ertesites_id");

        header("Location: notify.php");
        exit;
    }

    // ==== Csoport meghívó elutasítása ====
    if (isset($_POST['group_invite_decline'])) {

        $ertesites_id = (int)$_POST['notif_id'];

        // csak töröljük az értesítést
        $conn->query("DELETE FROM notifys WHERE id = $ertesites_id");

        header("Location: notify.php");
        exit;
    }
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
                    <?php elseif ($ertesites['notifytype'] === 'group_invite'): ?>
                        <?php
                        $csoport_id = (int)$ertesites['group_id'];
                        $csoport_adat = null;
                        $csoport_lekerdezes = $conn->query("
                            SELECT * FROM groups 
                            WHERE id = $csoport_id
                            LIMIT 1
                        ");
                        if ($csoport_lekerdezes && $csoport_lekerdezes->num_rows > 0) {
                            $csoport_adat = $csoport_lekerdezes->fetch_assoc();
                        }
                        ?>
                        <h4 class="entry-title">Csoport meghívó</h4>
                        <?php if ($csoport_adat): ?>
                            <p>
                                <a class="uploader-name" href="profile.php?userid=<?= (int)$notifyer['id'] ?>">
                                    <?= htmlspecialchars($notifyer['username']) ?>
                                </a>
                                meghívott a(z)
                                <strong><?= htmlspecialchars($csoport_adat['name']) ?></strong>
                                csoportba.
                            </p>
                            <form method="post">
                                <input type="hidden" name="notif_id" value="<?= (int)$ertesites['id'] ?>">
                                <input type="hidden" name="group_id" value="<?= $csoport_id ?>">
                                <button type="submit" name="group_invite_accept" class="btn-cta">
                                    Meghívás elfogadása
                                </button>
                                <button type="submit" name="group_invite_decline" class="btn-ghost">
                                    Elutasítás
                                </button>
                            </form>
                        <?php else: ?>
                            <p>Ez a csoport már nem létezik.</p>
                        <?php endif; ?>

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