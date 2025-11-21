<?php
    require "assets/php/db.php";
    $notify_number = 0;
    require "assets/php/lang.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
    }

    $profileId = isset($_GET['userid']) ? (int)$_GET['userid'] : 0;
    if ($profileId <= 0) {
        exit(t('msg_invalid_profile_id'));
    }

    $res = $conn->query("SELECT * FROM users WHERE id={$profileId} LIMIT 1");
    if (!$res || $res->num_rows === 0) {
        exit(t('msg_profile_not_found'));
    }
    $profile = $res->fetch_assoc();

    $viewerId = (int)$_COOKIE['id'];
    $isOwner  = ($viewerId === (int)$profile['id']);

    $profile_picture_path = "assets/img/default_profile_picture.jpg";
    if (!empty($profile['username']) && !empty($profile['profile_picture'])) {
        $fsPath     = __DIR__ . "/users/{$profile['username']}/{$profile['profile_picture']}";
        $publicPath = "users/{$profile['username']}/{$profile['profile_picture']}";
        if (is_file($fsPath)) {
            $profile_picture_path = $publicPath;
        }
    }

    $is_birthday = false;
    if (!empty($profile['birthdate'])) {
        $is_birthday = (date('m-d', strtotime($profile['birthdate'])) === date('m-d'));
    }

    if ($isOwner && isset($_POST['pfp-btn']) && isset($_FILES['profile_picture'])) {
        $file_name  = basename($_FILES['profile_picture']['name']);
        $tmp_name   = $_FILES['profile_picture']['tmp_name'];
        $target_dir = __DIR__ . "/users/" . $profile['username'] . "/";
        $target_file = $target_dir . $file_name;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (!empty($profile['profile_picture'])) {
            $old_file = $target_dir . $profile['profile_picture'];
            if (is_file($old_file)) {
                @unlink($old_file);
            }
        }

        if (is_uploaded_file($tmp_name) && move_uploaded_file($tmp_name, $target_file)) {
            $safeFile = $conn->real_escape_string($file_name);
            $conn->query("UPDATE users SET profile_picture='{$safeFile}' WHERE id={$viewerId} LIMIT 1");
            header("Location: profile.php?userid=".$viewerId);
            exit;
        } else {
            echo "<script>alert('".t('msg_file_upload_error')."');</script>";
        }
    }

    if ($isOwner && isset($_POST['edit-email-btn'])) {
        header("Location: edit_email.php?userid=" . $profile['id']);
        exit;
    }

    $notify_number = 0;
    $nf = $conn->query("SELECT id FROM notifys WHERE toid={$profile['id']} AND readed=0");
    if ($nf) { $notify_number = (int)$nf->num_rows; }

