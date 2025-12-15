<?php
    require "assets/php/db.php";
    require "assets/php/lang.php";
    require_once "assets/php/functions.php";

    if (!isset($_COOKIE['id']) || !ctype_digit($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $uid = (int)$_COOKIE['id'];

    $found_user = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$uid]);
    $user = $found_user ? $found_user->fetch_assoc() : null;

    if (!$user) {
        exit('A felhasználó nem található.');
    }

    $founded_notify = db_query($conn, "SELECT * FROM notifys WHERE toid = ? AND readed = 0", "i", [$user['id']]);
    $notify_number = $founded_notify ? $founded_notify->num_rows : 0;

    if (isset($_POST['group_invite_accept'])) {

        $ertesites_id = (int)($_POST['notif_id'] ?? 0);
        $csoport_id = (int)($_POST['group_id'] ?? 0);

        if ($csoport_id > 0) {
            $csoport_lekerdezes = db_query($conn, "SELECT * FROM groups WHERE id = ? LIMIT 1", "i", [$csoport_id]);

            if ($csoport_lekerdezes && $csoport_lekerdezes->num_rows > 0) {

                $tagsag_ellenorzes = db_query($conn, "SELECT id FROM group_members  WHERE group_id = ? AND user_id = ? LIMIT 1", "ii", [$csoport_id, $user['id']]
                );

                if (!$tagsag_ellenorzes || $tagsag_ellenorzes->num_rows == 0) {
                    db_exec($conn, "INSERT INTO group_members (group_id, user_id, role, status) VALUES (?, ?, 'member', 'accepted')", "ii", [$csoport_id, $user['id']]);
                }
            }
        }

        if ($ertesites_id > 0) {
            db_exec($conn, "DELETE FROM notifys WHERE id = ?", "i", [$ertesites_id]);
        }

        header("Location: notify.php");
        exit;
    }

    if (isset($_POST['group_invite_decline'])) {

        $ertesites_id = (int)($_POST['notif_id'] ?? 0);

        if ($ertesites_id > 0) {
            db_exec($conn, "DELETE FROM notifys WHERE id = ?","i", [$ertesites_id]);
        }

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
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
<?php
    include 'assets/php/navbar.php';

    if (isset($_POST['del-notifs-btn'])) {
        db_exec($conn, "DELETE FROM notifys WHERE toid = ?", "i", [$user['id']]);
        header("Location: notify.php");
        exit;
    }

    $founded_notifys = db_query($conn, "SELECT * FROM notifys WHERE toid = ? ORDER BY id DESC", "i", [$user['id']]);
?>
<div class="main">
    <h1><?= t('notify_title') ?></h1>
    <?php if ($founded_notifys && $founded_notifys->num_rows > 0): ?>
        <div class="content-grid">
            <?php while ($ertesites = $founded_notifys->fetch_assoc()):
                $from = (int)$ertesites['fromid'];
                $founded_notifyer = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$from]);
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
                        $check = db_query($conn, "SELECT * FROM friends   WHERE fromid = ?     AND toid   = ?     AND status = 0  LIMIT 1", "ii", [$notifyer['id'], $user['id']]);
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
                        if ($csoport_id > 0) {
                            $csoport_lekerdezes = db_query($conn, "SELECT * FROM groups WHERE id = ? LIMIT 1", "i", [$csoport_id]);
                            if ($csoport_lekerdezes && $csoport_lekerdezes->num_rows > 0) {
                                $csoport_adat = $csoport_lekerdezes->fetch_assoc();
                            }
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
        db_exec($conn, "UPDATE notifys SET readed = 1 WHERE toid = ?", "i", [$user['id']]);
    ?>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
