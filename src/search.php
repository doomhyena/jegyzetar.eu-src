<?php
require "assets/php/db.php";

$isLoggedIn = isset($_COOKIE['id']);
$user = null;
$notify_number = 0;

if ($isLoggedIn) {
    $uid = (int)$_COOKIE['id'];
    // username-t is kérjük le, hogy navbar/hero tudja használni
    if ($res = $conn->query("SELECT id, username, firstname FROM users WHERE id = $uid LIMIT 1")) {
        $user = $res->fetch_assoc();
    }
    if ($nf = $conn->query("SELECT id FROM notifys WHERE toid = $uid AND readed = 0")) {
        $notify_number = $nf->num_rows;
    }
}

$en_csoportjaim = array();

if ($isLoggedIn) {
    $csoport_lekerdezes = $conn->query("
        SELECT id, name 
        FROM groups 
        WHERE owner_id = $uid
        ORDER BY name ASC
    ");

    if ($csoport_lekerdezes && $csoport_lekerdezes->num_rows > 0) {
        while ($egy_csoport = $csoport_lekerdezes->fetch_assoc()) {
            $en_csoportjaim[] = $egy_csoport;
        }
    }
}

if (isset($_POST['tag_felvetele']) && $isLoggedIn) {

    $meghivott_felhasznalo_id = $_POST['felhasznalo_id'];
    $kivalasztott_csoport_id  = $_POST['csoport_id'];

    if ($meghivott_felhasznalo_id <= 0 || $kivalasztott_csoport_id <= 0) {

        echo "<script>alert('Hibás csoport vagy felhasználó.');</script>";

    } else {

        $csoport_ellenorzes = $conn->query("
            SELECT id 
            FROM groups 
            WHERE id = $kivalasztott_csoport_id 
              AND owner_id = $uid
        ");

        if (!$csoport_ellenorzes || $csoport_ellenorzes->num_rows == 0) {

            echo "<script>alert('Ehhez a csoporthoz nem adhatsz hozzá tagot.');</script>";

        } else {

            $elozo_tagsag = $conn->query("
                SELECT id, status 
                FROM group_members 
                WHERE group_id = $kivalasztott_csoport_id 
                  AND user_id = $meghivott_felhasznalo_id
            ");

            if ($elozo_tagsag && $elozo_tagsag->num_rows > 0) {

                echo "<script>alert('Ez a felhasználó már tag vagy van függőben lévő meghívója.');</script>";

            } else {

			$meghivo_beszur = $conn->query("
				INSERT INTO notifys (fromid, toid, notifytype, group_id, readed)
				VALUES ($uid, $meghivott_felhasznalo_id, 'group_invite', $kivalasztott_csoport_id, 0)
			");

                if ($meghivo_beszur) {
                    echo "<script>alert('Meghívó elküldve a csoportba.');</script>";
                } else {
                    echo "<script>alert('Hiba történt a meghívó mentésekor.');</script>";
                }
            }
        }
    }
}

$q     = trim($_GET['q'] ?? '');
$scope = strtolower($_GET['scope'] ?? 'all');
$type  = strtolower($_GET['type']  ?? 'all');
$sort  = strtolower($_GET['sort']  ?? 'new');

if (!in_array($scope, ['files','users','all'], true)) $scope = 'all';
if (!in_array($type,  ['all','pdf','mp4','docx'], true)) $type = 'all';
if (!in_array($sort,  ['new','old','top'], true)) $sort = 'new';

function esc($conn, $s)  { return $conn->real_escape_string($s); }
function like($conn, $s) { return '%'. $conn->real_escape_string($s) .'%'; }

$fileResult = null;
if ($scope !== 'users') {
    $where = [];

    if ($q !== '') {
        $like = like($conn, $q);
        $where[] = "(f.name LIKE '$like' OR f.description LIKE '$like' OR f.subject LIKE '$like' OR f.file_name LIKE '$like')";
    }
    if ($type !== 'all') {
        $ext = esc($conn, $type);
        $where[] = "LOWER(f.file_name) LIKE '%.{$ext}'";
    }

    $whereSql    = $where ? ('WHERE '.implode(' AND ', $where)) : '';
    $joinRatings = '';
    $selectExtra = '';
    $groupSql    = '';
    $orderSql    = 'ORDER BY f.id DESC';

    if ($sort === 'old') {
        $orderSql = 'ORDER BY f.id ASC';
    } elseif ($sort === 'top') {
        $joinRatings = "LEFT JOIN ratings r ON f.id = r.file_id";
        $selectExtra = ", IFNULL(AVG(r.rating),0) AS avg_rating, COUNT(r.id) AS rating_count";
        $groupSql    = "GROUP BY f.id";
        $orderSql    = "ORDER BY avg_rating DESC, rating_count DESC, f.id DESC";
    }

    $sqlFiles = "
            SELECT f.* $selectExtra
            FROM files f
            $joinRatings
            $whereSql
            $groupSql
            $orderSql
            LIMIT 60
        ";
    $fileResult = $conn->query($sqlFiles);
}

$userResult = null;
if ($scope !== 'files') {
    $userWhere  = '';
    $orderUsers = 'ORDER BY u.username ASC';

    if ($q !== '') {
        $like  = like($conn, $q);
        $start = esc($conn, $q) . '%';
        $userWhere = "
                WHERE
                    u.username  LIKE '$like'
                    OR u.firstname LIKE '$like'
                    OR u.lastname  LIKE '$like'
                    OR CONCAT(u.lastname,  ' ', u.firstname) LIKE '$like'
                    OR CONCAT(u.firstname, ' ', u.lastname)  LIKE '$like'
            ";
        $orderUsers = "
                ORDER BY
                    (CASE
                        WHEN u.username  LIKE '$start' THEN 0
                        WHEN u.firstname LIKE '$start' THEN 1
                        WHEN u.lastname  LIKE '$start' THEN 2
                        ELSE 3
                     END),
                    u.username ASC
            ";
    }

    $sqlUsers = "
            SELECT u.id, u.firstname, u.lastname, u.username, u.profile_picture
            FROM users u
            $userWhere
            $orderUsers
            LIMIT 50
        ";
    $userResult = $conn->query($sqlUsers);
}

require "assets/php/lang.php";
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title><?= t('search_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name="author" content="Baranyai Norbert, Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="main">
    <div class="section-titlebar">
        <h1><?= t('search_title') ?></h1>
        <?php if ($q !== ''): ?>
            <span class="entry-meta">
                <?= t('search_keyword') ?>
                <b><?= htmlspecialchars($q) ?></b>
            </span>
        <?php endif; ?>
    </div>
    <section class="filters card">
        <form class="filters-inner" action="search.php" method="get">
            <input class="input" type="text" name="q"
                   value="<?= htmlspecialchars($q) ?>"
                   placeholder="<?= t('search_placeholder') ?>">
            <select class="select" name="scope" aria-label="<?= t('search_scope_label') ?>">
                <?php
                $scopes = [
                        'all'   => t('search_scope_all'),
                        'files' => t('search_scope_files'),
                        'users' => t('search_scope_users'),
                ];
                foreach ($scopes as $val => $label) {
                    $sel = $scope === $val ? 'selected' : '';
                    echo "<option value=\"$val\" $sel>$label</option>";
                }
                ?>
            </select>
            <select class="select" name="type" aria-label="<?= t('search_type_label') ?>">
                <?php
                $types = [
                        'all' => t('search_type_all'),
                        'pdf' => t('search_type_pdf'),
                        'mp4' => t('search_type_mp4'),
                        'docx'=> t('search_type_docx')
                ];
                foreach ($types as $val => $label) {
                    $sel = $type === $val ? 'selected' : '';
                    echo "<option value=\"$val\" $sel>$label</option>";
                }
                ?>
            </select>
            <select class="select" name="sort" aria-label="<?= t('search_sort_label') ?>">
                <?php
                $sorts = [
                        'new' => t('search_sort_new'),
                        'old' => t('search_sort_old'),
                        'top' => t('search_sort_top')
                ];
                foreach ($sorts as $val => $label) {
                    $sel = $sort === $val ? 'selected' : '';
                    echo "<option value=\"$val\" $sel>$label</option>";
                }
                ?>
            </select>
            <button class="btn-search" type="submit" aria-label="<?= t('search_btn') ?>">
                <svg class="icon icon-search" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M15.5 15.5L21 21M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"
                          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span><?= t('search_btn') ?></span>
            </button>
        </form>
        <div class="hero-pills" style="margin-top:10px">
            <a class="pill" href="search.php?scope=files&type=pdf&sort=new"><?= t('pill_pdf') ?></a>
            <a class="pill" href="search.php?scope=files&type=mp4&sort=new"><?= t('pill_video') ?></a>
            <a class="pill" href="search.php?scope=files&type=docx&sort=new"><?= t('pill_word') ?></a>
            <a class="pill" href="search.php?scope=files&sort=top"><?= t('pill_top_rated') ?></a>
            <a class="pill" href="search.php?scope=users&q=<?= urlencode($q ?: '') ?>"><?= t('pill_users') ?></a>
        </div>
    </section>
    <?php if ($userResult && $userResult->num_rows > 0): ?>
        <div class="section-titlebar"><h3><?= t('result_users') ?></h3></div>
        <div class="list-compact">
            <?php while ($u = $userResult->fetch_assoc()):
                $uid   = (int)$u['id'];
                $unameRaw = $u['username'] ?? '';
                if ($unameRaw === '' ) {
                    $unameRaw = t('unknown_username');
                }
                $uname = htmlspecialchars($unameRaw);

                $full  = htmlspecialchars(trim(($u['lastname'] ?? '').' '.($u['firstname'] ?? '')));
                $pfp = "assets/img/default_profile_picture.jpg";
                if (!empty($u['username']) && !empty($u['profile_picture'])) {
                    $fs  = __DIR__ . "/users/{$u['username']}/{$u['profile_picture']}";
                    $pub = "users/{$u['username']}/{$u['profile_picture']}";
                    if (file_exists($fs)) $pfp = $pub;
                }
                ?>
                <article class="mini-card">
                    <div class="mini-main" style="display:flex;align-items:center;gap:12px;">
                        <img src="<?= htmlspecialchars($pfp) ?>" alt="" style="width:42px;height:42px;border-radius:999px;object-fit:cover;border:1px solid var(--stroke)">
                        <div>
                            <h4 class="mini-title"><?= $full !== '' ? $full : '@'.$uname ?></h4>
                            <p class="mini-meta">@<?= $uname ?></p>
                        </div>
                    </div>
                    <a class="mini-download"
                       href="profile.php?userid=<?= $uid ?>"
                       title="<?= t('open_profile') ?>"
                       aria-label="<?= t('open_profile') ?>">
                        <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 3v10m0 0l-4-4m4 4l4-4M4 17v3h16v-3"></path>
                        </svg>
                    </a>
					
					<?php
					// bejelentkezett felhasználó ID-ja a cookie-ból
					$bejelentkezett_id = isset($_COOKIE['id']) ? $_COOKIE['id'] : 0;
					?>

					<?php if ($isLoggedIn && count($en_csoportjaim) > 0 && $uid != $bejelentkezett_id): ?>
						<form method="post" style="display:flex;flex-direction:column;gap:4px;align-items:flex-end;">
						<input type="hidden" name="felhasznalo_id" value="<?= $uid ?>">
						<select name="csoport_id" class="select" style="max-width:180px;">
					<?php foreach ($en_csoportjaim as $egy_csoport): ?>
						<option value="<?= $egy_csoport['id'] ?>">
							<?= htmlspecialchars($egy_csoport['name']) ?>
						</option>
					<?php endforeach; ?>
					</select>
				<button type="submit" name="tag_felvetele" class="btn-ghost">
					<?= t('add_to_group', 'Tag felvétele') ?>
					</button>
					</form>
				<?php endif; ?>
					
                </article>
            <?php endwhile; ?>
        </div>
    <?php elseif ($scope === 'users' && $q !== ''): ?>
        <div class="card"><p><?= t('empty_no_users') ?></p></div>
    <?php endif; ?>
    <?php if ($fileResult && $fileResult->num_rows > 0): ?>
        <div class="section-titlebar" style="margin-top:14px"><h3><?= t('result_files') ?></h3></div>
        <div class="content-grid grid-large">
            <?php while ($f = $fileResult->fetch_assoc()):
                $uploader_q = $conn->query("SELECT username FROM users WHERE id=".(int)$f['uploaded_by']." LIMIT 1");
                $uploader   = $uploader_q ? $uploader_q->fetch_assoc() : ['username'=>null];

                $file_id   = (int)$f['id'];
                $file_name = htmlspecialchars($f['name']);
                $usernameRaw  = $uploader['username'] ?? '';
                if ($usernameRaw === '' ) {
                    $usernameRaw = t('unknown_username');
                }
                $username  = htmlspecialchars($usernameRaw);

                $ext       = strtolower(pathinfo($f['file_name'], PATHINFO_EXTENSION));
                $user_dir  = "users/" . ($uploader['username'] ?? '') . "/";
                $safe_path = $user_dir . $f['file_name'];
                ?>
                <article class="card">
                    <header class="card-head">
                        <h4 class="entry-title"><?= $file_name ?></h4>
                        <a class="uploader-name" href="profile.php?userid=<?= (int)$f['uploaded_by'] ?>">@<?= $username ?></a>
                        <a class="note-desc-btn" href="note.php?id=<?= (int)$f['id'] ?>">
                            <?= t('btn_details') ?>
                        </a>
                        <a class="entry-download-btn" href="assets/php/download.php?id=<?= $file_id ?>">
                            <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 3v10m0 0l-4-4m4 4l4-4M4 17v3h16v-3"></path>
                            </svg>
                            <?= t('btn_download') ?>
                        </a>
                    </header>
                    <?php if ($ext === 'docx'): ?>
                        <p><b><?= t('docx_warning') ?></b></p>
                    <?php elseif ($ext === 'mp4'): ?>
                        <video controls class="file-preview">
                            <source src="<?= htmlspecialchars($safe_path) ?>" type="video/mp4">
                            <?= t('video_fallback') ?>
                        </video>
                    <?php elseif ($ext === 'pdf'): ?>
                        <iframe src="<?= htmlspecialchars($safe_path) ?>" width="100%" height="460"></iframe>
                    <?php endif; ?>

                    <?php if ($sort === 'top'): ?>
                        <p class="entry-meta">
                            <?= t('rating_average') ?>
                            <b><?= number_format((float)($f['avg_rating'] ?? 0), 2) ?></b>
                            · <?= (int)($f['rating_count'] ?? 0) ?> <?= t('rating_count_suffix') ?>
                        </p>
                    <?php endif; ?>
                </article>
            <?php endwhile; ?>
        </div>
    <?php elseif ($scope !== 'users'): ?>
        <div class="card"><p><?= t('empty_no_files_filter') ?></p></div>
    <?php endif; ?>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>