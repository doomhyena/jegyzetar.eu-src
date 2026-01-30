<?php
	require_once "assets/php/premium.php";

    $isLoggedIn = false;
    $user = null;
    $notify_number = 0;
    $currentUsername = null;

    if (isset($_COOKIE['id']) && ctype_digit($_COOKIE['id'])) {
        $isLoggedIn = true;
        $userid = (int)$_COOKIE['id'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $user = $res->fetch_assoc();
            $currentUsername = $user['username'];

            $nf = $conn->prepare(
                    "SELECT COUNT(*) FROM notifys WHERE toid = ? AND readed = 0"
            );
            $nf->bind_param("i", $userid);
            $nf->execute();
            $nf->bind_result($notify_number);
            $nf->fetch();
            $nf->close();
        } else {
            setcookie("id", "", time() - 3600, "/");
            $isLoggedIn = false;
        }
    }
	
	$currentUserId = ($user['id'] ?? 0);
	$premium_van = false;
	
	if ($isLoggedIn) {
        $premium_van = user_premium($conn, $userid);
    }
?>

<nav class="navbar">
    <div class="navbar-content">
        <button class="navbar-toggler" type="button">
            <span class="hamburger"></span>
        </button>
        <div class="brand">
            <span>Jegyzetár</span>
            <span class="brand-badge">beta</span>
        </div>
        <ul class="nav-links">
            <li><a href="index.php"><?= t('nav_home') ?></a></li>
            <li><a href="search.php"><?= t('nav_search') ?></a></li>
            <li><a href="upload.php"><?= t('nav_upload') ?></a></li>
            <li><a href="groups.php">Csoportok</a></li>
            <?php if ($isLoggedIn && $currentUsername): ?>
                <li class="nav-item-has-dropdown">
                    <a href="#" class="nav-account-link">
                        <?= $isLoggedIn && $currentUsername ? '@'.htmlspecialchars($currentUsername) : t('nav_login') ?>
                        <span class="nav-account-chevron">▾</span>
                    </a>
                    <div class="nav-dropdown">
                            <a href="profile.php?username=<?= urlencode($currentUsername) ?>">
                                <?= t('nav_profil') ?>
                            </a>
                            <a href="favorites.php">Kedvencek</a>
                            <a href="messages.php"><?= t('nav_messages') ?></a>
                            <a href="premium.php"><?= $premium_van ? "⭐ Prémium" : "Prémium" ?></a>
                            <a href="notify.php"><?= t('nav_notify') ?> (<?= $notify_number ?>)</a>
                                <?php if (!empty($user['admin']) && $user['admin'] == 1): ?>
                                    <a href="admin_panel.php"><?= t('nav_admin') ?></a>
                                <?php endif; ?>
                            <a href="assets/php/logout.php"><?= t('nav_logout') ?></a>
                    </div>
                </li>
            <?php else: ?>
                <a href="reglog.php?mode=login">
                    <?= t('nav_login') ?>
                </a>
            <?php endif; ?>
            <li class="nav-lang-item">
                <form method="get">
                    <select name="lang" onchange="this.form.submit()" class="select">
                        <option value="hu" <?= $lang === 'hu' ? 'selected' : '' ?>>HU</option>
                        <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>EN</option>
                        <option value="de" <?= $lang === 'de' ? 'selected' : '' ?>>DE</option>
                    </select>
                    <?php
                    foreach ($_GET as $k => $v) {
                        if ($k === 'lang') continue;
                        echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars($v).'">';
                    }
                    ?>
                </form>
            </li>
        </ul>
    </div>
</nav>
