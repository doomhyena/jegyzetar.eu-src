<?php
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

        $nf = $conn->prepare("SELECT COUNT(*) FROM notifys WHERE toid = ? AND readed = 0");
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
?>

<nav class="navbar sticky top-0 z-50 w-full">
    <div class="navbar-content mx-auto max-w-6xl w-full px-4 md:px-6 lg:px-8 flex items-center justify-between gap-4">
        <!-- mobile toggler -->
        <button class="navbar-toggler md:hidden flex-shrink-0" type="button" aria-label="Menü">
            <span class="hamburger"></span>
        </button>

        <!-- brand -->
        <a href="index.php" class="brand inline-flex items-center gap-2 select-none flex-shrink-0">
            <span class="tracking-tight text-base md:text-lg">Jegyzetár</span>
            <span class="brand-badge text-xs md:text-sm">beta</span>
        </a>

        <ul class="nav-links items-center gap-1 md:gap-2">
            <li><a href="index.php" class="px-2 md:px-3 py-2 rounded-lg text-sm md:text-base whitespace-nowrap"><?= t('nav_home') ?></a></li>
            <li><a href="search.php" class="px-2 md:px-3 py-2 rounded-lg text-sm md:text-base whitespace-nowrap"><?= t('nav_search') ?></a></li>
            <li><a href="upload.php" class="px-2 md:px-3 py-2 rounded-lg text-sm md:text-base whitespace-nowrap"><?= t('nav_upload') ?></a></li>
            <li><a href="groups.php" class="px-2 md:px-3 py-2 rounded-lg text-sm md:text-base whitespace-nowrap">Csoportok</a></li>

            <?php if ($isLoggedIn && $currentUsername): ?>
                <li class="nav-item-has-dropdown relative">
                    <a href="#" class="nav-account-link px-2 md:px-3 py-2 rounded-lg text-sm md:text-base flex items-center gap-1">
                        <span class="truncate max-w-[120px] md:max-w-none"><?= '@' . htmlspecialchars($currentUsername) ?></span>
                        <span class="nav-account-chevron">▾</span>
                    </a>

                    <!-- dropdown -->
                    <div class="nav-dropdown absolute right-0 mt-2 min-w-[200px]">
                        <a href="profile.php?username=<?= urlencode($currentUsername) ?>" class="block px-4 py-2 text-sm hover:bg-white/10"><?= t('nav_profil') ?></a>
                        <a href="favorites.php" class="block px-4 py-2 text-sm hover:bg-white/10">Kedvencek</a>
                        <a href="messages.php" class="block px-4 py-2 text-sm hover:bg-white/10"><?= t('nav_messages') ?></a>
                        <a href="notify.php" class="block px-4 py-2 text-sm hover:bg-white/10"><?= t('nav_notify') ?> (<?= (int)$notify_number ?>)</a>

                        <?php if (!empty($user['admin']) && (int)$user['admin'] === 1): ?>
                            <a href="admin_panel.php" class="block px-4 py-2 text-sm hover:bg-white/10"><?= t('nav_admin') ?></a>
                        <?php endif; ?>

                        <a href="assets/php/logout.php" class="block px-4 py-2 text-sm hover:bg-white/10"><?= t('nav_logout') ?></a>
                    </div>
                </li>
            <?php else: ?>
                <li>
                    <a href="reglog.php?mode=login" class="px-2 md:px-3 py-2 rounded-lg text-sm md:text-base whitespace-nowrap">
                        <?= t('nav_login') ?>
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-lang-item flex-shrink-0">
                <form method="get" class="m-0">
                    <select name="lang" onchange="this.form.submit()" class="select text-sm md:text-base">
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