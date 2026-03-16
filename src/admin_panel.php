<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    if (!isset($_COOKIE['id']) || !ctype_digit($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit();
    }

    $userId = (int)$_COOKIE['id'];

    $result = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$userId]);
    $current_user = $result->fetch_assoc();

    if (!$current_user) {
        header("Location: reglog.php");
        exit();
    }

    if (!isset($current_user['admin']) || (int)$current_user['admin'] !== 1) {
        http_response_code(403);
        exit('Hozzáférés megtagadva. Nincs admin jogosultság.');
    }

    $user = $current_user;
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title>Admin Panel</title>
    <meta charset='UTF-8'>
    <meta name='description' content='Iskolai jegyzeteket megosztó oldal'>
    <meta name='keywords' content='iskola, jegyzet, megosztás, tanulás'>
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
    <style>
        table { width: 100%; border-collapse: collapse; margin: 18px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid var(--stroke); }
        th { background: rgba(255,255,255,.06); font-weight: 800; color: var(--primary); }
        tr:hover { background: rgba(255,255,255,.03); }
        a { color: var(--accent); text-decoration: none; font-weight: 700; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body class="no-ads admin-page">
<?php
    include 'assets/php/navbar.php';

    if ($current_user['admin'] != 1) {
        echo "<div class='main'>";
            echo "<div class='card'><h2>Nincs jogosultságod az admin felület megtekintéséhez.</h2></div>";
        echo "</div>";
        include 'assets/php/footer.php';
        echo '</body></html>';
        exit();
    }

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role_action']) && $_POST['role_action'] === 'set_role') {

    $target_user_id = isset($_POST['target_user_id']) ? (int)$_POST['target_user_id'] : 0;
    $new_role = (string)($_POST['new_role'] ?? 'diak'); // diak | tanar | admin

    if ($target_user_id > 0) {


        if ($target_user_id === (int)$current_user['id'] && $new_role !== 'admin') {
            echo "<script>alert('A saját admin rangodat nem veheted el!');</script>";
        } else {

            $set_admin = 0;
            $set_teacher = 0;

            if ($new_role === 'admin') {
                $set_admin = 1;   // admin=1, teacher=0
                $set_teacher = 0;
            } elseif ($new_role === 'tanar') {
                $set_admin = 0;
                $set_teacher = 1; // teacher=1, admin=0
            } else {
                $set_admin = 0;
                $set_teacher = 0; // diák
            }

            db_stmt(
                $conn,
                "UPDATE users SET admin = ?, teacher = ? WHERE id = ? LIMIT 1",
                "iii",
                [$set_admin, $set_teacher, $target_user_id]
            )->close();

            echo "<script>alert('Rang frissítve!'); location.href='admin_panel.php';</script>";
            exit();
        }
    }
}

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_user_badge') {
        $user_id  = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $badge_id = isset($_POST['badge_id']) ? intval($_POST['badge_id']) : 0;
        $adminId  = intval($current_user['id']);

        if ($user_id > 0 && $badge_id > 0) {
            $exists = db_query($conn, "SELECT id FROM user_badges WHERE user_id = ? AND badge_id = ? LIMIT 1", "ii", [$user_id, $badge_id]);

            if ($exists && $exists->num_rows == 0) {
                db_exec($conn, "INSERT INTO user_badges (user_id, badge_id, granted_by)  VALUES (?, ?, ?)", "iii", [$user_id, $badge_id, $adminId]);
            }
        }

        echo "<script>location.href='admin_panel.php';</script>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['badge_action'])) {
        $action = $_POST['badge_action'];

        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $icon = isset($_POST['icon']) ? trim($_POST['icon']) : '';

        if ($action === 'create') {
            if ($name !== '' && $slug !== '') {
                $descParam = ($description !== '') ? $description : null;
                $iconParam = ($icon !== '') ? $icon : null;

                db_exec($conn, "INSERT INTO badges (name, slug, description, icon)  VALUES (?, ?, ?, ?)", "ssss", [$name, $slug, $descParam, $iconParam]);
            }
        } elseif ($action === 'update' && isset($_POST['badge_id'])) {
            $id = intval($_POST['badge_id']);
            if ($id > 0 && $name !== '' && $slug !== '') {
                $descParam = ($description !== '') ? $description : null;
                $iconParam = ($icon !== '') ? $icon : null;

                db_exec($conn, "UPDATE badges  SET name = ?, slug = ?, description = ?, icon = ?  WHERE id = ?  LIMIT 1", "ssssi", [$name, $slug, $descParam, $iconParam, $id]
                );
            }
        }
        echo "<script>location.href='admin_panel.php';</script>";
        exit();
    }

    if (isset($_GET['delete_type']) && isset($_GET['delete_id'])) {
        $type = $_GET['delete_type'];
        $id = intval($_GET['delete_id']);

        switch ($type) {
            case 'user':
                if ($id != $current_user['id']) {
                    db_exec($conn, "DELETE FROM users WHERE id = ?", "i", [$id]);
                    db_exec($conn, "DELETE FROM files WHERE uploaded_by = ?", "i", [$id]);
                    db_exec($conn, "DELETE FROM comments WHERE userid = ?", "i", [$id]);
                }
                break;

            case 'file':
                db_exec($conn, "DELETE FROM files WHERE id = ?", "i", [$id]);
                db_exec($conn, "DELETE FROM comments WHERE postid = ?", "i", [$id]);
                break;

            case 'comment':
                db_exec($conn, "DELETE FROM comments WHERE id = ?", "i", [$id]);
                break;

            case 'category':
                if (isset($_GET['subject'])) {
                    $subject = $_GET['subject'];
                    db_exec($conn, "UPDATE files SET subject = '' WHERE subject = ?", "s", [$subject]);
                }
                break;

            case 'user_badge':
                db_exec($conn, "DELETE FROM user_badges WHERE id = ?", "i", [$id]);
                break;

            case 'badge':
                db_exec($conn, "DELETE FROM badges WHERE id = ?", "i", [$id]);
                break;
        }

        echo "<script>location.href='admin_panel.php';</script>";
        exit();
    }

    if (isset($_GET['css_action']) && isset($_GET['css_id'])) {
        $action = $_GET['css_action'];
        $css_id = intval($_GET['css_id']);
        $adminId = intval($current_user['id']);
        $now = date('Y-m-d H:i:s');

        if ($action === 'approve') {
            $res = db_query($conn, "SELECT * FROM user_custom_css_requests WHERE id = ? LIMIT 1", "i", [$css_id]);

            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $userId = intval($row['user_id']);

                db_stmt($conn, "UPDATE user_custom_css_requests  SET status = ?, reviewed_at = ?, reviewed_by = ?  WHERE id = ?  LIMIT 1", "ssii", ['approved', $now, $adminId, $css_id])->close();
                db_stmt($conn, "UPDATE user_custom_css_requests  SET status = ?  WHERE user_id = ? AND status = 'pending' AND id <> ?", "sii", ['rejected', $userId, $css_id])->close();
            }
        } elseif ($action === 'reject') {
            db_stmt($conn, "UPDATE user_custom_css_requests  SET status = ?, reviewed_at = ?, reviewed_by = ?  WHERE id = ?  LIMIT 1", "ssii", ['rejected', $now, $adminId, $css_id])->close();
        }

        echo "<script>location.href='admin_panel.php';</script>";
        exit();
    }
	
	if (isset($_GET['group_action']) && isset($_GET['group_id'])) {
		$action = $_GET['group_action']; 
		$group_id = (int)$_GET['group_id'];
		$adminId = (int)$current_user['id'];
		$now = date('Y-m-d H:i:s');

		if ($group_id > 0) {
			
			if ($action === 'approve') {
				db_stmt($conn,"UPDATE groups SET status=?, reviewed_at=?, reviewed_by=? WHERE id=? LIMIT 1","ssii",['approved', $now, $adminId, $group_id])->close();
				
        } elseif ($action === 'reject') {
            db_stmt($conn,"UPDATE groups SET status=?, reviewed_at=?, reviewed_by=? WHERE id=? LIMIT 1", "ssii", ['rejected', $now, $adminId, $group_id])->close();
        }
    }

		echo "<script>location.href='admin_panel.php';</script>";
		exit();
	}

	if (isset($_POST['report_action']) && isset($_POST['report_id'])) {
		$reportId = (int)$_POST['report_id'];
		$action = $_POST['report_action'];
		$adminId = (int)$current_user['id'];

		if (in_array($action, ['resolve', 'dismiss'], true)) {
			$newStatus = ($action === 'resolve') ? 'resolved' : 'dismissed';

			db_stmt($conn, "UPDATE reports SET status = ?, handled_by = ?, handled_at = NOW() WHERE id = ?", "sii", [$newStatus, $adminId, $reportId]);
		}

		echo "<script>location.href='admin_panel.php';</script>";
		exit();
	}

    if (isset($_POST['create_reg_code'])) {
        $code = trim($_POST['code'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $maxUses = $_POST['max_uses'] ?? '';
        $expiresRaw  = trim($_POST['expires_at'] ?? '');
        $maxUsesVal = null;
        $expiresAt = null;

        if ($code === '') {
            echo "<script>alert('A kód mező nem lehet üres.');</script>";
        } else {
            if ($maxUses !== '' && ctype_digit($maxUses)) {
                $maxUsesVal = (int)$maxUses;
            }

            if ($expiresRaw !== '') {
                $expiresAt = str_replace('T', ' ', $expiresRaw) . ':00';
            }

            db_stmt($conn, "INSERT INTO reg_codes (code, description, max_uses, expires_at, active) VALUES (?, ?, ?, ?, 1)", "ssis", [$code, $description !== '' ? $description : null, $maxUsesVal, $expiresAt])->close();

            echo "<script>alert('Regisztrációs kód létrehozva.');</script>";
        }
    }

    if (isset($_POST['deactivate_reg_code'])) {
        $id = (int)($_POST['reg_code_id'] ?? 0);
        if ($id > 0) {
            db_stmt($conn, "UPDATE reg_codes SET active = 0 WHERE id = ?", "i", [$id])->close();
        }
    }

    if (isset($_POST['activate_reg_code'])) {
        $id = (int)($_POST['reg_code_id'] ?? 0);
        if ($id > 0) {
            db_stmt($conn, "UPDATE reg_codes SET active = 1 WHERE id = ?", "i", [$id])->close();
        }
    }

    if (isset($_POST['delete_reg_code'])) {
        $id = (int)($_POST['reg_code_id'] ?? 0);
        if ($id > 0) {
            db_stmt($conn, "DELETE FROM reg_codes WHERE id = ?", "i", [$id])->close();
        }
    }

    $regCodes = db_query($conn, "SELECT * FROM reg_codes ORDER BY id DESC");
    $users = $conn->query("SELECT * FROM users ORDER BY id DESC");
    $files = $conn->query("SELECT * FROM files ORDER BY id DESC");
    $comments = $conn->query("SELECT comments.*, users.username FROM comments LEFT JOIN users ON comments.userid=users.id ORDER BY comments.id DESC");
    $categories = $conn->query("SELECT DISTINCT subject FROM files WHERE subject != '' ORDER BY subject ASC");
    $css_requests = $conn->query("SELECT r.*, u.username, rv.username AS reviewer_name FROM user_custom_css_requests r JOIN users u ON r.user_id = u.id LEFT JOIN users rv ON r.reviewed_by = rv.id ORDER BY (r.status = 'pending') DESC, r.id DESC");
	$group_requests = $conn->query("SELECT g.*, u.username AS owner_name, rv.username AS reviewer_name FROM groups g LEFT JOIN users u ON u.id = g.owner_id LEFT JOIN users rv ON rv.id = g.reviewed_by ORDER BY (g.status = 'pending') DESC, g.id DESC");
    $user_badges = $conn->query("SELECT ub.*, u.username, b.name AS badge_name FROM user_badges ub JOIN users u ON ub.user_id = u.id JOIN badges b ON ub.badge_id = b.id ORDER BY ub.id DESC");
    $badge_options = $conn->query("SELECT id, name FROM badges ORDER BY name ASC");
    $user_options  = $conn->query("SELECT id, username FROM users ORDER BY username ASC");
    $badges = $conn->query("SELECT * FROM badges ORDER BY id DESC");
    $reports = $conn->query("SELECT r.*, u.username AS reporter_name FROM reports r LEFT JOIN users u ON u.id = r.reporter_id ORDER BY (r.status = 'open') DESC, r.created_at DESC");
?>
<div class="main w-full max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6">
    <h1 class="text-2xl md:text-3xl lg:text-4xl mb-6">Admin Panel</h1>
    <section class="card p-4 md:p-6 mb-6 overflow-x-auto">
        <h2 class="text-xl md:text-2xl mb-4">Felhasználók kezelése</h2>
        <table>
            <tr>
                <th>ID</th><th>Név</th><th>Felhasználónév</th><th>Email</th><th>Admin</th><th>Művelet</th>
            </tr>
            <?php while($user = $users->fetch_assoc()) { ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['lastname'] . ' ' . $user['firstname']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars(mask_email($user['email'])) ?></td>
                    <td><?= $user['admin'] == 1 ? 'Igen' : 'Nem' ?></td>
                    <td>
                        <?php if ($user['id'] != $current_user['id']) { ?>
                            <a href="?delete_type=user&delete_id=<?= $user['id'] ?>" onclick="return confirm('Biztosan törlöd ezt a felhasználót?')">Törlés</a>
                        <?php } else { ?>
                            Saját fiók
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
	
	<section class="card p-4 md:p-6 mb-6">
		<h2 class="text-xl md:text-2xl mb-4">Rangok kezelése</h2>

		<input class="input w-full" type="text" id="admin-user-search" placeholder="Keress felhasználóra">
		<div id="admin-user-results"></div>

		<div id="search" style="margin-top:12px;">
		<small class="text-xs opacity-75">Írj a keresőbe, és itt megjelennek a találatok.</small>
		</div>
	</section>
	
    <section class="card p-4 md:p-6 mb-6 overflow-x-auto">
        <h2 class="text-xl md:text-2xl mb-4">Fájlok kezelése</h2>
        <table>
            <tr>
                <th>ID</th><th>Név</th><th>Leírás</th><th>Kategória</th><th>Feltöltő</th><th>Művelet</th>
            </tr>
            <?php while($f = $files->fetch_assoc()) {
                $uploaderUsername = 'Ismeretlen';
                if (!empty($f['uploaded_by'])) {
                    $uid = (int)$f['uploaded_by'];
                    $uploaderRes = db_query($conn, "SELECT username FROM users WHERE id = ? LIMIT 1", "i", [$uid]);
                    $uploaderRow = $uploaderRes ? $uploaderRes->fetch_assoc() : null;
                    if ($uploaderRow && isset($uploaderRow['username'])) {
                        $uploaderUsername = $uploaderRow['username'];
                    }
                }
                ?>
                <tr>
                    <td><?= $f['id'] ?></td>
                    <td><?= htmlspecialchars($f['name']) ?></td>
                    <td><?= htmlspecialchars($f['description']) ?></td>
                    <td><?= htmlspecialchars($f['subject']) ?></td>
                    <td><?= htmlspecialchars($uploaderUsername) ?></td>
                    <td>
                        <a href="?delete_type=file&delete_id=<?= $f['id'] ?>" onclick="return confirm('Biztosan törlöd ezt a fájlt?')">Törlés</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
    <section class="card p-4 md:p-6 mb-6 overflow-x-auto">
        <h2 class="text-xl md:text-2xl mb-4">Kommentek kezelése</h2>
        <table>
            <tr>
                <th>ID</th><th>Felhasználó</th><th>Fájl ID</th><th>Szöveg</th><th>Művelet</th>
            </tr>
            <?php while($c = $comments->fetch_assoc()) { ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['username']) ?></td>
                    <td><?= $c['postid'] ?></td>
                    <td><?= htmlspecialchars($c['text']) ?></td>
                    <td>
                        <a href="?delete_type=comment&delete_id=<?= $c['id'] ?>" onclick="return confirm('Biztosan törlöd ezt a kommentet?')">Törlés</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
    <section class="card p-4 md:p-6 mb-6 overflow-x-auto">
        <h2 class="text-xl md:text-2xl mb-4">Kategóriák kezelése</h2>
        <table>
            <tr>
                <th>Kategória</th><th>Művelet</th>
            </tr>
            <?php while($cat = $categories->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($cat['subject']) ?></td>
                    <td>
                        <a href="?delete_type=category&subject=<?= urlencode($cat['subject']) ?>" onclick="return confirm('Biztosan törlöd ezt a kategóriát? (A fájlokból eltávolítja a kategóriát)')">Törlés</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
    <section class="card p-4 md:p-6 mb-6 overflow-x-auto">
        <h2 class="text-xl md:text-2xl mb-4">Profil CSS kérések</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Felhasználó</th>
                <th>Státusz</th>
                <th>Létrehozva</th>
                <th>Review</th>
                <th>CSS</th>
                <th>Művelet</th>
            </tr>
            <?php while($css = $css_requests->fetch_assoc()) { ?>
                <tr>
                    <td><?= $css['id'] ?></td>
                    <td><?= htmlspecialchars($css['username']) ?></td>
                    <td><?= htmlspecialchars($css['status']) ?></td>
                    <td><?= htmlspecialchars($css['created_at']) ?></td>
                    <td>
                        <?php if (!empty($css['reviewed_at'])): ?>
                            <?= htmlspecialchars($css['reviewed_at']) ?><br>
                            <?= htmlspecialchars($css['reviewer_name'] ?? '') ?>
                        <?php else: ?>
                            –
                        <?php endif; ?>
                    </td>
                    <td>
                        <pre style="max-width:300px; max-height:150px; overflow:auto; white-space:pre-wrap;"><?= htmlspecialchars($css['css']) ?></pre>
                    </td>
                    <td>
                        <?php if ($css['status'] === 'pending') { ?>
                            <a href="?css_action=approve&css_id=<?= $css['id'] ?>" onclick="return confirm('Biztosan jóváhagyod ezt a CSS-t?')">Jóváhagyás</a><br>
                            <a href="?css_action=reject&css_id=<?= $css['id'] ?>" onclick="return confirm('Biztosan elutasítod ezt a CSS-t?')">Elutasítás</a>
                        <?php } else { ?>
                            Nincs művelet
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
	
	<section class="card p-4 md:p-6 mb-6 overflow-x-auto">
    <h2 class="text-xl md:text-2xl mb-4">Csoportok jóváhagyása</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Név</th>
            <th>Leírás</th>
            <th>Tulaj</th>
            <th>Privát</th>
            <th>Státusz</th>
            <th>Review</th>
            <th>Művelet</th>
        </tr>

        <?php if ($group_requests && $group_requests->num_rows > 0): ?>
            <?php while($g = $group_requests->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$g['id'] ?></td>
                    <td><?= htmlspecialchars($g['name']) ?></td>
                    <td><?= htmlspecialchars($g['description'] ?? '') ?></td>
                    <td><?= htmlspecialchars($g['owner_name'] ?? 'ismeretlen') ?></td>
                    <td><?= ((int)$g['is_private'] === 1) ? 'Igen' : 'Nem' ?></td>
                    <td><?= htmlspecialchars($g['status']) ?></td>
                    <td>
                        <?php if (!empty($g['reviewed_at'])): ?>
                            <?= htmlspecialchars($g['reviewed_at']) ?><br>
                            <?= htmlspecialchars($g['reviewer_name'] ?? '') ?>
                        <?php else: ?>
                            –
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($g['status'] === 'pending'): ?>
                            <a href="?group_action=approve&group_id=<?= (int)$g['id'] ?>" onclick="return confirm('Jóváhagyod ezt a csoportot?')">Jóváhagyás</a><br>
                            <a href="?group_action=reject&group_id=<?= (int)$g['id'] ?>" onclick="return confirm('Elutasítod ezt a csoportot?')">Elutasítás</a>
                        <?php else: ?>
                            Nincs művelet
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">Nincs csoport.</td></tr>
        <?php endif; ?>
    </table>
	</section>
    <section class="card p-4 md:p-6 mb-6">
        <h2 class="text-xl md:text-2xl mb-4">User badge-ek kezelése</h2>
        <h3 class="text-lg md:text-xl mb-3">Új badge hozzárendelése</h3>
        <form method="post" action="admin_panel.php" class="mb-4 flex flex-col md:flex-row gap-3 items-end">
            <input type="hidden" name="action" value="add_user_badge">
            <div class="flex flex-col gap-2">
                <label for="user_id" class="text-sm md:text-base font-semibold">Felhasználó:</label>
                <select name="user_id" id="user_id" class="profile-theme-select text-sm md:text-base" required>
                    <option value="">Válassz felhasználót</option>
                    <?php while ($u = $user_options->fetch_assoc()) { ?>
                        <option value="<?= (int)$u['id'] ?>">
                            <?= htmlspecialchars($u['username']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="flex flex-col gap-2">
                <label for="badge_id" class="text-sm md:text-base font-semibold">Badge:</label>
                <select name="badge_id" id="badge_id" class="profile-theme-select text-sm md:text-base" required>
                    <option value="">Válassz badge-et</option>
                    <?php while ($b = $badge_options->fetch_assoc()) { ?>
                        <option value="<?= (int)$b['id'] ?>">
                            <?= htmlspecialchars($b['name']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn-cta text-sm md:text-base">Hozzáadás</button>
        </form>
        <h3 class="text-lg md:text-xl mb-3">Meglévő user_badge-ek</h3>
        <div class="badge-table-wrap">
            <table class="badge-list-table">
                <tr>
                    <th>ID</th>
                    <th>Felhasználó</th>
                    <th>Badge</th>
                    <th>Adta</th>
                    <th>Dátum</th>
                    <th>Művelet</th>
                </tr>
                <?php while ($ub = $user_badges->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $ub['id'] ?></td>
                        <td><?= htmlspecialchars($ub['username']) ?></td>
                        <td><?= htmlspecialchars($ub['badge_name']) ?></td>
                        <td>
                            <?php
                            if (!empty($ub['granted_by'])) {
                                $gbUsername = 'ismeretlen';
                                $gbId = (int)$ub['granted_by'];

                                $gbRes = db_query(
                                    $conn,
                                    "SELECT username FROM users WHERE id = ? LIMIT 1",
                                    "i",
                                    [$gbId]
                                );
                                $gbRow = $gbRes ? $gbRes->fetch_assoc() : null;
                                if ($gbRow && isset($gbRow['username'])) {
                                    $gbUsername = $gbRow['username'];
                                }
                                echo htmlspecialchars($gbUsername);
                            } else {
                                echo '—';
                            }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($ub['granted_at']) ?></td>
                        <td>
                            <a href="?delete_type=user_badge&delete_id=<?= $ub['id'] ?>"
                               onclick="return confirm('Biztosan törlöd ezt a user_badge sort?')">
                                Törlés
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </section>
    <section class="card p-4 md:p-6 mb-6">
        <div class="badges-header mb-4">
            <div>
                <h2 class="text-xl md:text-2xl">Badge-ek kezelése</h2>
                <small class="text-xs md:text-sm opacity-75">Kis jelvények, amikkel jutalmazhatod a felhasználókat.</small>
            </div>
        </div>

        <h3 class="text-lg md:text-xl mb-3">Új badge létrehozása</h3>
        <form method="post" action="admin_panel.php" class="badge-form-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <input type="hidden" name="badge_action" value="create">

            <div class="form-field">
                <label for="badge_name" class="text-sm md:text-base font-semibold">Név</label>
                <input type="text" id="badge_name" name="name" class="input w-full text-sm md:text-base"
                       required placeholder="pl. Szuper segítő">
            </div>

            <div class="form-field">
                <label for="badge_slug" class="text-sm md:text-base font-semibold">Slug</label>
                <input type="text" id="badge_slug" name="slug" class="input w-full text-sm md:text-base"
                       required placeholder="pl. super-helper">
            </div>

            <div class="form-field">
                <label for="badge_desc" class="text-sm md:text-base font-semibold">Leírás</label>
                <input type="text" id="badge_desc" name="description" class="input w-full text-sm md:text-base"
                       placeholder="pl. Sok jegyzetet töltött fel">
            </div>

            <div class="form-field">
                <label for="badge_icon" class="text-sm md:text-base font-semibold">Ikon (emoji / rövid kód)</label>
                <input type="text" id="badge_icon" name="icon" class="input w-full text-sm md:text-base"
                       placeholder="pl. ⭐ vagy trophy">
            </div>

            <div class="form-field flex items-end">
                <button type="submit" class="btn-cta w-full text-sm md:text-base">
                    Hozzáadás
                </button>
            </div>
        </form>

        <h3 class="text-lg md:text-xl mb-3">Meglévő badge-ek</h3>
        <div class="overflow-x-auto">
        <table class="badge-list-table">
            <tr>
                <th>ID</th>
                <th>Előnézet</th>
                <th>Név</th>
                <th>Slug</th>
                <th>Leírás</th>
                <th>Ikon</th>
                <th>Művelet</th>
            </tr>
            <?php while($b = $badges->fetch_assoc()) { ?>
                <tr>
                    <form method="post" action="admin_panel.php">
                        <input type="hidden" name="badge_action" value="update">
                        <input type="hidden" name="badge_id" value="<?= $b['id'] ?>">

                        <td><?= $b['id'] ?></td>
                        <td>
                                <span class="badge-chip">
                                    <?php if (!empty($b['icon'])): ?>
                                        <span class="badge-chip-icon"><?= htmlspecialchars($b['icon']) ?></span>
                                    <?php endif; ?>
                                    <span><?= htmlspecialchars($b['name']) ?></span>
                                    <span class="badge-chip-slug"><?= htmlspecialchars($b['slug']) ?></span>
                                </span>
                        </td>
                        <td>
                            <input type="text" name="name" class="input"
                                   value="<?= htmlspecialchars($b['name']) ?>" required>
                        </td>
                        <td>
                            <input type="text" name="slug" class="input"
                                   value="<?= htmlspecialchars($b['slug']) ?>" required>
                        </td>
                        <td>
                            <input type="text" name="description" class="input"
                                   value="<?= htmlspecialchars($b['description'] ?? '') ?>">
                        </td>
                        <td>
                            <input type="text" name="icon" class="input"
                                   value="<?= htmlspecialchars($b['icon'] ?? '') ?>">
                        </td>
                        <td>
                            <button type="submit" class="btn-cta btn-ghost">Mentés</button>
                            <a href="?delete_type=badge&delete_id=<?= $b['id'] ?>"
                               onclick="return confirm('Biztosan törlöd ezt a badge-et? A hozzárendelt user_badge-ek is törlődnek.')">
                                Törlés
                            </a>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        </table>
        </div>
    </section>
    <section class="card p-4 md:p-6 mb-6 overflow-x-auto" id="reports">
        <h2 class="text-xl md:text-2xl mb-4">Jelentések</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Jelentő</th>
                <th>Típus</th>
                <th>Cél</th>
                <th>Indok</th>
                <th>Állapot</th>
                <th>Dátum</th>
                <th>Művelet</th>
            </tr>
            <?php if ($reports && $reports->num_rows > 0): ?>
                <?php while ($rep = $reports->fetch_assoc()): ?>
                    <?php
                        $targetId   = (int)$rep['target_id'];
                        $targetType = $rep['target_type'];

                        $targetUrl   = '#';
                        $targetLabel = 'Ismeretlen cél';

                        if ($targetType === 'user') {
                            $uRes = db_query($conn, "SELECT username FROM users WHERE id = ? LIMIT 1", "i", [$targetId]);
                            $uRow = $uRes ? $uRes->fetch_assoc() : null;

                            if ($uRow && isset($uRow['username'])) {
                                $targetUrl   = 'profile.php?user=' . urlencode($uRow['username']);
                                $targetLabel = 'Felhasználó: @' . $uRow['username'];
                            } else {
                                $targetUrl   = '#';
                                $targetLabel = 'Felhasználó ID: ' . $targetId;
                            }

                        } elseif ($targetType === 'group') {
                            $targetUrl = 'group.php?id=' . $targetId;

                            $gRes = db_query($conn, "SELECT name FROM groups WHERE id = ? LIMIT 1", "i", [$targetId]);
                            $gRow = $gRes ? $gRes->fetch_assoc() : null;

                            if ($gRow && isset($gRow['name'])) {
                                $targetLabel = 'Csoport: ' . $gRow['name'];
                            } else {
                                $targetLabel = 'Csoport ID: ' . $targetId;
                            }

                        } elseif ($targetType === 'note') {
                            $targetUrl = 'note.php?id=' . $targetId;

                            $nRes = db_query($conn, "SELECT name FROM files WHERE id = ? LIMIT 1", "i", [$targetId]);
                            $nRow = $nRes ? $nRes->fetch_assoc() : null;

                            if ($nRow && isset($nRow['name'])) {
                                $targetLabel = 'Jegyzet: ' . $nRow['name'];
                            } else {
                                $targetLabel = 'Jegyzet ID: ' . $targetId;
                            }
                        }
                    ?>
                    <tr>
                        <td><?= (int)$rep['id'] ?></td>
                        <td><?= htmlspecialchars($rep['reporter_name'] ?? 'ismeretlen') ?></td>
                        <td><?= htmlspecialchars($rep['target_type']) ?></td>
                        <td>
                            <?php if ($targetUrl !== '#'): ?>
                                <a href="<?= htmlspecialchars($targetUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                                    <?= htmlspecialchars($targetLabel, ENT_QUOTES, 'UTF-8') ?>
                                </a>
                                <br>
                                <small>ID: <?= $targetId ?></small>
                            <?php else: ?>
                                <?= htmlspecialchars($targetLabel, ENT_QUOTES, 'UTF-8') ?>
                            <?php endif; ?>
                        </td>
                        <td><?= nl2br(htmlspecialchars($rep['reason'])) ?></td>
                        <td>
                            <?php
                                if ($rep['status'] === 'open') {
                                    echo '<span class="badge badge-active">Nyitott</span>';
                                } elseif ($rep['status'] === 'resolved') {
                                    echo '<span class="badge badge-inactive">Megoldva</span>';
                                } else {
                                    echo '<span class="badge badge-expired">Elutasítva</span>';
                                }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($rep['created_at']) ?></td>
                        <td>
                            <?php if ($rep['status'] === 'open'): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="report_id" value="<?= (int)$rep['id'] ?>">
                                    <button type="submit" name="report_action" value="resolve" class="btn-cta">
                                        Elfogad
                                    </button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="report_id" value="<?= (int)$rep['id'] ?>">
                                    <button type="submit" name="report_action" value="dismiss" class="btn-ghost">
                                        Elutasít
                                    </button>
                                </form>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Nincs még jelentés.</td>
                </tr>
            <?php endif; ?>
        </table>
    </section>

    <section class="card p-4 md:p-6 mb-6" id="reg-codes">
        <div class="regcodes-header mb-4">
            <div>
                <h2 class="text-xl md:text-2xl">Regisztrációs kódok</h2>
                <small class="text-xs md:text-sm opacity-75">Belépőkódok osztályoknak / eseményeknek – látható statisztikával.</small>
            </div>
        </div>

        <h3 class="text-lg md:text-xl mb-3">Új kód létrehozása</h3>
        <form method="post" class="regcodes-form grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="form-field">
                <label for="code" class="text-sm md:text-base font-semibold">Kód</label>
                <input type="text" name="code" id="code" class="input w-full text-sm md:text-base" required placeholder="pl. 10A-2024">
            </div>

            <div class="form-field">
                <label for="description" class="text-sm md:text-base font-semibold">Leírás</label>
                <input type="text" name="description" id="description" class="input w-full text-sm md:text-base" placeholder="pl. 10.A osztály, verseny, teszt stb.">
            </div>

            <div class="form-field">
                <label for="max_uses" class="text-sm md:text-base font-semibold">Max. felhasználás</label>
                <input type="number" name="max_uses" id="max_uses" class="input w-full text-sm md:text-base" min="1" placeholder="Üres = végtelen">
            </div>

            <div class="form-field">
                <label for="expires_at" class="text-sm md:text-base font-semibold">Lejárat</label>
                <input type="datetime-local" name="expires_at" id="expires_at" class="input w-full text-sm md:text-base" placeholder="Üres = soha">
            </div>

            <div class="form-field flex items-end">
                <button type="submit" name="create_reg_code" class="btn-cta w-full text-sm md:text-base">
                    Kód létrehozása
                </button>
            </div>
        </form>

        <h3 class="text-lg md:text-xl mb-3">Meglévő kódok</h3>
        <div class="overflow-x-auto">
        <table class="regcodes-table">
            <tr>
                <th>ID</th>
                <th>Kód</th>
                <th>Leírás</th>
                <th>Felhasznált / Max</th>
                <th>Lejárat</th>
                <th>Aktív</th>
                <th>Létrehozva</th>
                <th>Műveletek</th>
            </tr>
            <?php if ($regCodes && $regCodes->num_rows > 0): ?>
                <?php while ($code = $regCodes->fetch_assoc()):
                    $isExpired = false;
                    if (!empty($code['expires_at']) && strtotime($code['expires_at']) <= time()) {
                        $isExpired = true;
                    }
                    $rowClass = $isExpired ? 'row-expired' : '';
                ?>
                    <tr class="<?= $rowClass ?>">
                        <td><?= (int)$code['id'] ?></td>
                        <td><code><?= htmlspecialchars($code['code']) ?></code></td>
                        <td><?= htmlspecialchars($code['description'] ?? '') ?></td>
                        <td>
                            <?= (int)$code['used'] ?>
                            /
                            <?= $code['max_uses'] !== null ? (int)$code['max_uses'] : '∞' ?>
                        </td>
                        <td>
                            <?php if ($code['expires_at']): ?>
                                <?= htmlspecialchars($code['expires_at']) ?>
                                <?php if ($isExpired): ?>
                                    <span class="badge badge-expired">Lejárt</span>
                                <?php endif; ?>
                            <?php else: ?>
                                Nincs
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ((int)$code['active'] === 1 && !$isExpired): ?>
                                <span class="badge badge-active">Aktív</span>
                            <?php elseif ((int)$code['active'] === 1 && $isExpired): ?>
                                <span class="badge badge-expired">Lejárt, még aktív</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Inaktív</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($code['created_at']) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="reg_code_id" value="<?= (int)$code['id'] ?>">
                                <?php if ((int)$code['active'] === 1): ?>
                                    <button type="submit" name="deactivate_reg_code" class="btn-ghost">
                                        Deaktiválás
                                    </button>
                                <?php else: ?>
                                    <button type="submit" name="activate_reg_code" class="btn-cta">
                                        Aktiválás
                                    </button>
                                <?php endif; ?>
                            </form>
                            <form method="post" style="display:inline;"
                                  onsubmit="return confirm('Biztosan törlöd ezt a kódot?');">
                                <input type="hidden" name="reg_code_id" value="<?= (int)$code['id'] ?>">
                                <button type="submit" name="delete_reg_code" class="btn-ghost btn-delete">
                                    Törlés
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Még nincs regisztrációs kód.</td>
                </tr>
            <?php endif; ?>
        </table>
        </div>
    </section>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
