<?php
    session_start();
    
    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once 'assets/php/functions.php';

    $notify_number = 0;
    $profileUpdateError = '';
    $profileUpdateSuccess = '';
    $closeCssPreview = false;

    if (!empty($_SESSION['profile_toast'])) {
        $profileUpdateSuccess = t($_SESSION['profile_toast']);
        unset($_SESSION['profile_toast']);
    }

    $cssResetDone = !empty($_SESSION['css_reset_done']);
    if ($cssResetDone) {
        unset($_SESSION['css_reset_done']);
    }

    if (!empty($_SESSION['close_css_preview'])) {
        $closeCssPreview = true;
        unset($_SESSION['close_css_preview']);
    }

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $viewerId = (int)$_COOKIE['id'];
    $profileId = isset($_GET['userid']) ? (int)$_GET['userid'] : 0;

    if ($profileId <= 0) {
        exit(t('msg_invalid_profile_id'));
    }

    $res = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$profileId]);
    if ($res->num_rows === 0) {
        exit(t('msg_profile_not_found'));
    }
    $profile = $res->fetch_assoc();

    $isOwner  = ($viewerId === (int)$profile['id']);

    $profile_theme = !empty($profile['profile_theme']) ? $profile['profile_theme'] : 'default';

    $is_birthday = false;
    if (!empty($profile['birthdate'])) {
        $is_birthday = (date('m-d', strtotime($profile['birthdate'])) === date('m-d'));
    }

    $birthdateValue = '';
    if (!empty($profile['birthdate']) && $profile['birthdate'] !== '0000-00-00') {
        $birthdateValue = substr($profile['birthdate'], 0, 10);
    }

    $needsSecuritySetup = $isOwner && ($profile['security_answer'] === '' || $profile['security_answer'] === null);

    $securitySetupQuestion = '';
    if ($needsSecuritySetup) {
        $security_questions = [
            t('sec_q_favorite_book'),
            t('sec_q_first_pet_name'),
            t('sec_q_mother_maiden_name'),
            t('sec_q_birth_city'),
            t('sec_q_favorite_food')
        ];

        if (!empty($_POST['security_question'])) {
            $securitySetupQuestion = $_POST['security_question'];
        } else {
            $securitySetupQuestion = $security_questions[array_rand($security_questions)];
        }
    }

    if ($isOwner && isset($_POST['update-basic-profile'])) {
        $firstname = trim($_POST['firstname'] ?? '');
        $lastname  = trim($_POST['lastname'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $birthdate = trim($_POST['birthdate'] ?? '');

        if ($firstname === '') {
            $firstname = $profile['firstname'];
        }
        if ($lastname === '') {
            $lastname = $profile['lastname'];
        }
        if ($email === '') {
            $email = $profile['email'];
        }
        if ($birthdate === '') {
            $birthdate = $profile['birthdate'];
        }

        if ($firstname === '' || $lastname === '' || $email === '') {
            $profileUpdateError = t('error_all_fields_required');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $profileUpdateError = t('error_bad_email_format');
        } else {
            if ($needsSecuritySetup) {
                $sec_question = trim($_POST['security_question'] ?? '');
                $sec_answer   = trim($_POST['security_answer'] ?? '');
                if ($sec_question === '' || $sec_answer === '') {
                    $profileUpdateError = t('error_security_answer_required');
                } else {
                    db_stmt($conn, "UPDATE users  SET firstname = ?, lastname = ?, email = ?, birthdate = ?, security_question = ?, security_answer = ?  WHERE id = ? LIMIT 1", "ssssssi", [$firstname, $lastname, $email, $birthdate, $sec_question, $sec_answer, $viewerId])->close();
                    $_SESSION['profile_toast'] = 'msg_profile_update_success';
                    header("Location: profile.php?userid=" . $viewerId);
                    exit;
                }
            } else {
                db_stmt($conn, "UPDATE users  SET firstname = ?, lastname = ?, email = ?, birthdate = ?  WHERE id = ? LIMIT 1", "ssssi", [$firstname, $lastname, $email, $birthdate, $viewerId])->close();
                $_SESSION['profile_toast'] = 'msg_profile_update_success';
                header("Location: profile.php?userid=" . $viewerId);
                exit;
            }
        }
    }

    $badges = [];
    $badgeRes = db_query($conn, "SELECT b.*  FROM user_badges ub  JOIN badges b ON ub.badge_id = b.id  WHERE ub.user_id = ?  ORDER BY ub.granted_at ASC", "i", [(int)$profile['id']]);
    while ($row = $badgeRes->fetch_assoc()) {
        $badges[] = $row;
    }

    $approvedCss = '';
    $cssRes = db_query($conn, "SELECT css  FROM user_custom_css_requests  WHERE user_id = ? AND status = 'approved'  ORDER BY id DESC  LIMIT 1", "i", [(int)$profile['id']]);
    if ($cssRes->num_rows > 0) {
        $approvedCss = $cssRes->fetch_assoc()['css'];
    }

    $lastCssRequest = null;
    if ($isOwner) {
        $lastReqRes = db_query($conn, "SELECT *  FROM user_custom_css_requests  WHERE user_id = ? AND status <> 'disabled'  ORDER BY id DESC  LIMIT 1", "i", [$viewerId]);
        if ($lastReqRes->num_rows > 0) {
            $lastCssRequest = $lastReqRes->fetch_assoc();
        }
    }

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
            db_stmt($conn, "UPDATE users SET profile_picture = ? WHERE id = ? LIMIT 1", "si", [$file_name, $viewerId])->close();

            header("Location: profile.php?userid=".$viewerId);
            exit;
        } else {
            Message(t('msg_file_upload_error'));
        }
    }

    if ($isOwner && isset($_POST['edit-email-btn'])) {
        header("Location: edit_email.php?userid=" . $profile['id']);
        exit;
    }

    if ($isOwner && isset($_POST['save-profile-settings'])) {
        $bio   = $_POST['bio'] ?? '';
        $theme = $_POST['theme'] ?? 'default';

        $allowedThemes = ['default', 'pastel', 'forest', 'light'];

        if (!in_array($theme, $allowedThemes, true)) {
            $theme = 'default';
        }

        db_stmt($conn, "UPDATE users SET bio = ?, profile_theme = ? WHERE id = ? LIMIT 1", "ssi", [$bio, $theme, $viewerId])->close();

        header("Location: profile.php?userid=" . $viewerId);
        exit;
    }

    if ($isOwner && isset($_POST['submit-custom-css'])) {
        $css = trim($_POST['custom_css'] ?? '');

        if ($css !== '') {
            db_stmt($conn, "INSERT INTO user_custom_css_requests (user_id, css, status, created_at)  VALUES (?, ?, 'pending', NOW())", "is", [$viewerId, $css])->close();
        } else {
            $_SESSION['profile_toast'] = 'msg_css_empty_reset';
            $_SESSION['close_css_preview'] = true;
        }

        header("Location: profile.php?userid=" . $viewerId);
        exit;
    }

    if ($isOwner && isset($_POST['reset-custom-css'])) {

        $reqRes = db_query($conn, "SELECT *  FROM user_custom_css_requests  WHERE user_id = ?  ORDER BY id DESC  LIMIT 1", "i", [$viewerId]);
        $req = $reqRes->num_rows > 0 ? $reqRes->fetch_assoc() : null;

        if ($req) {
            db_stmt($conn, "INSERT INTO user_custom_css_archive (original_request_id, user_id, css, status, created_at, reviewed_at, reviewed_by)  VALUES (?, ?, ?, ?, ?, ?, ?)", "iissssi", [ $req['id'], $req['user_id'], $req['css'], $req['status'], $req['created_at'], $req['reviewed_at'], $req['reviewed_by']])->close();
            db_stmt($conn, "DELETE FROM user_custom_css_requests WHERE id = ?", "i", [$req['id']])->close();
        }

        $_SESSION['css_reset_done'] = true;

        $_SESSION['profile_toast'] = 'msg_custom_css_reset';
        $_SESSION['close_css_preview'] = true;

        header("Location: profile.php?userid=" . $viewerId);
        exit;
    }

    $notify_number = 0;
    $nfRes = db_query($conn, "SELECT id FROM notifys WHERE toid = ? AND readed = 0", "i", [(int)$profile['id']]);
    
    $notify_number = (int)$nfRes->num_rows;
    $friendship = null;

    if (!$isOwner) {
        $frqRes = db_query($conn, "SELECT * FROM friends  WHERE (fromid = ? AND toid = ?) OR (fromid = ? AND toid = ?)  LIMIT 1", "iiii", [$viewerId, $profileId, $profileId, $viewerId]);
        if ($frqRes->num_rows > 0) {
            $friendship = $frqRes->fetch_assoc();
        }
    }

    $basicEditMode = false;
    if ($isOwner) {
        if (!empty($profileUpdateError) && isset($_POST['update-basic-profile'])) {
            $basicEditMode = true;
        }
    }
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
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <?php if (!empty($approvedCss)): ?>
        <style id="profile-custom-css">
            <?= $approvedCss . "\n" ?>
        </style>
    <?php endif; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body class="theme-<?= htmlspecialchars($profile_theme) ?>">
    <?php include 'assets/php/navbar.php'; ?>
    <div class="main">
        <?php if (!empty($profileUpdateError)): ?>
            <div class="toast toast-error">
                <?= htmlspecialchars($profileUpdateError) ?>
            </div>
        <?php elseif (!empty($profileUpdateSuccess)): ?>
            <div class="toast toast-success">
                <?= htmlspecialchars($profileUpdateSuccess) ?>
            </div>
        <?php endif; ?>
        <h1><?= htmlspecialchars($profile['firstname']) . ' ' . t('profile_of') ?></h1>
        <div class="profile-layout">
            <section class="card profile-header">
                <div class="profile-header-grid">
                    <div class="profile-header-avatar">
                        <div class="avatar-wrap <?= $is_birthday ? 'is-birthday' : '' ?>" style="--avatar-size:180px">
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
                    </div>
                    <div class="profile-header-main">
                        <div>
                            <h2 class="profile-name"><?= htmlspecialchars($profile['lastname'] . ' ' . $profile['firstname']) ?></h2>
                            <p class="entry-meta profile-username">@<?= htmlspecialchars($profile['username']) ?></p>
                            <?php if (!empty($badges)): ?>
                                <div class="profile-badges">
                                    <?php foreach ($badges as $badge): ?>
                                        <span class="badge-pill" title="<?= htmlspecialchars($badge['description'] ?? '') ?>">
                                            <?= htmlspecialchars($badge['icon'] ?: '🏅') ?>
                                            <?= htmlspecialchars($badge['name']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($is_birthday && $isOwner): ?>
                                <div class="bday-banner" role="status" aria-live="polite">
                                    <span class="bday-emoji" aria-hidden="true">🎂</span>
                                    <div class="bday-text">
                                        <strong><?= t('bday_title') ?> <?= htmlspecialchars($profile['firstname']) ?>!</strong>
                                        <?= t('bday_message') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="profile-header-actions">
                            <?php if ($isOwner): ?>
                                <form method="POST" enctype="multipart/form-data">
                                    <label for="profile_picture" class="btn-cta profile-save-btn"><?= t('btn_upload_profile_pic') ?></label>
                                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" style="display:none" onchange="this.form.submit()">
                                    <input type="hidden" name="pfp-btn" value="1">
                                </form>
                                <a href="favorites.php" class="btn-cta profile-save-btn">
                                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true" style="width: 18px; height: 18px; margin-right: 4px;">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                                    </svg>
                                    <?= t('nav_favorites') ?>
                                </a>
                            <?php else: ?>
                                <div class="profile-friendship">
                                    <h3 class="profile-friendship-title"><?= t('profile_friendship') ?></h3>
                                    <?php if ($friendship): ?>
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
                        </div>
                    </div>
                </div>
            </section>
            <div class="profile-main-columns">
                <aside class="profile-column profile-column-left">
                    <div class="card">
                        <h3><?= t('profile_data') ?></h3>
                        <div class="profile-info-card" id="basic-profile-static"
                            style="<?= ($isOwner && $basicEditMode) ? 'display:none;' : '' ?>">
                            <?php if ($isOwner): ?>
                                <div class="profile-info-item">
                                    <div class="profile-info-label"><?= t('profile_fullname') ?></div>
                                    <div class="profile-info-value">
                                        <?= htmlspecialchars($profile['lastname'] . ' ' . $profile['firstname']) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="profile-info-item">
                                <div class="profile-info-label"><?= t('profile_username') ?></div>
                                <div class="profile-info-value">@<?= htmlspecialchars($profile['username']) ?></div>
                            </div>
                            <?php if ($isOwner): ?>
                                <div class="profile-info-item">
                                    <div class="profile-info-label"><?= t('profile_email') ?></div>
                                    <div class="profile-info-value"><?= htmlspecialchars($profile['email']) ?></div>
                                </div>
                                <div class="profile-info-item">
                                    <div class="profile-info-label"><?= t('profile_birthdate') ?></div>
                                    <div class="profile-info-value">
                                        <?= htmlspecialchars($birthdateValue ?: '—') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="profile-info-item">
                                <div class="profile-info-label"><?= t('profile_registration') ?></div>
                                <div class="profile-info-value"><?= htmlspecialchars($profile['registration_date']) ?></div>
                            </div>
                            <?php if ($isOwner): ?>
                                <button type="button" class="btn-cta profile-save-btn" id="edit-basic-profile-btn" style="margin-top:10px;">
                                    <?= t('btn_edit_profile_data') ?>
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php if ($isOwner): ?>
                        <form method="post" class="profile-basic-form" id="basic-profile-form" style="<?= $basicEditMode ? '' : 'display:none;' ?>">
                            <input type="text" class="input" name="firstname" placeholder="<?= htmlspecialchars(t('label_firstname')) ?>" value="<?= htmlspecialchars($_POST['firstname'] ?? $profile['firstname']) ?>">
                            <input type="text" class="input" name="lastname" placeholder="<?= htmlspecialchars(t('label_lastname')) ?>" value="<?= htmlspecialchars($_POST['lastname'] ?? $profile['lastname']) ?>">
                            <input type="email" class="input" name="email" placeholder="<?= htmlspecialchars(t('label_email')) ?>" value="<?= htmlspecialchars($_POST['email'] ?? $profile['email']) ?>">
                            <input type="date" class="input" name="birthdate" placeholder="<?= htmlspecialchars(t('label_birthdate')) ?>" value="<?= htmlspecialchars($_POST['birthdate'] ?? $birthdateValue) ?>">
                        <?php if ($needsSecuritySetup): ?>
                            <p class="entry-meta" style="margin-top:10px;">
                                <?= t('profile_security_intro') ?>
                            </p>
                            <div class="profile-info-item">
                                <div class="profile-info-label"><?= t('auth_field_security_question') ?></div>
                                <div class="profile-info-value">
                                    <?= htmlspecialchars($securitySetupQuestion) ?>
                                </div>
                            </div>
                            <div class="profile-info-item">
                                <div class="profile-info-label"><?= t('auth_field_answer') ?></div>
                                <div class="profile-info-value">
                                    <input type="text" name="security_answer" class="input" placeholder="<?= htmlspecialchars(t('placeholder_security_answer')) ?>" value="<?= htmlspecialchars($_POST['security_answer'] ?? '') ?>">
                                </div>
                            </div>
                            <input type="hidden" name="security_question" value="<?= htmlspecialchars($securitySetupQuestion) ?>">
                        <?php endif; ?>
                                <button type="submit"
                                        name="update-basic-profile"
                                        class="btn-cta profile-save-btn"
                                        style="margin-top:10px;">
                                    <?= t('btn_save') ?>
                                </button>
                            </form>
                    </div>
                    <?php if ($isOwner): ?>
                        <div class="card">
                            <h3><?= t('profile_customization') ?></h3>
                            <form method="post" class="profile-settings-form">
                                <div class="profile-info-item">
                                    <div class="profile-info-label"><?= t('profile_bio') ?></div>
                                    <div class="profile-info-value">
                                        <textarea class="profile-bio-input" rows="4" name="bio" id="profile-bio-input" placeholder="<?= htmlspecialchars(t('profile_bio')) ?>">
                                        <?= htmlspecialchars($profile['bio'] ?? '') ?>
                                    </textarea>
                                    </div>
                                </div>
                                <div class="profile-info-item">
                                    <div class="profile-info-label"><?= t('profile_theme') ?></div>
                                    <div class="profile-info-value">
                                        <select id="profile-theme-select" name="theme" class="profile-theme-select" data-theme="<?= htmlspecialchars($profile_theme) ?>">
                                            <option value="default" <?= ($profile_theme === 'default') ? 'selected' : '' ?>><?= t('profile_theme_default') ?></option>
                                            <option value="pastel" <?= ($profile_theme === 'pastel') ? 'selected' : '' ?>><?= t('profile_theme_pastel') ?></option>
                                            <option value="forest" <?= ($profile_theme === 'forest') ? 'selected' : '' ?>><?= t('profile_theme_forest') ?></option>
                                            <option value="light"  <?= ($profile_theme === 'light')  ? 'selected' : '' ?>><?= t('profile_theme_light') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="save-profile-settings" class="btn-cta profile-save-btn">
                                    <?= t('btn_save') ?>
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                        <div class="card">
                            <h3><?= t('profile_custom_css_request') ?></h3>
                            <?php if ($lastCssRequest): ?>
                                <p class="entry-meta">
                                    <?= t('profile_last_request_status') ?>
                                    <strong><?= htmlspecialchars($lastCssRequest['status']) ?></strong>
                                    <?php if (!empty($lastCssRequest['reviewed_at'])): ?>
                                        (<?= htmlspecialchars($lastCssRequest['reviewed_at']) ?>)
                                    <?php endif; ?>
                                </p>
                            <?php else: ?>
                                <p class="entry-meta"><?= t('profile_custom_css_not_requested') ?></p>
                            <?php endif; ?>
                            <details class="css-tutorial" id="css-tutorial">
                                <summary><?= t('profile_css_tutorial_summary') ?></summary>
                                <div class="css-tutorial-body">
                                    <p><?= t('profile_css_tutorial_intro') ?></p>
                                    <p><?= t('profile_css_tutorial_example') ?></p>
                            <pre><code>
body {
    background:
        radial-gradient(circle at 0% 0%, rgba(244,114,182,.35), transparent 60%),
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
}
                        </code></pre>
                                    <p><?= t('tip_profile_custom_css') ?></p>
                                </div>
                            </details>
                            <form method="post">
                                <div class="profile-info-item">
                                    <div class="profile-info-label"><?= t('profile_css_label') ?></div>
                                    <div class="profile-info-value">
                                        <textarea class="profile-bio-input" rows="4" id="profile-custom-css-input" style="width: 250px" name="custom_css" placeholder="<?= htmlspecialchars(t('css_placeholder')) ?>" data-i18n-css-empty="<?= htmlspecialchars(t('msg_css_empty_reset')) ?>" data-i18n-css-admin="<?= htmlspecialchars(t('msg_css_approved_by_admin')) ?>">  
                                            <?php
                                            if ($cssResetDone) {
                                                echo '';
                                            } else {
                                                echo htmlspecialchars($lastCssRequest['css'] ?? '');
                                            }
                                        ?></textarea>
                                        <p class="entry-meta">
                                            <?= t('css_approval_note') ?>
                                        </p>
                                            <div class="css-button-row">
                                                <button type="submit" name="submit-custom-css" class="btn-cta profile-save-btn">
                                                    <?= t('profile_custom_css_submit') ?>
                                                </button>
                                                <button type="submit" name="reset-custom-css" class="btn-cta profile-save-btn">
                                                    <?= t('profile_custom_css_reset_btn') ?>
                                                </button>
                                            </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </aside>
                <section class="profile-column profile-column-right">
                    <?php if (!empty($profile['bio'])): ?>
                        <div class="card">
                            <h3 data-translation-key="profile_bio"><?= t('profile_bio') ?></h3>
                            <p><?= nl2br(htmlspecialchars($profile['bio'])) ?></p>
                        </div>
                    <?php endif; ?>
                    <div class="section-titlebar">
                        <h3 data-translation-key="profile_uploaded_files"><?= t('profile_uploaded_files') ?></h3>
                    </div>
                    <?php
                    $files = db_query($conn, "SELECT * FROM files WHERE uploaded_by = ? ORDER BY id DESC", "i", [(int)$profile['id']]);
                    if ($files->num_rows > 0): ?>
                        <div class="content-grid grid-large">
                            <?php while ($file = $files->fetch_assoc()):
                                $uploaderRes = db_query($conn, "SELECT username FROM users WHERE id = ? LIMIT 1", "i", [(int)$file['uploaded_by']]);
                                $uploader = $uploaderRes->num_rows > 0
                                    ? $uploaderRes->fetch_assoc()
                                    : ['username' => t('label_unknown_user')];
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
    </div>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>