?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title><?= t('profile_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyai Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.aurora.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="main">
    <h1><?= htmlspecialchars($profile['firstname']) . ' ' . t('profile_of') ?></h1>
    <div class="home-grid">
        <aside class="content-aside">
            <div class="card profile-center">
                <div class="avatar-wrap <?= $is_birthday ? 'is-birthday' : '' ?>" style="--avatar-size:180px">
                    <div class="avatar-box">
                        <img class="profile-picture"
                             src="<?= htmlspecialchars($profile_picture_path) ?>"
                             alt="<?= t('profile_picture_alt') ?>">
                        <?php if ($is_birthday): ?>
                            <svg class="avatar-ring" viewBox="0 0 200 200" aria-hidden="true" focusable="false" preserveAspectRatio="xMidYMid meet">
                                <defs>
                                    <linearGradient id="ringGradient" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%"  stop-color="#ff6ec7"/>
                                        <stop offset="50%" stop-color="#7cf3ff"/>
                                        <stop offset="100%" stop-color="#ffd166"/>
                                    </linearGradient>
                                    <path id="starPath" d="M5 0 L6.5 3.2 L10 3.6 L7.5 5.8 L8.2 9 L5 7.3 L1.8 9 L2.5 5.8 0 3.6 3.5 3.2 Z" />
                                </defs>
                                <circle cx="100" cy="100" r="86" fill="none" stroke="url(#ringGradient)"
                                        stroke-width="10" stroke-linecap="round" stroke-dasharray="40 18 10 18"/>
                                <g class="ring-stars">
                                    <use href="#starPath" transform="translate(100,12) scale(1.4)" />
                                    <use href="#starPath" transform="translate(176,100) rotate(72) scale(1.2)" />
                                    <use href="#starPath" transform="translate(100,188) rotate(144) scale(1.3)" />
                                    <use href="#starPath" transform="translate(24,100) rotate(216) scale(1.1)" />
                                    <use href="#starPath" transform="translate(160,52) rotate(288) scale(1.0)" />
                                </g>
                            </svg>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($is_birthday && $isOwner): ?>
                    <div class="bday-banner" role="status" aria-live="polite">
                        <span class="bday-emoji" aria-hidden="true">🎂</span>
                        <div class="bday-text">
                            <strong><?= t('bday_title') ?> <?= htmlspecialchars($profile['firstname']) ?>!</strong>
                            <?= t('bday_message') ?>
                        </div>
                    </div>
                <?php endif; ?>
                <h2 class="profile-name"><?= htmlspecialchars($profile['lastname'] . ' ' . $profile['firstname']) ?></h2>
                <p class="entry-meta profile-username">@<?= htmlspecialchars($profile['username']) ?></p>
                <?php if ($isOwner): ?>
                    <div class="profile-actions">
                        <form method="POST" enctype="multipart/form-data">
                            <label for="profile_picture" class="btn-ghost"><?= t('btn_upload_profile_pic') ?></label>
                            <input type="file" name="profile_picture" id="profile_picture" accept="image/*" style="display:none" onchange="this.form.submit()">
                            <input type="hidden" name="pfp-btn" value="1">
                        </form>
                        <form method="POST">
                            <button type="submit" name="edit-email-btn" class="btn-ghost">
                                <?= t('btn_edit_email') ?>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card">
                <h3><?= t('profile_data') ?></h3>
                <div class="profile-info-card">
                    <div class="profile-info-item">
                        <div class="profile-info-label"><?= t('profile_fullname') ?></div>
                        <div class="profile-info-value">
                            <?= htmlspecialchars($profile['lastname'] . ' ' . $profile['firstname']) ?>
                        </div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-label"><?= t('profile_username') ?></div>
                        <div class="profile-info-value">@<?= htmlspecialchars($profile['username']) ?></div>
                    </div>
                    <?php if ($isOwner): ?>
                        <div class="profile-info-item">
                            <div class="profile-info-label">Email</div>
                            <div class="profile-info-value"><?= htmlspecialchars($profile['email']) ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label"><?= t('profile_registration') ?></div>
                            <div class="profile-info-value"><?= htmlspecialchars($profile['registration_date']) ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!$isOwner):
                $frq = $conn->query(
                        "SELECT * FROM friends 
                     WHERE (fromid={$viewerId} AND toid={$profileId})
                        OR (fromid={$profileId} AND toid={$viewerId})
                     LIMIT 1"
                );
                ?>
                <div class="card">
                    <h3><?= t('profile_friendship') ?></h3>
                    <?php if ($frq && $frq->num_rows > 0):
                        $friendship = $frq->fetch_assoc(); ?>
                        <p class="entry-meta">
                            <?php
                            if ((int)$friendship['status'] === 1) {
                                echo t('friend_status_friends');
                            } elseif ((int)$friendship['fromid'] === $viewerId) {
                                echo t('friend_status_sent_by_you');
                            } else {
                                echo t('friend_status_sent_to_you');
                            }
                            ?>
                        </p>
                    <?php else: ?>
                        <form method="post" action="assets/php/add_friend.php">
                            <input type="hidden" name="toid" value="<?= $profileId ?>">
                            <button type="submit" class="btn-cta"><?= t('btn_add_friend') ?></button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </aside>
        <section class="content-main">
            <div class="section-titlebar">
                <h3><?= t('profile_uploaded_files') ?></h3>
            </div>
            <?php
            $files = $conn->query("SELECT * FROM files WHERE uploaded_by={$profile['id']} ORDER BY id DESC");
            if ($files && $files->num_rows > 0): ?>
                <div class="content-grid grid-large">
                    <?php while ($file = $files->fetch_assoc()):
                        $uploader_q = $conn->query("SELECT username FROM users WHERE id=".(int)$file['uploaded_by']." LIMIT 1");
                        $uploader   = $uploader_q ? $uploader_q->fetch_assoc() : ['username' => 'ismeretlen'];
                        $ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
                        ?>
                        <article class="card">
                            <header class="card-head">
                                <h4 class="entry-title"><?= htmlspecialchars($file['name']) ?></h4>
                                <a class="note-desc-btn" href="note.php?id=<?= (int)$file['id'] ?>">
                                    <?= t('btn_details') ?>
                                </a>
                                <a class="entry-download-btn" href="assets/php/download.php?id=<?= (int)$file['id'] ?>">
                                    <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 3v10m0 0l-4-4m4 4l4-4M4 17v3h16v-3"
                                              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                    <?= t('btn_download') ?>
                                </a>
                            </header>
                            <?php if (!empty($file['subject'])): ?>
                                <p class="entry-meta">
                                    <?= t('label_subject') ?>
                                    <strong><?= htmlspecialchars($file['subject']) ?></strong>
                                </p>
                            <?php endif; ?>
                            <p><?= htmlspecialchars($file['description'] ?? '') ?></p>
                            <?php
                            $safe_path = "users/".htmlspecialchars($uploader['username'])."/".htmlspecialchars($file['file_name']);
                            if ($ext === 'docx'): ?>
                                <p><b><?= t('docx_warning') ?></b></p>
                            <?php elseif ($ext === 'mp4'): ?>
                                <video controls class="file-preview">
                                    <source src="<?= $safe_path ?>" type="video/mp4">
                                </video>
                            <?php elseif ($ext === 'pdf'): ?>
                                <iframe src="<?= $safe_path ?>" width="100%" height="500"></iframe>
                            <?php endif; ?>
                            <?php if (!empty($file['tags'])): ?>
                                <p class="entry-meta">
                                    <?= t('label_tags') ?>
                                    <?= htmlspecialchars($file['tags']) ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($isOwner && (int)$file['uploaded_by'] === $viewerId): ?>
                                <form method="POST" action="assets/php/delete.php">
                                    <input type="hidden" name="file_id" value="<?= (int)$file['id'] ?>">
                                    <button type="submit" class="entry-download-btn">
                                        <?= t('btn_delete_file') ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="card">
                    <p><?= t('empty_no_files') ?></p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
