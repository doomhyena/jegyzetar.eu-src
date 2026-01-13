<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require "assets/php/db.php";
    require_once "assets/php/functions.php";

    $isLoggedIn = isset($_COOKIE['id']);
    $user = null;
    $notify_number = 0;

    if ($isLoggedIn) {
        $uid = (int)$_COOKIE['id'];
        $res = db_query($conn, "SELECT id, username, firstname FROM users WHERE id = ? LIMIT 1", "i", [$uid]);
        if ($res && $res->num_rows > 0) {
            $user = $res->fetch_assoc();
        }

        $nf = db_query($conn, "SELECT id FROM notifys WHERE toid = ? AND readed = 0", "i", [$uid]);
        $notify_number = $nf->num_rows;
    }

    $en_csoportjaim = [];

    if ($isLoggedIn) {
        $cg = db_query($conn, "SELECT id, name FROM groups WHERE owner_id = ? ORDER BY name ASC", "i", [$uid]);

        while ($cg && $row = $cg->fetch_assoc()) {
            $en_csoportjaim[] = $row;
        }
    }

    $q = trim($_GET['q'] ?? '');
    $scope = strtolower($_GET['scope'] ?? 'all');
    $type = strtolower($_GET['type'] ?? 'all');
    $sort = strtolower($_GET['sort'] ?? 'new');

    if (!in_array($scope, ['files','users','all'], true)) $scope = 'all';
    if (!in_array($type, ['all','pdf','mp4','docx'], true)) $type = 'all';
    if (!in_array($sort, ['new','old','top'], true)) $sort = 'new';

    $userResult = null;

    if ($scope !== 'files') {
        $where = '';
        $types = '';
        $params = [];
        $order = 'ORDER BY u.username ASC';

        if ($q !== '') {
            $like  = '%' . $q . '%';
            $start = $q . '%';

            $where = "WHERE u.username LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ?";
            $types = 'sss';
            $params = [$like, $like, $like];

            $order = "ORDER BY CASE WHEN u.username LIKE ? THEN 0 WHEN u.firstname LIKE ? THEN 1 WHEN u.lastname LIKE ? THEN 2 ELSE 3 END, u.username ASC";

            $types .= 'sss';
            array_push($params, $start, $start, $start);
        }

        $sqlUsers = "SELECT u.id, u.username, u.firstname, u.lastname, u.profile_picture FROM users u $where $order LIMIT 50";
        $userResult = db_query($conn, $sqlUsers, $types, $params);
    }

    $fileResult = null;

    if ($scope !== 'users') {
        $where = [];
        $types = '';
        $params = [];

        if ($q !== '') {
            $like = '%' . $q . '%';
            $where[] = "(f.name LIKE ? OR f.description LIKE ? OR f.subject LIKE ? OR f.file_name LIKE ?)";
            $types .= 'ssss';
            array_push($params, $like, $like, $like, $like);
        }

        if ($type !== 'all') {
            $where[] = "LOWER(f.file_name) LIKE ?";
            $types .= 's';
            $params[] = '%.' . strtolower($type);
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $orderSql = 'ORDER BY f.id DESC';

        if ($sort === 'old') {
            $orderSql = 'ORDER BY f.id ASC';
        }

        $sqlFiles = "SELECT f.* FROM files f $whereSql $orderSql LIMIT 60";
        $fileResult = db_query($conn, $sqlFiles, $types, $params);
    }

    require "assets/php/lang.php";
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('search_title') ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="content-wrapper">
    <?php include "assets/php/ads.php"; ?>
    <div class="main">
            <section class="card search-panel" style="margin-bottom: 32px;">
                <form method="GET" class="search-form-grid">
                    <div class="form-field full-width">
                        <label for="q"><?= t('search_placeholder') ?? 'Keresés...' ?></label>
                        <div class="search-input-wrapper">
                            <input type="text" name="q" id="q" value="<?= htmlspecialchars($q) ?>" class="input" placeholder="Írd be mit keresel...">
                            <button type="submit" class="btn-cta">
                                <svg class="icon icon-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px; height:20px;">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-field">
                        <label><?= t('label_scope') ?? 'Hol keressünk?' ?></label>
                        <select name="scope" class="select">
                            <option value="all" <?= $scope === 'all' ? 'selected' : '' ?>>Mindenhol</option>
                            <option value="files" <?= $scope === 'files' ? 'selected' : '' ?>>Csak fájlok</option>
                            <option value="users" <?= $scope === 'users' ? 'selected' : '' ?>>Csak felhasználók</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label><?= t('label_type') ?? 'Fájltípus' ?></label>
                        <select name="type" class="select">
                            <option value="all" <?= $type === 'all' ? 'selected' : '' ?>>Összes típus</option>
                            <option value="pdf" <?= $type === 'pdf' ? 'selected' : '' ?>>PDF</option>
                            <option value="mp4" <?= $type === 'mp4' ? 'selected' : '' ?>>Videó (MP4)</option>
                            <option value="docx" <?= $type === 'docx' ? 'selected' : '' ?>>Word (DOCX)</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label><?= t('label_sort') ?? 'Rendezés' ?></label>
                        <select name="sort" class="select">
                            <option value="new" <?= $sort === 'new' ? 'selected' : '' ?>>Legújabb elöl</option>
                            <option value="old" <?= $sort === 'old' ? 'selected' : '' ?>>Legrégebbi elöl</option>
                        </select>
                    </div>
                </form>
            </section>

        <?php if ($userResult && $userResult->num_rows > 0): ?>
            <h3><?= t('result_users') ?></h3>
            <div class="list-compact">
                <?php while ($u = $userResult->fetch_assoc()):
                    $usernameRaw = $u['username'];
                    $username = htmlspecialchars($usernameRaw);
                    $fullname = htmlspecialchars(trim(($u['lastname'] ?? '').' '.($u['firstname'] ?? '')));
                    $pfp = "assets/img/default_profile_picture.jpg";
                    if (!empty($u['profile_picture'])) {
                        $fs = __DIR__ . "/users/$usernameRaw/{$u['profile_picture']}";
                        if (file_exists($fs)) {
                            $pfp = "users/$usernameRaw/{$u['profile_picture']}";
                        }
                    }
                ?>
                    <article class="mini-card">
                        <img src="<?= htmlspecialchars($pfp) ?>" alt="" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid var(--stroke);">
                        <div class="mini-main">
                            <h4 class="mini-title"><?= $fullname ?: '@'.$username ?></h4>
                            <p class="mini-meta">@<?= $username ?></p>
                        </div>
                        <a href="profile.php?username=<?= urlencode($usernameRaw) ?>"
                           class="btn-ghost"
                           title="<?= t('open_profile') ?>">
                            <?= t('open_profile') ?>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
        <?php if ($fileResult && $fileResult->num_rows > 0): ?>
            <h3 style="margin-top: 32px;"><?= t('result_files') ?></h3>
            <div class="content-grid">
                <?php while ($f = $fileResult->fetch_assoc()):
                    $uploaderQ = db_query($conn, "SELECT username FROM users WHERE id = ?", "i", [(int)$f['uploaded_by']]);
                    $uploader  = $uploaderQ && $uploaderQ->num_rows ? $uploaderQ->fetch_assoc()['username'] : null;
                    ?>
                    <article class="card">
                        <h4 class="entry-title"><?= htmlspecialchars($f['name']) ?></h4>

                        <?php if ($uploader): ?>
                            <p class="entry-meta">
                                <?= t('label_uploaded_by') ?>
                                <a class="uploader-name" href="profile.php?username=<?= urlencode($uploader) ?>">
                                    @<?= htmlspecialchars($uploader) ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <div style="margin-top: auto; display: flex; gap: 8px;">
                            <a class="note-link" href="note.php?id=<?= (int)$f['id'] ?>">
                                <?= t('btn_details') ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>