<?php
    $isLoggedIn = isset($_COOKIE['id']);
    $user = null;
    $notify_number = 0;

    if ($isLoggedIn) {
        $userid = (int)$_COOKIE['id'];
        $sql = "SELECT * FROM users WHERE id='" . $conn->real_escape_string($userid) . "'";
        $found_user = $conn->query($sql);

        if ($found_user && $found_user->num_rows > 0) {
            $user = $found_user->fetch_assoc();

            $sql = "SELECT * FROM notifys WHERE toid = $userid AND readed = 0";
            $founded_notify = $conn->query($sql);
            $notify_number = $founded_notify ? (int)$founded_notify->num_rows : 0;
        } else {
            $isLoggedIn = false;
            setcookie("id", "", time() - 3600, "/");
        }
    }

    $currentUserId = (int)($user['id'] ?? 0);

?>

<nav class="navbar">
    <div class="navbar-content">
        <button class="navbar-toggler" type="button">
            <span class="hamburger"></span>
        </button>

        <ul class="nav-links">
            <li><a href="index.php">Főoldal</a></li>
            <li><a href="upload.php">Feltöltés</a></li>
            <li><a href="profile.php?userid=<?= $currentUserId ?>">Profilom</a></li>
            <li><a href="search.php">Keresés</a></li>
            <li><a href="notify.php">Értesítések (<?= $notify_number ?>)</a></li>
            <li><a href="messages.php">Üzenetek</a></li>

            <?php if (!empty($user['admin']) && $user['admin'] == 1): ?>
                <li><a href="admin_panel.php">Admin Panel</a></li>
            <?php endif; ?>

            <?php if ($isLoggedIn): ?>
                <li><a href="assets/php/logout.php">Kijelentkezés</a></li>
            <?php else: ?>
                <li><a href="reglog.php?mode=login">Bejelentkezés</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
