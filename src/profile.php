<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $viewerId = (int)$_COOKIE['id'];
    $username = $_GET['username'] ?? $_GET['user'] ?? '';

    if (!preg_match('/^[a-zA-Z0-9_.-]{3,32}$/', $username)) {
        http_response_code(400);
        exit(t('msg_invalid_profile'));
    }

    $res = db_query($conn, "SELECT * FROM users WHERE username = ? LIMIT 1", "s", [$username]);
    if (!$res || $res->num_rows === 0) {
        http_response_code(404);
        exit(t('msg_profile_not_found'));
    }

    $profile = $res->fetch_assoc();
    $profileId = $profile['id'];
    $isOwner = ($viewerId === $profileId);
    $show_fullname  = ($profile['show_fullname'] ?? 1);
    $show_email = ($profile['show_email'] ?? 0);
    $show_birthdate = ($profile['show_birthdate'] ?? 0);

    $nfRes = db_query($conn, "SELECT id FROM notifys WHERE toid = ? AND readed = 0", "i", [$profileId]);
    $notify_number = $nfRes ? (int)$nfRes->num_rows : 0;

    $profile_theme = $profile['profile_theme'] ?: 'default';
    $is_birthday = (!empty($profile['birthdate']) && date('m-d', strtotime($profile['birthdate'])) === date('m-d'));
    $birthdateValue = (!empty($profile['birthdate']) && $profile['birthdate'] !== '0000-00-00') ? substr($profile['birthdate'], 0, 10): '';

    $profileUpdateError = '';
    $profileUpdateSuccess = '';
    $backupCodesGenerated = [];
    if (!empty($_SESSION['profile_toast'])) {
        $profileUpdateSuccess = t($_SESSION['profile_toast']);
        unset($_SESSION['profile_toast']);
    }
    
    if (!empty($_SESSION['backup_codes_generated'])) {
        $backupCodesGenerated = $_SESSION['backup_codes_generated'];
        unset($_SESSION['backup_codes_generated']);
        unset($_SESSION['backup_codes_show']);
    }

    $friendship = null;
    if (!$isOwner) {
        $fsRes = db_query($conn, "SELECT * FROM friends WHERE (fromid = ? AND toid = ?) OR (fromid = ? AND toid = ?) LIMIT 1", "iiii", [$viewerId, $profileId, $profileId, $viewerId]);
        if ($fsRes && $fsRes->num_rows > 0) {
            $friendship = $fsRes->fetch_assoc();
        }
    }

    $profile_picture_path = "assets/img/default_profile_picture.jpg";
    if (!empty($profile['profile_picture'])) {
        $fs = __DIR__ . "/users/{$profile['username']}/{$profile['profile_picture']}";
        if (is_file($fs)) {
            $profile_picture_path = "users/{$profile['username']}/{$profile['profile_picture']}";
        }
    }

    $badges = [];
    $badgeRes = db_query($conn, "SELECT b.* FROM user_badges ub JOIN badges b ON ub.badge_id = b.id WHERE ub.user_id = ? ORDER BY ub.granted_at ASC", "i", [$profileId]);

    if ($badgeRes) {
        while ($row = $badgeRes->fetch_assoc()) {
            $badges[] = $row;
        }
    }

    $lastCssRequest = null;
    $cssResetDone = false;
    $cssRes = db_query($conn, "SELECT * FROM user_custom_css_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT 1", "i", [$profileId]);
    if ($cssRes && $cssRes->num_rows > 0) {
        $lastCssRequest = $cssRes->fetch_assoc();
    }

    $approvedCss = '';
    if ($lastCssRequest) {
        $status = strtolower((string)($lastCssRequest['status'] ?? ''));
        $cssVal  = (string)($lastCssRequest['css'] ?? '');
        if (!$cssResetDone && $status === 'approved' && trim($cssVal) !== '') {
            $approvedCss = $cssVal;
        }
    }

    if ($isOwner && isset($_POST['update-basic-profile'])) {
        $firstname = trim($_POST['firstname'] ?? $profile['firstname']);
        $lastname = trim($_POST['lastname'] ?? $profile['lastname']);
        $email = trim($_POST['email'] ?? $profile['email']);
        $birthdate = trim($_POST['birthdate'] ?? $profile['birthdate']);

        if ($firstname === '' || $lastname === '' || $email === '') {
            $profileUpdateError = t('error_all_fields_required');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $profileUpdateError = t('error_bad_email_format');
        } else {
            db_stmt($conn, "UPDATE users SET firstname=?, lastname=?, email=?, birthdate=? WHERE id=? LIMIT 1", "ssssi", [$firstname, $lastname, $email, $birthdate, $viewerId])->close();
            $_SESSION['profile_toast'] = 'msg_profile_update_success';
            header("Location: profile.php?user=" . urlencode($profile['username']));
            exit;
        }
    }
    if ($isOwner && isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture']['tmp_name'])) {
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
            db_stmt($conn, "UPDATE users SET profile_picture = ? WHERE id = ? LIMIT 1", "si", [$file_name, $viewerId])->close();
            header("Location: profile.php?user=" . urlencode($profile['username']));
            exit;
        } else {
            Message(t('msg_file_upload_error'));
        }
    }

    if ($isOwner && isset($_POST['save-profile-settings'])) {
        $bio   = trim($_POST['bio'] ?? '');
        $theme = $_POST['theme'] ?? $profile['profile_theme'];

        if (mb_strlen($bio, 'UTF-8') > 1500) {
            $profileUpdateError = t('error_bio_too_long');
        } else {
            db_stmt($conn, "UPDATE users SET bio = ?, profile_theme = ? WHERE id = ? LIMIT 1", "ssi", [$bio, $theme, $viewerId])->close();
            $_SESSION['profile_toast'] = 'msg_profile_update_success';
            header("Location: profile.php?user=" . urlencode($profile['username']));
            exit;
        }
    }

    if ($isOwner && isset($_POST['unlink-discord'])) {
        db_stmt($conn, "UPDATE users SET oauth_provider=NULL, oauth_sub=NULL WHERE id=? LIMIT 1", "i", [$viewerId])->close();
        $_SESSION['profile_toast'] = 'Discord fiók sikeresen leválasztva!';
        header("Location: profile.php?user=" . urlencode($profile['username']));
        exit;
    }

    if ($isOwner && isset($_POST['link-discord'])) {
        $_SESSION['oauth_link_mode'] = 1;
        header('Location: assets/oauth/discord-login.php');
        exit;
    }

    if ($isOwner && isset($_POST['toggle-2fa'])) {
        $enable2fa = isset($_POST['enable_2fa']) ? 1 : 0;
        db_stmt($conn, "UPDATE users SET twofa_enabled = ? WHERE id = ? LIMIT 1", "ii", [$enable2fa, $viewerId])->close();
        
        if ($enable2fa) {
            $backupCodes = generate_backup_codes($conn, $viewerId, 10);
            $_SESSION['backup_codes_show'] = true;
            $_SESSION['backup_codes_generated'] = $backupCodes;
        } else {
            db_exec($conn, "DELETE FROM 2fa_backup_codes WHERE userid = ?", "i", [$viewerId]);
            unset($_SESSION['backup_codes_show']);
            unset($_SESSION['backup_codes_generated']);
        }
        
        $_SESSION['profile_toast'] = $enable2fa ? 'msg_2fa_enabled' : 'msg_2fa_disabled';
        header("Location: profile.php?user=" . urlencode($profile['username']));
        exit;
    }

    if ($isOwner && (isset($_POST['submit-custom-css']) || isset($_POST['reset-custom-css']))) {
        $customCss = trim($_POST['custom_css'] ?? '');

        $isReset = isset($_POST['reset-custom-css']);
        if ($isReset) {
            $customCss = '';
        }

        if (mb_strlen($customCss, '8bit') > 20000) {
            $_SESSION['profile_toast'] = 'A CSS túl hosszú (max 20000 byte).';
            header("Location: profile.php?user=" . urlencode($profile['username']));
            exit;
        }

        try {
            if ($isReset) {
                db_stmt($conn, "INSERT INTO user_custom_css_requests (user_id, css, status, reviewed_at, reviewed_by) VALUES (?, ?, 'approved', NOW(), ?)", "isi", [$viewerId, $customCss, $viewerId])->close();
                db_stmt($conn, "UPDATE user_custom_css_requests SET status = 'rejected' WHERE user_id = ? AND status = 'pending'", "i", [$viewerId])->close();
                $_SESSION['profile_toast'] = t('msg_css_empty_reset');
            } else {
                db_stmt($conn, "INSERT INTO user_custom_css_requests (user_id, css) VALUES (?, ?)", "is", [$viewerId, $customCss])->close();
                $_SESSION['profile_toast'] = 'A CSS kérelmet elküldtük; várj admin jóváhagyásra.';
            }
        } catch (Throwable $e) {
            $_SESSION['profile_toast'] = 'Hiba történt a CSS mentésekor.';
        }

        header("Location: profile.php?user=" . urlencode($profile['username']));
        exit;
    }

    if ($isOwner && isset($_POST['save-visibility'])) {
        $sf = isset($_POST['show_fullname']) ? 1 : 0;
        $se = isset($_POST['show_email']) ? 1 : 0;
        $sb = isset($_POST['show_birthdate']) ? 1 : 0;

        db_stmt($conn, "UPDATE users SET show_fullname=?, show_email=?, show_birthdate=? WHERE id=? LIMIT 1","iiii",[$sf, $se, $sb, $viewerId])->close();

        $_SESSION['profile_toast'] = 'msg_profile_update_success';
        header("Location: profile.php?user=" . urlencode($profile['username']));
        exit;
    }

    if ($isOwner && isset($_POST['update-username'])) {
        $newUsername = trim($_POST['new_username'] ?? '');
        $oldUsername = (string)$profile['username'];

        if (!preg_match('/^[a-zA-Z0-9_.-]{3,32}$/', $newUsername)) {
            $profileUpdateError = t('msg_invalid_profile');
        } elseif (strcasecmp($newUsername, $oldUsername) === 0) {
            $profileUpdateError = 'Ugyanazt a felhasználónevet adtad meg.';
        } else {
            $check = db_query($conn, "SELECT id FROM users WHERE username = ? LIMIT 1", "s", [$newUsername]);
            if ($check && $check->num_rows > 0) {
                $profileUpdateError = 'Ez a felhasználónév már foglalt.';
            } else {
                $oldDir = __DIR__ . "/users/" . $oldUsername;
                $newDir = __DIR__ . "/users/" . $newUsername;

                $conn->begin_transaction();
                try {
                    db_stmt($conn, "UPDATE users SET username=? WHERE id=? LIMIT 1", "si", [$newUsername, $viewerId])->close();

                    if (is_dir($oldDir)) {
                        if (file_exists($newDir)) {
                            throw new Exception("A célmappa már létezik.");
                        }
                        if (!@rename($oldDir, $newDir)) {
                            throw new Exception("Nem sikerült átnevezni a user mappát.");
                        }
                    } else {
                        if (!is_dir($newDir)) {
                            @mkdir($newDir, 0777, true);
                        }
                    }

                    $conn->commit();
                    $_SESSION['profile_toast'] = 'msg_profile_update_success';
                    header("Location: profile.php?user=" . urlencode($newUsername));
                    exit;

                } catch (Throwable $e) {
                    $conn->rollback();
                    if (is_dir($newDir) && !is_dir($oldDir)) {
                        @rename($newDir, $oldDir);
                    }
                    $profileUpdateError = "Hiba a felhasználónév módosításakor: " . $e->getMessage();
                }
            }
        }
    }

    $bio = trim((string)($profile['bio'] ?? ''));
    $emptyBios = [
            "Ez a felhasználó még nem dobott be bemutatkozást.",
            "Itt lenne a bio… ha lenne bio.",
            "A bemutatkozás DLC még nem lett telepítve.",
            "Csend van… túl nagy a csend.",
            "Bio nincs, de a vibe megvan.",
    ];
    $fallbackBio = $emptyBios[array_rand($emptyBios)];

    $statUploads   = 0;
    $statDownloads = 0;
    $statFavorites = 0;
    $rUploads   = db_query($conn, "SELECT COUNT(*) AS c FROM files WHERE uploaded_by = ?", "i", [$profileId]);
    if ($rUploads)   $statUploads   = (int)($rUploads->fetch_assoc()['c'] ?? 0);
    $rDownloads = db_query($conn, "SELECT COALESCE(SUM(download_count),0) AS s FROM files WHERE uploaded_by = ?", "i", [$profileId]);
    if ($rDownloads) $statDownloads = (int)($rDownloads->fetch_assoc()['s'] ?? 0);
    $rFavs      = db_query($conn, "SELECT COUNT(*) AS c FROM favorites WHERE user_id = ?", "i", [$profileId]);
    if ($rFavs)      $statFavorites = (int)($rFavs->fetch_assoc()['c'] ?? 0);
    $statFriends = 0;
    $rFriends = db_query($conn, "SELECT COUNT(*) AS c FROM friends WHERE (fromid = ? OR toid = ?) AND status = 1", "ii", [$profileId, $profileId]);
    if ($rFriends) $statFriends = (int)($rFriends->fetch_assoc()['c'] ?? 0);

