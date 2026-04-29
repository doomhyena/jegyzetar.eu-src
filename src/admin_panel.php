<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    require_login();

    $userId = (int)auth_user_id();

    $result = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$userId]);
    $current_user = $result->fetch_assoc();

    if (!$current_user) {
        header("Location: reglog.php");
        exit();
    }

    if (!isset($current_user['admin']) || (int)$current_user['admin'] !== 1) {
        http_response_code(403);
        exit(t('admin_panel_no_permission'));
    }

    $user = $current_user;
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title><?= t('admin_panel_title') ?></title>
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
</head>
<body class="no-ads admin-page">
<?php
    include 'assets/php/navbar.php';

    if ($current_user['admin'] != 1) {
        echo "<div class='main'>";
            echo "<div class='card'><h2>" . t('admin_panel_no_access') . "</h2></div>";
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
            echo "<script>alert('" . t('admin_panel_cannot_remove_self') . "');</script>";
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

            echo "<script>alert('" . t('admin_panel_role_updated') . "'); location.href='admin_panel.php';</script>";
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

                    $uRes = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$id]);
                    $uRow = $uRes ? $uRes->fetch_assoc() : null;

                    if ($uRow) {
                        $uploadCountRes = db_query($conn,
                            "SELECT COUNT(*) AS c FROM files WHERE uploaded_by = ?", "i", [$id]);
                        $uploadCount = $uploadCountRes ? (int)($uploadCountRes->fetch_assoc()['c'] ?? 0) : 0;

                        $dlCountRes = db_query($conn,
                            "SELECT COALESCE(SUM(download_count), 0) AS s FROM files WHERE uploaded_by = ?", "i", [$id]);
                        $dlCount = $dlCountRes ? (int)($dlCountRes->fetch_assoc()['s'] ?? 0) : 0;

                        $premRes = db_query($conn, "SELECT MAX(premium_ig) AS p FROM premium_users WHERE user_id = ?", "i", [$id]);
                        $premRow  = $premRes ? $premRes->fetch_assoc() : null;
                        $wasPrem  = (!empty($premRow['p']) && strtotime($premRow['p']) >= time()) ? 1 : 0;

                        db_exec($conn, "INSERT INTO deleted_users (original_id, username, email, firstname, lastname, birthdate, registration_date, was_admin, was_teacher, was_premium, upload_count, download_count, deleted_by, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())", "issssssiiiiii",
                            [
                                (int)$uRow['id'],
                                (string)($uRow['username'] ?? ''),
                                (string)($uRow['email'] ?? ''),
                                (string)($uRow['firstname'] ?? ''),
                                (string)($uRow['lastname'] ?? ''),
                                (!empty($uRow['birthdate']) && $uRow['birthdate'] !== '0000-00-00')
                                    ? $uRow['birthdate'] : null,
                                (string)($uRow['registration_date'] ?? ''),
                                (int)($uRow['admin'] ?? 0),
                                (int)($uRow['teacher'] ?? 0),
                                $wasPrem,
                                $uploadCount,
                                $dlCount,
                                (int)$current_user['id'],
                            ]
                        );
                    }

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
            echo "<script>alert('" . t('admin_panel_code_required') . "');</script>";
        } else {
            if ($maxUses !== '' && ctype_digit($maxUses)) {
                $maxUsesVal = (int)$maxUses;
            }

            if ($expiresRaw !== '') {
                $expiresAt = str_replace('T', ' ', $expiresRaw) . ':00';
            }

            db_stmt($conn, "INSERT INTO reg_codes (code, description, max_uses, expires_at, active) VALUES (?, ?, ?, ?, 1)", "ssis", [$code, $description !== '' ? $description : null, $maxUsesVal, $expiresAt])->close();

            echo "<script>alert('" . t('admin_panel_code_created') . "');</script>";
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
    $css_requests = $conn->query("SELECT r.*, u.username, rv.username AS reviewer_name FROM user_custom_css_requests r JOIN users u ON r.user_id = u.id LEFT JOIN users rv ON r.reviewed_by = rv.id ORDER BY (r.status = 'pending') DESC, r.id DESC");
	$group_requests = $conn->query("SELECT g.*, u.username AS owner_name, rv.username AS reviewer_name FROM groups g LEFT JOIN users u ON u.id = g.owner_id LEFT JOIN users rv ON rv.id = g.reviewed_by ORDER BY (g.status = 'pending') DESC, g.id DESC");
    $user_badges = $conn->query("SELECT ub.*, u.username, b.name AS badge_name FROM user_badges ub JOIN users u ON ub.user_id = u.id JOIN badges b ON ub.badge_id = b.id ORDER BY ub.id DESC");
    $badge_options = $conn->query("SELECT id, name FROM badges ORDER BY name ASC");
    $user_options  = $conn->query("SELECT id, username FROM users ORDER BY username ASC");
    $badges = $conn->query("SELECT * FROM badges ORDER BY id DESC");
    $reports = $conn->query("SELECT r.*, u.username AS reporter_name FROM reports r LEFT JOIN users u ON u.id = r.reporter_id ORDER BY (r.status = 'open') DESC, r.created_at DESC");

    $rows_du = [];
    $deleter_names = [];
    $du_res = $conn->query("SELECT * FROM deleted_users ORDER BY deleted_at DESC");
    if ($du_res) {
        $deleter_ids = [];
        while ($r = $du_res->fetch_assoc()) {
            $rows_du[] = $r;
            $deleter_ids[] = (int)$r['deleted_by'];
        }
        if (!empty($deleter_ids)) {
            $in = implode(',', array_unique($deleter_ids));
            $dnRes = $conn->query("SELECT id, username FROM users WHERE id IN ($in)");
            if ($dnRes) {
                while ($dn = $dnRes->fetch_assoc()) {
                    $deleter_names[(int)$dn['id']] = $dn['username'];
                }
            }
        }
    }
?>
<div class="main w-full max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6">
    <h1 class="text-2xl md:text-3xl lg:text-4xl mb-6"><?= t('admin_panel_title') ?></h1>
    <section class="card p-4 md:p-6 mb-6 overflow-x-auto">
        <h2 class="text-xl md:text-2xl mb-4"><?= t('admin_users_title') ?></h2>
        <table>
            <tr>
                <th><?= t('admin_user_id') ?></th><th><?= t('admin_user_name') ?></th><th><?= t('admin_user_username') ?></th><th><?= t('admin_user_email') ?></th><th><?= t('admin_user_admin') ?></th><th><?= t('admin_user_teacher') ?></th><th><?= t('admin_user_action') ?></th>
            </tr>
            <?php while($user = $users->fetch_assoc()) { ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['lastname'] . ' ' . $user['firstname']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars(mask_email($user['email'])) ?></td>
                    <td>
                        <?php if ($user['admin'] == 1): ?>
                            <span class="badge badge-active"><?= t('label_yes') ?></span>
                        <?php else: ?>
                            <span class="badge badge-inactive"><?= t('label_no') ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user['teacher'] == 1): ?>
                            <span class="badge badge-active"><?= t('label_yes') ?></span>
                        <?php else: ?>
                            <span class="badge badge-inactive"><?= t('label_no') ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user['id'] != $current_user['id']) { ?>
                            <a href="?delete_type=user&delete_id=<?= $user['id'] ?>" onclick="return confirm('<?= t('admin_confirm_delete_user') ?>')"><?= t('btn_delete') ?></a>
                        <?php } else { ?>
                            <?= t('admin_user_own_account') ?>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
    <section class="card p-4 md:p-6 mb-6" id="deleted-users">
        <h2 class="text-xl md:text-2xl mb-4"><?= t('admin_deleted_users_title') ?></h2>
        <?php if (empty($rows_du)): ?>
            <p class="entry-meta"><?= t('admin_deleted_users_none') ?></p>
        <?php else: ?>
            <table style="width:100%; table-layout:auto;">
                <thead>
                    <tr>
                        <th style="white-space:nowrap;">Orig. ID</th>
                        <th style="white-space:nowrap;"><?= t('admin_deleted_username') ?></th>
                        <th style="white-space:nowrap;">Email</th>
                        <th style="white-space:nowrap;"><?= t('admin_deleted_fullname') ?></th>
                        <th style="white-space:nowrap;"><?= t('admin_deleted_registered') ?></th>
                        <th style="white-space:nowrap;"><?= t('admin_deleted_role') ?></th>
                        <th style="white-space:nowrap;">Felt.</th>
                        <th style="white-space:nowrap;">Let.</th>
                        <th style="white-space:nowrap;"><?= t('admin_deleted_deleted_by') ?></th>
                        <th style="white-space:nowrap;"><?= t('admin_deleted_deleted_at') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows_du as $du): ?>
                        <tr>
                            <td><code><?= (int)$du['original_id'] ?></code></td>
                            <td style="white-space:nowrap;"><strong>@<?= htmlspecialchars($du['username']) ?></strong></td>
                            <td style="word-break:break-all; max-width:180px;"><?= htmlspecialchars($du['email']) ?></td>
                            <td style="white-space:nowrap;"><?= htmlspecialchars(trim($du['lastname'] . ' ' . $du['firstname'])) ?: '—' ?></td>
                            <td style="white-space:nowrap;"><?= htmlspecialchars(substr($du['registration_date'] ?? '—', 0, 10)) ?></td>
                            <td style="white-space:nowrap;">
                                <?php if ($du['was_admin']): ?>
                                    <span class="badge badge-active">Admin</span>
                                <?php elseif ($du['was_teacher']): ?>
                                    <span class="badge" style="background:rgba(167,139,250,.15);border:1px solid rgba(167,139,250,.6);color:#ddd6fe;"><?= t('role_teacher') ?></span>
                                <?php else: ?>
                                    <span class="badge badge-inactive">Tanuló</span>
                                <?php endif; ?>
                                <?php if ($du['was_premium']): ?>
                                    <span class="badge" style="background:rgba(251,191,36,.15);border:1px solid rgba(251,191,36,.6);color:#fde68a;"><?= t('role_premium') ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= (int)$du['upload_count'] ?></td>
                            <td><?= (int)$du['download_count'] ?></td>
                            <td style="white-space:nowrap;">
                                <?php
                                    $dname = $deleter_names[(int)$du['deleted_by']] ?? null;
                                    echo $dname
                                        ? '@' . htmlspecialchars($dname)
                                        : '<span class="entry-meta">' . (int)$du['deleted_by'] . '</span>';
                                ?>
                            </td>
                            <td style="white-space:nowrap;"><?= htmlspecialchars(substr($du['deleted_at'], 0, 10)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
	<section class="card p-4 md:p-6 mb-6">
		<h2 class="text-xl md:text-2xl mb-4"><?= t('admin_roles_title') ?></h2>
		<input class="input w-full" type="text" id="admin-user-search" placeholder="<?= t('admin_search_user_placeholder') ?>">
		<div id="admin-user-results"></div>

		<div id="search" style="margin-top:12px;">
		<small class="text-xs opacity-75"><?= t('admin_search_help') ?></small>
		</div>
	</section>
	
    <section class="card p-4 md:p-6 mb-6 overflow-x-auto">
        <h2 class="text-xl md:text-2xl mb-4"><?= t('admin_files_title') ?></h2>
        <table>
            <tr>
                <th><?= t('admin_file_id') ?></th><th><?= t('admin_file_name') ?></th><th><?= t('admin_file_description') ?></th><th><?= t('admin_file_uploader') ?></th><th><?= t('admin_file_action') ?></th>
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
                    <td><?= htmlspecialchars($uploaderUsername) ?></td>
                    <td>
                        <a href="?delete_type=file&delete_id=<?= $f['id'] ?>" onclick="return confirm('<?= t('admin_confirm_delete_file') ?>')"><?= t('btn_delete') ?></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
    <section class="card p-4 md:p-6 mb-6 overflow-x-auto">
        <h2 class="text-xl md:text-2xl mb-4"><?= t('admin_comments_title') ?></h2>
        <table>
            <tr>
                <th><?= t('admin_comment_id') ?></th><th><?= t('admin_comment_user') ?></th><th><?= t('admin_comment_file_id') ?></th><th><?= t('admin_comment_text') ?></th><th><?= t('admin_comment_action') ?></th>
            </tr>
            <?php while($c = $comments->fetch_assoc()) { ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['username']) ?></td>
                    <td><?= $c['postid'] ?></td>
                    <td><?= htmlspecialchars($c['text']) ?></td>
                    <td>
                        <a href="?delete_type=comment&delete_id=<?= $c['id'] ?>" onclick="return confirm('<?= t('admin_confirm_delete_comment') ?>')"><?= t('btn_delete') ?></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
    <section class="card p-4 md:p-6 mb-6 overflow-x-auto">
        <h2 class="text-xl md:text-2xl mb-4"><?= t('admin_css_requests_title') ?></h2>
        <table>
            <tr>
                <th>ID</th>
                <th><?= t('admin_user_badges_user') ?></th>
                <th><?= t('admin_group_status') ?></th>
                <th><?= t('admin_invite_code_created') ?></th>
                <th>Review</th>
                <th>CSS</th>
                <th><?= t('admin_reports_action') ?></th>
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
                            <a href="?css_action=approve&css_id=<?= $css['id'] ?>" onclick="return confirm('<?= t('admin_confirm_approve_css') ?>')"><?= t('admin_action_approve') ?></a><br>
                            <a href="?css_action=reject&css_id=<?= $css['id'] ?>" onclick="return confirm('<?= t('admin_confirm_reject_css') ?>')"><?= t('admin_action_reject') ?></a>
                        <?php } else { ?>
                            <?= t('admin_no_action') ?>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
	<section class="card p-4 md:p-6 mb-6 overflow-x-auto">
    <h2 class="text-xl md:text-2xl mb-4"><?= t('admin_groups_title') ?></h2>
    <table>
        <tr>
            <th>ID</th>
            <th><?= t('admin_group_name') ?></th>
            <th><?= t('admin_group_description') ?></th>
            <th><?= t('admin_group_owner') ?></th>
            <th><?= t('admin_group_private') ?></th>
            <th><?= t('admin_group_status') ?></th>
            <th>Review</th>
            <th><?= t('admin_reports_action') ?></th>
        </tr>

        <?php if ($group_requests && $group_requests->num_rows > 0): ?>
            <?php while($g = $group_requests->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$g['id'] ?></td>
                    <td><?= htmlspecialchars($g['name']) ?></td>
                    <td><?= htmlspecialchars($g['description'] ?? '') ?></td>
                    <td><?= htmlspecialchars($g['owner_name'] ?? t('label_unknown_user')) ?></td>
                    <td><?= ((int)$g['is_private'] === 1) ? t('label_yes') : t('label_no') ?></td>
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
                            <a href="?group_action=approve&group_id=<?= (int)$g['id'] ?>" onclick="return confirm('<?= t('admin_confirm_approve_group') ?>')"><?= t('admin_action_approve') ?></a><br>
                            <a href="?group_action=reject&group_id=<?= (int)$g['id'] ?>" onclick="return confirm('<?= t('admin_confirm_reject_group') ?>')"><?= t('admin_action_reject') ?></a>
                        <?php else: ?>
                            <?= t('admin_no_action') ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8"><?= t('admin_group_none') ?></td></tr>
        <?php endif; ?>
    </table>
	</section>
    <section class="card p-4 md:p-6 mb-6">
        <h2 class="text-xl md:text-2xl mb-4"><?= t('admin_user_badges_title') ?></h2>
        <h3 class="text-lg md:text-xl mb-3"><?= t('admin_user_badges_add_title') ?></h3>
        <form method="post" action="admin_panel.php" class="mb-4 flex flex-col md:flex-row gap-3 items-end">
            <input type="hidden" name="action" value="add_user_badge">
            <div class="flex flex-col gap-2">
                <label for="user_id" class="text-sm md:text-base font-semibold"><?= t('admin_user_badges_label_user') ?></label>
                <select name="user_id" id="user_id" class="profile-theme-select text-sm md:text-base" required>
                    <option value=""><?= t('admin_user_badges_select_user_placeholder') ?></option>
                    <?php while ($u = $user_options->fetch_assoc()) { ?>
                        <option value="<?= (int)$u['id'] ?>">
                            <?= htmlspecialchars($u['username']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="flex flex-col gap-2">
                <label for="badge_id" class="text-sm md:text-base font-semibold"><?= t('admin_user_badges_label_badge') ?></label>
                <select name="badge_id" id="badge_id" class="profile-theme-select text-sm md:text-base" required>
                    <option value=""><?= t('admin_user_badges_select_badge_placeholder') ?></option>
                    <?php while ($b = $badge_options->fetch_assoc()) { ?>
                        <option value="<?= (int)$b['id'] ?>">
                            <?= htmlspecialchars($b['name']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn-cta text-sm md:text-base"><?= t('admin_user_badges_add_button') ?></button>
        </form>
        <h3 class="text-lg md:text-xl mb-3"><?= t('admin_user_badges_existing_title') ?></h3>
        <div class="badge-table-wrap">
            <table class="badge-list-table">
                <tr>
                    <th>ID</th>
                    <th><?= t('admin_user_badges_user') ?></th>
                    <th><?= t('admin_user_badges_badge') ?></th>
                    <th><?= t('admin_user_badges_granted_by') ?></th>
                    <th><?= t('admin_reports_date') ?></th>
                    <th><?= t('admin_reports_action') ?></th>
                </tr>
                <?php while ($ub = $user_badges->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $ub['id'] ?></td>
                        <td><?= htmlspecialchars($ub['username']) ?></td>
                        <td><?= htmlspecialchars($ub['badge_name']) ?></td>
                        <td>
                            <?php
                            if (!empty($ub['granted_by'])) {
                                $gbUsername = t('label_unknown_user');
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
                               onclick="return confirm('<?= t('admin_confirm_delete_user_badge') ?>')">
                                <?= t('btn_delete') ?>
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
                <h2 class="text-xl md:text-2xl"><?= t('admin_badges_title') ?></h2>
                <small class="text-xs md:text-sm opacity-75"><?= t('admin_badges_description') ?></small>
            </div>
        </div>
        <h3 class="text-lg md:text-xl mb-3"><?= t('admin_badges_add_title') ?></h3>
        <form method="post" action="admin_panel.php" class="badge-form-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <input type="hidden" name="badge_action" value="create">

            <div class="form-field">
                <label for="badge_name" class="text-sm md:text-base font-semibold"><?= t('admin_badges_name') ?></label>
                <input type="text" id="badge_name" name="name" class="input w-full text-sm md:text-base"
                       required placeholder="<?= t('admin_badge_name_placeholder') ?>">
            </div>

            <div class="form-field">
                <label for="badge_slug" class="text-sm md:text-base font-semibold"><?= t('admin_badges_slug') ?></label>
                <input type="text" id="badge_slug" name="slug" class="input w-full text-sm md:text-base"
                       required placeholder="<?= t('admin_badge_slug_placeholder') ?>">
            </div>

            <div class="form-field">
                <label for="badge_desc" class="text-sm md:text-base font-semibold"><?= t('admin_badges_description_label') ?></label>
                <input type="text" id="badge_desc" name="description" class="input w-full text-sm md:text-base"
                       placeholder="<?= t('admin_badge_description_placeholder') ?>">
            </div>

            <div class="form-field">
                <label for="badge_icon" class="text-sm md:text-base font-semibold"><?= t('admin_badges_icon') ?></label>
                <input type="text" id="badge_icon" name="icon" class="input w-full text-sm md:text-base"
                       placeholder="<?= t('admin_badge_icon_placeholder') ?>">
            </div>

            <div class="form-field flex items-end">
                <button type="submit" class="btn-cta w-full text-sm md:text-base">
                    <?= t('admin_badges_add_button') ?>
                </button>
            </div>
        </form>

        <h3 class="text-lg md:text-xl mb-3"><?= t('admin_badges_existing_title') ?></h3>
        <div class="overflow-x-auto">
        <table class="badge-list-table">
            <tr>
                <th>ID</th>
                <th><?= t('admin_badges_preview') ?></th>
                <th><?= t('admin_group_name') ?></th>
                <th><?= t('admin_badges_slug') ?></th>
                <th><?= t('admin_badges_description') ?></th>
                <th><?= t('admin_badges_icon') ?></th>
                <th><?= t('admin_reports_action') ?></th>
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
                            <button type="submit" class="btn-cta btn-ghost"><?= t('admin_badges_save') ?></button>
                            <a href="?delete_type=badge&delete_id=<?= $b['id'] ?>"
                               onclick="return confirm('<?= t('admin_confirm_delete_badge') ?>')">
                                <?= t('btn_delete') ?>
                            </a>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        </table>
        </div>
    </section>
    <section class="card p-4 md:p-6 mb-6 overflow-x-auto" id="reports">
        <h2 class="text-xl md:text-2xl mb-4"><?= t('admin_reports_title') ?></h2>
        <table>
            <tr>
                <th>ID</th>
                <th><?= t('admin_reports_reporter') ?></th>
                <th><?= t('admin_reports_type') ?></th>
                <th><?= t('admin_reports_target') ?></th>
                <th><?= t('admin_reports_reason') ?></th>
                <th><?= t('admin_reports_status') ?></th>
                <th><?= t('admin_reports_date') ?></th>
                <th><?= t('admin_reports_action') ?></th>
            </tr>
            <?php if ($reports && $reports->num_rows > 0): ?>
                <?php while ($rep = $reports->fetch_assoc()): ?>
                    <?php
                        $targetId   = (int)$rep['target_id'];
                        $targetType = $rep['target_type'];

                        $targetUrl   = '#';
                        $targetLabel = t('admin_unknown_target');

                        if ($targetType === 'user') {
                            $uRes = db_query($conn, "SELECT username FROM users WHERE id = ? LIMIT 1", "i", [$targetId]);
                            $uRow = $uRes ? $uRes->fetch_assoc() : null;

                            if ($uRow && isset($uRow['username'])) {
                                $targetUrl   = 'profile.php?user=' . urlencode($uRow['username']);
                                $targetLabel = t('admin_target_user_label', $uRow['username']);
                            } else {
                                $targetUrl   = '#';
                                $targetLabel = t('admin_target_user_id_label', $targetId);
                            }

                        } elseif ($targetType === 'group') {
                            $targetUrl = 'group.php?id=' . $targetId;

                            $gRes = db_query($conn, "SELECT name FROM groups WHERE id = ? LIMIT 1", "i", [$targetId]);
                            $gRow = $gRes ? $gRes->fetch_assoc() : null;

                            if ($gRow && isset($gRow['name'])) {
                                $targetLabel = t('admin_target_group_label', $gRow['name']);
                            } else {
                                $targetLabel = t('admin_target_group_id_label', $targetId);
                            }

                        } elseif ($targetType === 'note') {
                            $targetUrl = 'note.php?id=' . $targetId;

                            $nRes = db_query($conn, "SELECT name FROM files WHERE id = ? LIMIT 1", "i", [$targetId]);
                            $nRow = $nRes ? $nRes->fetch_assoc() : null;

                            if ($nRow && isset($nRow['name'])) {
                                $targetLabel = t('admin_target_note_label', $nRow['name']);
                            } else {
                                $targetLabel = t('admin_target_note_id_label', $targetId);
                            }
                        }
                    ?>
                    <tr>
                        <td><?= (int)$rep['id'] ?></td>
                        <td><?= htmlspecialchars($rep['reporter_name'] ?? t('label_unknown_user')) ?></td>
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
                                    echo '<span class="badge badge-expired">' . t('admin_report_rejected') . '</span>';
                                }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($rep['created_at']) ?></td>
                        <td>
                            <?php if ($rep['status'] === 'open'): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="report_id" value="<?= (int)$rep['id'] ?>">
                                    <button type="submit" name="report_action" value="resolve" class="btn-cta">
                                        <?= t('admin_report_accept') ?>
                                    </button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="report_id" value="<?= (int)$rep['id'] ?>">
                                    <button type="submit" name="report_action" value="dismiss" class="btn-ghost">
                                        <?= t('admin_report_reject') ?>
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
                    <td colspan="8"><?= t('admin_reports_none') ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </section>
    <section class="card p-4 md:p-6 mb-6" id="reg-codes">
        <div class="regcodes-header mb-4">
            <div>
                <h2 class="text-xl md:text-2xl"><?= t('admin_invite_codes_title') ?></h2>
                <small class="text-xs md:text-sm opacity-75"><?= t('admin_invite_codes_help') ?></small>
            </div>
        </div>

        <h3 class="text-lg md:text-xl mb-3"><?= t('admin_invite_code_add_title') ?></h3>
        <form method="post" class="regcodes-form grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="form-field">
                <label for="code" class="text-sm md:text-base font-semibold"><?= t('admin_invite_code_label_code') ?></label>
                <input type="text" name="code" id="code" class="input w-full text-sm md:text-base" required placeholder="<?= t('admin_reg_code_placeholder') ?>">
            </div>

            <div class="form-field">
                <label for="description" class="text-sm md:text-base font-semibold"><?= t('admin_invite_code_label_description') ?></label>
                <input type="text" name="description" id="description" class="input w-full text-sm md:text-base" placeholder="<?= t('admin_reg_code_description_placeholder') ?>">
            </div>

            <div class="form-field">
                <label for="max_uses" class="text-sm md:text-base font-semibold"><?= t('admin_invite_code_label_max_uses') ?></label>
                <input type="number" name="max_uses" id="max_uses" class="input w-full text-sm md:text-base" min="1" placeholder="<?= t('admin_reg_code_max_uses_placeholder') ?>">
            </div>

            <div class="form-field">
                <label for="expires_at" class="text-sm md:text-base font-semibold"><?= t('admin_invite_code_label_expires') ?></label>
                <input type="datetime-local" name="expires_at" id="expires_at" class="input w-full text-sm md:text-base" placeholder="<?= t('admin_reg_code_expires_placeholder') ?>">
            </div>

            <div class="form-field flex items-end">
                <button type="submit" name="create_reg_code" class="btn-cta w-full text-sm md:text-base">
                    <?= t('admin_invite_code_create_button') ?>
                </button>
            </div>
        </form>

        <h3 class="text-lg md:text-xl mb-3"><?= t('admin_invite_code_existing_title') ?></h3>
        <div class="overflow-x-auto">
        <table class="regcodes-table">
            <tr>
                <th>ID</th>
                <th><?= t('admin_invite_code_code') ?></th>
                <th><?= t('admin_badges_description') ?></th>
                <th><?= t('admin_invite_code_used_max') ?></th>
                <th><?= t('admin_invite_code_expires') ?></th>
                <th><?= t('admin_invite_code_active') ?></th>
                <th><?= t('admin_invite_code_created') ?></th>
                <th><?= t('admin_invite_code_actions') ?></th>
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
                                <?= t('admin_invite_code_expires_none') ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ((int)$code['active'] === 1 && !$isExpired): ?>
                                <span class="badge badge-active"><?= t('admin_invite_code_status_active') ?></span>
                            <?php elseif ((int)$code['active'] === 1 && $isExpired): ?>
                                <span class="badge badge-expired"><?= t('admin_invite_code_status_expired_active') ?></span>
                            <?php else: ?>
                                <span class="badge badge-inactive"><?= t('admin_invite_code_status_inactive') ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($code['created_at']) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="reg_code_id" value="<?= (int)$code['id'] ?>">
                                <?php if ((int)$code['active'] === 1): ?>
                                    <button type="submit" name="deactivate_reg_code" class="btn-ghost">
                                        <?= t('admin_invite_code_deactivate') ?>
                                    </button>
                                <?php else: ?>
                                    <button type="submit" name="activate_reg_code" class="btn-cta">
                                        <?= t('admin_invite_code_activate') ?>
                                    </button>
                                <?php endif; ?>
                            </form>
                            <form method="post" style="display:inline;"
                                  onsubmit="return confirm('<?= t('admin_confirm_delete_reg_code') ?>');">
                                <input type="hidden" name="reg_code_id" value="<?= (int)$code['id'] ?>">
                                <button type="submit" name="delete_reg_code" class="btn-ghost btn-delete">
                                    <?= t('admin_invite_code_delete') ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8"><?= t('admin_invite_code_none') ?></td>
                </tr>
            <?php endif; ?>
        </table>
        </div>
    </section>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>