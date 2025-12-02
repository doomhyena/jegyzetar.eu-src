<?php
    $isLoggedIn = isset($_COOKIE['id']);
    $user = null;
    $notify_number = 0;

    if ($isLoggedIn) {
        $userid = $_COOKIE['id'];
        $sql = "SELECT * FROM users WHERE id='" . $conn->real_escape_string($userid) . "'";
        $found_user = $conn->query($sql);

        if ($found_user && $found_user->num_rows > 0) {
            $user = $found_user->fetch_assoc();

            $sql = "SELECT * FROM notifys WHERE toid = $userid AND readed = 0";
            $founded_notify = $conn->query($sql);
            $notify_number = $founded_notify ? $founded_notify->num_rows : 0;
        } else {
            $isLoggedIn = false;
            setcookie("id", "", time() - 3600, "/");
        }
    }

    $currentUserId = ($user['id'] ?? 0);

?>

<nav class="navbar">
    <div class="navbar-content">
        <button class="navbar-toggler" type="button">
            <span class="hamburger"></span>
        </button>
        <div class="brand"><span>Jegyzetár</span><span class="brand-badge">beta</span></div>
        <ul class="nav-links">
            <li><a href="index.php"><?= t('nav_home') ?></a></li>
            <li><a href="upload.php"><?= t('nav_upload') ?></a></li>
            <li><a href="profile.php?userid=<?= $currentUserId ?>"><?= t('nav_profil') ?></a></li>
            <li><a href="search.php"><?= t('nav_search') ?></a></li>
			<li><a href="groups.php"><?= t('Csoportok') ?></a></li>
            <li><a href="notify.php"><?= t('nav_notify') ?> (<?= $notify_number ?>)</a></li>
            <li><a href="messages.php"><?= t('nav_messages') ?></a></li>

            <?php if (!empty($user['admin']) && $user['admin'] == 1): ?>
                <li><a href="admin_panel.php"><?= t('nav_admin') ?></a></li>
            <?php endif; ?>

            <?php if ($isLoggedIn): ?>
                <li><a href="assets/php/logout.php"><?= t('nav_logout') ?></a></li>
            <?php else: ?>
                <li><a href="reglog.php?mode=login"><?= t('nav_login') ?></a></li>
            <?php endif; ?>

            <form method="get" style="display:inline;">
                <select name="lang" onchange="this.form.submit()" class="select">
                    <option value="hu" <?= $lang === 'hu' ? 'selected' : '' ?>>HU</option>
                    <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>EN</option>
                    <option value="de" <?= $lang === 'de' ? 'selected' : '' ?>>DE</option>
                </select>
                <?php
                foreach ($_GET as $k => $v) {
                    if ($k === 'lang') continue;
                    echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
                }
                ?>
            </form>
        </ul>
    </div>
</nav>