?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title><?= t('profile_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-iconre" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
    <?php if (!empty($approvedCss)): ?>
        <style id="profile-custom-css">
            <?= $approvedCss . "\n" ?>
        </style>
    <?php endif; ?>
</head>
<body class="theme-<?= htmlspecialchars($profile_theme) ?>">
<?php include 'assets/php/navbar.php'; ?>
<div class="content-wrapper">
    <?php include "assets/php/ads.php"; ?>
    <div class="main">
        <?php if (!empty($profileUpdateError)): ?>
            <div class="toast toast-error"><?= htmlspecialchars($profileUpdateError) ?></div>
        <?php elseif (!empty($profileUpdateSuccess)): ?>
            <div class="toast toast-success"><?= htmlspecialchars($profileUpdateSuccess) ?></div>
        <?php endif; ?>
        <?php if (!empty($backupCodesGenerated)): ?>
            <div style="margin: 20px 0; padding: 20px; border-radius: 14px; background: rgba(96, 165, 250, 0.12); border: 2px solid rgba(96, 165, 250, 0.35); color: #e5e7eb;">
                <h2 style="margin: 0 0 16px 0; color: #93c5fd; font-size: 18px;">✅ 2FA Backup Kódok - Megmentve!</h2>
                <p style="margin: 0 0 16px 0; color: #cbd5e1;">
                    Az alábbi <strong><?= count($backupCodesGenerated) ?></strong> backup kód segítségével tudsz bejelentkezni, ha nem fér hozzá az emailedhez.
                    <br><strong>Ezek a kódok csak most láthatóak! Másolhatod vagy letöltheted őket.</strong>
                </p>
                <div style="background: rgba(15, 23, 42, 0.6); padding: 16px; border-radius: 8px; margin-bottom: 16px; max-height: 300px; overflow-y: auto;">
                    <div style="font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.8; color: #60a5fa;">
                        <?php foreach ($backupCodesGenerated as $code): ?>
                            <div style="padding: 4px 8px; word-break: break-all;">
                                <span style="color: #93c5fd;">□</span> <?= htmlspecialchars($code) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <p style="margin: 12px 0 0 0; color: #fde68a; font-size: 13px;">
                    <strong>Fontos:</strong> Fordulj figyelmet arra, hogy ezek a kódok titkosak maradnak. Ne oszd meg őket senkivel!
                </p>
            </div>
        <?php endif; ?>
        <h1><?= htmlspecialchars($show_fullname ? $profile['firstname'] : '@' . $profile['username']) . ' ' . t('profile_of') ?></h1>
        <div class="profile-layout">
            <section class="card profile-header">
                <div class="profile-header-grid">
                    <div class="profile-header-avatar">
                        <div class="avatar-wrap <?= $is_birthday ? 'is-birthday' : '' ?>">
                            <div class="avatar-box">
                                <img class="profile-picture"
                                     src="<?= htmlspecialchars($profile_picture_path) ?>"
                                     alt="<?= t('profile_picture_alt') ?>">
                                <?php if ($is_birthday): ?>
                                    <svg class="avatar-ring" viewBox="0 0 200 200" aria-hidden="true">
                                        <defs>
                                            <linearGradient id="ringGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                                <stop offset="0%" stop-color="#ffdd55"/>
                                                <stop offset="50%" stop-color="#ff6ec7"/>
                                                <stop offset="100%" stop-color="#6ee7ff"/>
                                            </linearGradient>
                                            <path id="starPath" d="M 0 -12 L 3.5 -3.5 12 0 3.5 3.5 0 12 -3.5 3.5 -12 0 -3.5 -3.5 Z" />
                                        </defs>
                                        <circle cx="100" cy="100" r="86" fill="none" stroke="url(#ringGradient)" stroke-width="10" stroke-linecap="round" stroke-dasharray="40 18 10 18"/>
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
                        <?php if ($is_birthday): ?>
                            <div class="bday-banner" role="status" aria-live="polite">
                                <span class="bday-emoji" aria-hidden="true">🎂</span>
                                <div class="bday-text">
                                    <strong><?= t('bday_title') ?> <?= htmlspecialchars($show_fullname ? $profile['firstname'] : $profile['username']) ?>!</strong>
                                    <span><?= t('bday_message') ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-header-main">
                        <div class="profile-identity">
                            <h2 class="profile-name"><?= htmlspecialchars($show_fullname ? ($profile['lastname'] . ' ' . $profile['firstname']) : ('@' . $profile['username'])) ?></h2>
                            <?php if ($show_fullname): ?><span class="entry-meta profile-username">@<?= htmlspecialchars($profile['username']) ?></span><?php endif; ?>
                            <?php if (!empty($badges)): ?>
                                <div class="profile-badges">
                                    <?php foreach ($badges as $badge): ?>
                                        <span class="badge-pill"
                                              title="<?= htmlspecialchars($badge['description'] ?? '') ?>">
                                            <?= htmlspecialchars($badge['icon'] ?: '🏅') ?>
                                            <?= htmlspecialchars($badge['name']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="profile-badges">
                                <?php if ((int)($profile['admin'] ?? 0) === 1): ?>
                                    <span class="badge-pill badge-pill--admin" title="<?= t('role_badge_admin_title') ?>">
                                        👑 <?= t('role_badge_admin') ?>
                                    </span>
                                <?php elseif ((int)($profile['teacher'] ?? 0) === 1): ?>
                                    <span class="badge-pill badge-pill--teacher" title="<?= t('role_badge_teacher_title') ?>">
                                        🎓 <?= t('role_badge_teacher') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge-pill badge-pill--student" title="<?= t('role_badge_student_title') ?>">
                                        📚 <?= t('role_badge_student') ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php if ($isOwner): ?>
                                <div class="profile-actions inline" aria-label="Profil műveletek">
                                    <form method="post" enctype="multipart/form-data" id="pfp-form" style="display:none;">
                                        <input type="file" name="profile_picture" id="pfp-input" accept="image/png,image/jpeg,image/webp" required>
                                        <button type="submit" name="pfp-btn">Upload</button>
                                    </form>
                                    <button type="button" class="profile-action-btn" id="pfp-open-btn">Feltöltés</button>
                                    <script>
                                        document.getElementById('pfp-open-btn').addEventListener('click', () => {
                                            document.getElementById('pfp-input').click();
                                        });
                                        document.getElementById('pfp-input').addEventListener('change', () => {
                                            if (document.getElementById('pfp-input').files.length) {
                                                document.getElementById('pfp-form').submit();
                                            }
                                        });
                                    </script>
                                    <a href="favorites.php" class="profile-action-btn" role="button">Kedvenceim</a>
                                </div>
                            <?php endif; ?>
                            <?php if (!$isOwner): ?>
                                <div class="profile-social-block">
                                    <?php if ($friendship && (int)$friendship['status'] === 1): ?>
                                        <div class="friend-status-pill friend-status-pill--yes">
                                            <span class="friend-status-icon">✓</span>
                                            <span>Barátok vagytok</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="friend-status-pill friend-status-pill--no">
                                            <span class="friend-status-icon">○</span>
                                            <span>Még nem vagytok barátok</span>
                                        </div>
                                        <form method="post" action="assets/php/add_friend.php">
                                            <input type="hidden" name="toid" value="<?= (int)$profileId ?>">
                                            <button type="submit" class="profile-action-btn"><?= t('btn_add_friend') ?></button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="post" action="assets/php/report.php" id="user-report-form">
                                        <input type="hidden" name="type" value="user">
                                        <input type="hidden" name="target_id" value="<?= (int)$profileId ?>">
                                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="button" class="profile-action-btn profile-action-btn--danger" id="report-toggle-btn">⚑ Jelentés</button>
                                        <div id="report-box" style="display:none; margin-top:8px;">
                                            <textarea name="reason" rows="3" required placeholder="Írd le, miért jelented..." class="report-textarea"></textarea>
                                            <button type="submit" class="btn-cta danger" onclick="return confirm('Biztosan elküldöd a jelentést?');">Elküldés</button>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="profile-header-stats">
                        <div class="profile-stat-item">
                            <span class="profile-stat-value"><?= $statUploads ?></span>
                            <span class="profile-stat-label">Feltöltés</span>
                        </div>
                        <div class="profile-stat-item">
                            <span class="profile-stat-value"><?= $statDownloads ?></span>
                            <span class="profile-stat-label">Letöltés</span>
                        </div>
                        <div class="profile-stat-item">
                            <span class="profile-stat-value"><?= $statFavorites ?></span>
                            <span class="profile-stat-label">Kedvenc</span>
                        </div>
                        <div class="profile-stat-item">
                            <span class="profile-stat-value"><?= $statFriends ?></span>
                            <span class="profile-stat-label">Barát</span>
                        </div>
                    </div>
                </div>
            </section>
            <div class="profile-main-columns">
                <aside class="profile-column profile-column-left">
                    <div class="card">
                        <h3><?= t('profile_data') ?></h3>
                        <div class="profile-info-card" id="basic-profile-static">
                            <?php if ($show_fullname): ?>
                            <div class="profile-info-item">
                                <div class="profile-info-label"><?= t('profile_fullname') ?></div>
                                <div class="profile-info-value"><?= htmlspecialchars($profile['lastname'] . ' ' . $profile['firstname']) ?></div>
                            </div>
                            <?php endif; ?>
                            <div class="profile-info-item">
                                <div class="profile-info-label"><?= t('profile_username') ?></div>
                                <div class="profile-info-value">@<?= htmlspecialchars($profile['username']) ?></div>
                            </div>
                            <?php if ($isOwner || $show_email): ?>
                                <div class="profile-info-item">
                                    <div class="profile-info-label"><?= t('profile_email') ?></div>
                                    <div class="profile-info-value"><?= htmlspecialchars($profile['email']) ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if ($isOwner || $show_birthdate): ?>
                                <div class="profile-info-item">
                                    <div class="profile-info-label"><?= t('profile_birthdate') ?></div>
                                    <div class="profile-info-value"><?= htmlspecialchars($birthdateValue ?: '—') ?></div>
                                </div>
                            <?php endif; ?>
                            <div class="profile-info-item">
                                <div class="profile-info-label"><?= t('profile_registration') ?></div>
                                <div class="profile-info-value"><?= htmlspecialchars($profile['registration_date']) ?></div>
                            </div>
                            <?php if ($isOwner): ?>
                                <button type="button" class="btn-cta profile-save-btn" id="edit-basic-profile-btn">
                                    <?= t('btn_edit_profile_data') ?>
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php if ($isOwner): ?>
                            <form method="post" class="profile-basic-form hidden" id="basic-profile-form">
                                <input type="text" class="input" name="firstname" placeholder="<?= htmlspecialchars(t('label_firstname')) ?>" value="<?= htmlspecialchars($_POST['firstname'] ?? $profile['firstname']) ?>">
                                <input type="text" class="input" name="lastname" placeholder="<?= htmlspecialchars(t('label_lastname')) ?>" value="<?= htmlspecialchars($_POST['lastname'] ?? $profile['lastname']) ?>">
                                <input type="email" class="input" name="email" placeholder="<?= htmlspecialchars(t('label_email')) ?>" value="<?= htmlspecialchars($_POST['email'] ?? $profile['email']) ?>">
                                <input type="date" class="input" name="birthdate" value="<?= htmlspecialchars($_POST['birthdate'] ?? $birthdateValue) ?>">
                                <button type="submit" name="update-basic-profile" class="btn-cta profile-save-btn"><?= t('btn_save') ?></button>
                                <button type="button" class="btn-ghost" id="cancel-basic-profile-edit"><?= t('btn_cancel') ?></button>
                            </form>
                            <details class="sidebar-accordion">
                                <summary class="sidebar-accordion-summary">Adatok láthatósága</summary>
                                <div class="sidebar-accordion-body">
                                    <form method="post" class="profile-visibility-form">
                                        <label class="toggle privacy-toggle">
                                            <input type="checkbox" name="show_fullname" <?= $show_fullname ? 'checked' : '' ?>>
                                            <span class="toggle-slider"></span>
                                            <span class="toggle-label">Teljes név látható</span>
                                        </label>
                                        <label class="toggle privacy-toggle">
                                            <input type="checkbox" name="show_email" <?= $show_email ? 'checked' : '' ?>>
                                            <span class="toggle-slider"></span>
                                            <span class="toggle-label">Email látható</span>
                                        </label>
                                        <label class="toggle privacy-toggle">
                                            <input type="checkbox" name="show_birthdate" <?= $show_birthdate ? 'checked' : '' ?>>
                                            <span class="toggle-slider"></span>
                                            <span class="toggle-label">Születésnap látható</span>
                                        </label>
                                        <button type="submit" name="save-visibility" class="btn-cta profile-save-btn">Mentés</button>
                                    </form>
                                </div>
                            </details>
                            <details class="sidebar-accordion">
                                <summary class="sidebar-accordion-summary">Kétlépcsős azonosítás (2FA)</summary>
                                <div class="sidebar-accordion-body">
                                    <form method="post">
                                        <label class="toggle">
                                            <input type="checkbox" name="enable_2fa" <?= ((int)$profile['twofa_enabled'] === 1) ? 'checked' : '' ?>>
                                            <span class="toggle-slider"></span>
                                            <span class="toggle-label"><?= ((int)$profile['twofa_enabled'] === 1) ? 'Bekapcsolva' : 'Kikapcsolva' ?></span>
                                        </label>
                                        <br>
                                        <button type="submit" name="toggle-2fa" class="btn-cta profile-save-btn">Mentés</button>
                                    </form>
                                    <p class="entry-meta" style="margin-top:8px;">Bejelentkezéskor e-mailben kapsz egy egyszer használatos kódot.</p>
                                    <?php if ((int)$profile['twofa_enabled'] === 1): ?>
                                        <a href="2fa-backup-codes.php" class="sidebar-link-btn">Backup kódok megtekintése →</a>
                                    <?php endif; ?>
                                </div>
                            </details>
                            <details class="sidebar-accordion">
                                <summary class="sidebar-accordion-summary">Discord</summary>
                                <div class="sidebar-accordion-body">
                                    <?php if (!empty($profile['oauth_provider']) && $profile['oauth_provider'] === 'discord' && !empty($profile['oauth_sub'])): ?>
                                        <p class="entry-meta" style="color:var(--primary);font-weight:600;margin-bottom:6px;">Discord fiók összekapcsolva</p>
                                        <p class="entry-meta">ID: <code class="sidebar-code"><?= htmlspecialchars($profile['oauth_sub']) ?></code></p>
                                        <form method="post">
                                            <button type="submit" name="unlink-discord" class="btn-ghost profile-save-btn">Discord leválasztása</button>
                                        </form>
                                    <?php else: ?>
                                        <p class="entry-meta" style="margin-bottom:10px;">Discord fiók nincs összekapcsolva.</p>
                                        <form method="post">
                                            <button type="submit" name="link-discord" class="btn-cta profile-save-btn">Discord összekapcsolása</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </details>
                        <?php endif; ?>
                    </div>
                    <?php if ($isOwner): ?>
                        <details class="card sidebar-card-accordion">
                            <summary class="sidebar-card-summary"><?= t('profile_customization') ?></summary>
                            <div class="sidebar-card-body">
                                <form method="post" class="profile-settings-form">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><?= t('profile_bio') ?></div>
                                        <div class="profile-info-value">
                                            <textarea class="profile-bio-input" rows="4" name="bio" id="profile-bio-input" maxlength="1500"
                                                      placeholder="<?= htmlspecialchars(t('profile_bio')) ?>"><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
                                            <small id="bio-counter">0 / 1500</small>
                                        </div>
                                    </div>
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><?= t('profile_theme') ?></div>
                                        <div class="profile-info-value">
                                            <select id="profile-theme-select" name="theme" class="profile-theme-select" data-theme="<?= htmlspecialchars($profile_theme) ?>">
                                                <option value="default" <?= ($profile_theme === 'default') ? 'selected' : '' ?>><?= t('profile_theme_default') ?></option>
                                                <option value="pastel"  <?= ($profile_theme === 'pastel')  ? 'selected' : '' ?>><?= t('profile_theme_pastel') ?></option>
                                                <option value="forest"  <?= ($profile_theme === 'forest')  ? 'selected' : '' ?>><?= t('profile_theme_forest') ?></option>
                                                <option value="light"   <?= ($profile_theme === 'light')   ? 'selected' : '' ?>><?= t('profile_theme_light') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" name="save-profile-settings" class="btn-cta profile-save-btn"><?= t('btn_save') ?></button>
                                </form>
                            </div>
                        </details>
                        <div class="card css-editor-card">
                            <div class="css-editor-card-header">
                                <h3><?= t('profile_custom_css_request') ?></h3>
                                <?php if ($lastCssRequest): ?>
                                    <span class="css-status-badge css-status-<?= htmlspecialchars($lastCssRequest['status']) ?>">
                                        <?= htmlspecialchars($lastCssRequest['status']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php if ($lastCssRequest): ?>
                                <p class="entry-meta"><?= t('profile_last_request_status') ?> <strong><?= htmlspecialchars($lastCssRequest['status']) ?></strong><?php if (!empty($lastCssRequest['reviewed_at'])): ?> (<?= htmlspecialchars($lastCssRequest['reviewed_at']) ?>)<?php endif; ?></p>
                            <?php else: ?>
                                <p class="entry-meta"><?= t('profile_custom_css_not_requested') ?></p>
                            <?php endif; ?>
                            <button type="button" class="btn-cta" id="css-modal-open-btn" style="margin-top:4px;">
                                CSS szerkesztése
                            </button>
                        </div>
                        <div id="css-modal-overlay" class="css-modal-overlay" aria-hidden="true">
                            <div class="css-modal-panel" role="dialog" aria-modal="true" aria-label="Egyedi CSS szerkesztő">
                                <div class="css-modal-header">
                                    <div class="css-modal-title">
                                        <h2><?= t('profile_custom_css_request') ?></h2>
                                    </div>
                                    <button type="button" class="css-modal-close" id="css-modal-close-btn" aria-label="Bezárás">✕</button>
                                </div>
                                <div class="css-modal-body">
                                    <div class="css-modal-left">
                                        <details class="css-tutorial" id="css-tutorial">
                                            <summary><?= t('profile_css_tutorial_summary') ?></summary>
                                            <div class="css-tutorial-body">
                                                <p><?= t('profile_css_tutorial_intro') ?></p>
                                                <p><?= t('profile_css_tutorial_example') ?></p>
                                                <pre><code>body {
   background:
       radial-gradient(circle at 0%, rgba(244,114,182,.35), transparent 60%),
       radial-gradient(circle at 100% 0%, rgba(56,189,248,.28), transparent 55%),
       radial-gradient(circle at 50% 100%, rgba(167,139,250,.3), transparent 55%),
       linear-gradient(180deg, #050816 0%, #020617 100%);
   color: #e5e7eb;
}

.main {
   border-radius: 28px;
   border: 1px solid rgba(148,163,184,.35);
   background:
        radial-gradient(circle at 0% 0%, rgba(244,114,182,.12), transparent 55%),
        radial-gradient(circle at 100% 0%, rgba(56,189,248,.10), transparent 55%),
        linear-gradient(180deg, rgba(15,23,42,.96), rgba(15,23,42,.94));
   box-shadow: 0 24px 60px rgba(0,0,0,.7);
   padding: 40px 34px;
}</code></pre>
                                                <p><?= t('tip_profile_custom_css') ?></p>
                                            </div>
                                        </details>
                                    </div>
                                    <div class="css-modal-right">
                                        <form method="post" class="css-modal-form">
                                            <label class="css-modal-label"><?= t('profile_css_label') ?></label>
                                            <textarea
                                                class="css-modal-textarea"
                                                id="profile-custom-css-input"
                                                name="custom_css"
                                                placeholder="<?= htmlspecialchars(t('css_placeholder')) ?>"
                                                data-i18n-css-empty="<?= htmlspecialchars(t('msg_css_empty_reset')) ?>"
                                                data-i18n-css-admin="<?= htmlspecialchars(t('msg_css_approved_by_admin')) ?>"><?= htmlspecialchars($cssResetDone ? '' : ($lastCssRequest['css'] ?? '')) ?></textarea>
                                            <p class="entry-meta css-modal-note"><?= t('css_approval_note') ?></p>
                                            <div class="css-modal-actions">
                                                <button type="submit" name="submit-custom-css" class="btn-cta"><?= t('profile_custom_css_submit') ?></button>
                                                <button type="submit" name="reset-custom-css" class="btn-ghost"><?= t('profile_custom_css_reset_btn') ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>
                </aside>
                <section class="profile-column profile-column-right" id="profile-main-content">
                    <div class="profile-right-block">
                        <div class="card profile-bio">
                            <h3 data-translation-key="profile_bio"><?= t('profile_bio') ?></h3>
                            <?php if ($bio !== ''): ?>
                                <p><?= nl2br(htmlspecialchars($bio)) ?></p>
                            <?php else: ?>
                                <p class="entry-meta opacity-80"><?= htmlspecialchars($fallbackBio) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($isOwner):
                        $savedSearches = [];
                        $ssRes = db_query($conn,
                            "SELECT id, params_json, created_at FROM saved_searches WHERE user_id = ? ORDER BY created_at DESC LIMIT 20",
                            "i", [$profileId]
                        );
                        if ($ssRes) {
                            while ($ssRow = $ssRes->fetch_assoc()) {
                                $decoded = json_decode((string)($ssRow['params_json'] ?? '{}'), true);
                                if (is_array($decoded)) {
                                    $savedSearches[] = ['id' => (int)$ssRow['id'], 'params' => $decoded, 'created_at' => $ssRow['created_at']];
                                }
                            }
                        }
                    ?>
                    <section class="card profile-saved-searches" id="profile-saved-searches">
                        <div class="section-titlebar">
                            <h3><?= htmlspecialchars(t('profile_saved_searches_title')) ?></h3>
                            <?php if (!empty($savedSearches)): ?>
                                <span class="uploads-count"><?= count($savedSearches) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (empty($savedSearches)): ?>
                            <p class="entry-meta opacity-80"><?= htmlspecialchars(t('profile_saved_searches_empty')) ?></p>
                        <?php else: ?>
                            <ul class="saved-searches-list">
                                <?php foreach ($savedSearches as $ss):
                                    $p = $ss['params'];
                                    $parts = [];
                                    if (!empty($p['q'])) $parts[] = htmlspecialchars($p['q']);
                                    if (!empty($p['scope']) && $p['scope'] !== 'all') $parts[] = htmlspecialchars(t('label_scope')) . ': ' . htmlspecialchars($p['scope']);
                                    if (!empty($p['type']) && $p['type']  !== 'all') $parts[] = htmlspecialchars(t('label_type'))  . ': ' . htmlspecialchars(strtoupper($p['type']));
                                    if (!empty($p['level']) && $p['level'] !== 'all') $parts[] = htmlspecialchars(t('search_level_label')) . ': ' . htmlspecialchars($p['level']);
                                    if (!empty($p['tag'])) $parts[] = '#' . htmlspecialchars($p['tag']);
                                    if (!empty($p['mode']) && $p['mode']  !== 'all') $parts[] = htmlspecialchars(t('search_mode_label')) . ': ' . htmlspecialchars($p['mode']);
                                    if (!empty($p['sort']) && $p['sort']  !== 'newest') $parts[] = htmlspecialchars(t('label_sort')) . ': ' . htmlspecialchars($p['sort']);
                                    $label = implode(' · ', $parts) ?: t('save_search_no_label');
                                    $url = 'search.php?' . http_build_query(array_merge($p, ['page' => 1]));
                                ?>
                                <li class="saved-search-item" id="ssi-<?= $ss['id'] ?>">
                                    <a href="<?= htmlspecialchars($url) ?>"><?= $label ?></a>
                                    <span class="saved-search-date"><?= htmlspecialchars(substr($ss['created_at'], 0, 10)) ?></span>
                                    <button
                                        type="button"
                                        class="btn-danger saved-search-delete"
                                        onclick="deleteSavedSearch(<?= $ss['id'] ?>, this)"
                                        title="<?= htmlspecialchars(t('save_search_delete')) ?>">✕</button>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </section>
                    <script>
                    function deleteSavedSearch(id, btn) {
                        if (!confirm('<?= addslashes(t('save_search_delete_confirm')) ?>')) return;
                        btn.disabled = true;
                        fetch('save_search.php', {
                            method : 'POST',
                            body   : new URLSearchParams({ action: 'delete', id: id })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.ok) {
                                const li = document.getElementById('ssi-' + id);
                                if (li) li.remove();
                            } else {
                                btn.disabled = false;
                                alert('<?= addslashes(t('save_search_error')) ?>');
                            }
                        })
                        .catch(() => { btn.disabled = false; });
                    }
                    </script>
                    <?php endif; ?>

                    <section class="card profile-uploads">
                        <div class="section-titlebar profile-uploads-titlebar">
                            <h3 data-translation-key="profile_uploaded_files"><?= t('profile_uploaded_files') ?></h3>
                            <span class="uploads-count">
                                <?php
                                    $filesCountRes = db_query($conn, "SELECT COUNT(*) AS c FROM files WHERE uploaded_by = ?", "i", [$profileId]);
                                    $filesCount = ($filesCountRes && $filesCountRes->num_rows) ? (int)($filesCountRes->fetch_assoc()['c'] ?? 0) : 0;
                                    echo $filesCount;
                                ?>
                            </span>
                        </div>
                        <?php
                            $files = db_query($conn, "SELECT * FROM files WHERE uploaded_by = ? ORDER BY id DESC", "i", [$profileId]);
                            if ($files && $files->num_rows > 0):
                            ?>
                            <div class="profile-uploads-list">
                                <?php while ($file = $files->fetch_assoc()):
                                        $ext = strtolower(pathinfo((string)$file['file_name'], PATHINFO_EXTENSION));
                                        $safe_path = "users/" . htmlspecialchars($profile['username']) . "/" . htmlspecialchars($file['file_name']);

                                        $desc = trim((string)($file['description'] ?? ''));
                                        $tags = [];
                                        if (!empty($file['tags'])) {
                                            $tags = array_values(array_filter(array_map('trim', preg_split('/[,;]+/u', (string)$file['tags']))));
                                        }
                                    ?>
                                    <article class="upload-item">
                                        <div class="upload-top">
                                            <div class="upload-main">
                                                <h4 class="upload-title">
                                                    <a href="note.php?id=<?= (int)$file['id'] ?>" class="upload-title-link">
                                                        <?= htmlspecialchars($file['name']) ?>
                                                    </a>
                                                </h4>
                                                <div class="upload-meta">
                                                    <?php if (!empty($file['subject'])): ?>
                                                        <span class="meta-chip"><strong><?= htmlspecialchars($file['subject']) ?></strong></span>
                                                    <?php endif; ?>
                                                    <span class="meta-chip"><?= strtoupper($ext ?: 'FILE') ?></span>
                                                    <?php if (isset($file['download_count'])): ?>
                                                        <span class="meta-chip"><?= t('label_downloads') ?>: <strong><?= (int)$file['download_count'] ?></strong></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($file['file_size'])): ?>
                                                        <span class="meta-chip"><?= htmlspecialchars(format_bytes((int)$file['file_size'])) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="upload-actions">
                                                <a class="btn-soft" href="note.php?id=<?= (int)$file['id'] ?>"><?= t('btn_details') ?></a>
                                                <a class="btn-primary" href="assets/php/download.php?id=<?= (int)$file['id'] ?>">
                                                    <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path d="M12 3v10m0 0l-4-4m4 4l4-4M4 17v3h16v-3"
                                                              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                    <?= t('btn_download') ?>
                                                </a>
                                                <?php if ($isOwner && (int)$file['uploaded_by'] === $viewerId): ?>
                                                    <form method="POST" action="assets/php/delete.php" class="upload-delete">
                                                        <input type="hidden" name="file_id" value="<?= (int)$file['id'] ?>">
                                                        <button type="submit" class="btn-danger"><?= t('btn_delete_file') ?></button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if ($desc !== ''): ?>
                                            <p class="upload-desc"><?= htmlspecialchars($desc) ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($tags)): ?>
                                            <div class="upload-tags" aria-label="<?= t('label_tags') ?>">
                                                <?php foreach (array_slice($tags, 0, 10) as $tg): ?>
                                                    <span class="tag-pill" title="<?= htmlspecialchars($tg) ?>"><?= htmlspecialchars($tg) ?></span>
                                                <?php endforeach; ?>
                                                <?php if (count($tags) > 10): ?>
                                                    <span class="tag-pill tag-pill-more">+<?= count($tags) - 10 ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($ext === 'mp4' || $ext === 'pdf'): ?>
                                            <details class="upload-preview">
                                                <summary class="upload-preview-summary">
                                                    <?= ($ext === 'mp4') ? 'Videó előnézet' : 'PDF előnézet' ?>
                                                </summary>
                                                <?php if ($ext === 'mp4'): ?>
                                                    <video controls class="file-preview">
                                                        <source src="<?= $safe_path ?>" type="video/mp4">
                                                    </video>
                                                <?php else: ?>
                                                    <iframe class="file-preview" src="<?= $safe_path ?>" height="460"></iframe>
                                                <?php endif; ?>
                                            </details>
                                        <?php elseif ($ext === 'docx'): ?>
                                            <p class="upload-hint"><strong><?= t('docx_warning') ?></strong></p>
                                        <?php endif; ?>
                                    </article>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="profile-empty">
                                <p><?= t('empty_no_files') ?></p>
                                <?php if ($isOwner): ?>
                                    <p class="upload-hint">Tipp: tölts fel valamit, hogy itt megjelenjen. 👀</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </section>
                </section>
            </div>
        </div>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>