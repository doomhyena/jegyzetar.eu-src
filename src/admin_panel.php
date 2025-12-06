<?php
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
    <style>
        table { width: 100%; border-collapse: collapse; margin: 18px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid var(--stroke); }
        th { background: rgba(255,255,255,.06); font-weight: 800; color: var(--primary); }
        tr:hover { background: rgba(255,255,255,.03); }
        a { color: var(--accent); text-decoration: none; font-weight: 700; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
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

        if ($action === 'approve') {
            $res = db_query($conn, "SELECT * FROM user_custom_css_requests WHERE id = ? LIMIT 1", "i", [$css_id]);

            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $userId = intval($row['user_id']);

                db_exec($conn, "UPDATE user_custom_css_requests  SET status = 'approved', reviewed_at = NOW(), reviewed_by = ?  WHERE id = ?  LIMIT 1", "ii", [$adminId, $css_id]);
                db_exec($conn, "UPDATE user_custom_css_requests  SET status = 'rejected'  WHERE user_id = ? AND status = 'pending' AND id <> ?", "ii", [$userId, $css_id]);
            }
        } elseif ($action === 'reject') {
            db_exec($conn, "UPDATE user_custom_css_requests  SET status = 'rejected', reviewed_at = NOW(), reviewed_by = ?  WHERE id = ?  LIMIT 1", "ii", [$adminId, $css_id]);
        }

        echo "<script>location.href='admin_panel.php';</script>";
        exit();
    }

    $users = $conn->query("SELECT * FROM users ORDER BY id DESC");
    $files = $conn->query("SELECT * FROM files ORDER BY id DESC");
    $comments = $conn->query("SELECT comments.*, users.username FROM comments LEFT JOIN users ON comments.userid=users.id ORDER BY comments.id DESC");
    $categories = $conn->query("SELECT DISTINCT subject FROM files WHERE subject != '' ORDER BY subject ASC");
    $css_requests = $conn->query("SELECT r.*, u.username, rv.username AS reviewer_name FROM user_custom_css_requests r JOIN users u ON r.user_id = u.id LEFT JOIN users rv ON r.reviewed_by = rv.id ORDER BY (r.status = 'pending') DESC, r.id DESC");
    $user_badges = $conn->query("SELECT ub.*, u.username, b.name AS badge_name FROM user_badges ub JOIN users u ON ub.user_id = u.id JOIN badges b ON ub.badge_id = b.id ORDER BY ub.id DESC");
    $badge_options = $conn->query("SELECT id, name FROM badges ORDER BY name ASC");
    $user_options  = $conn->query("SELECT id, username FROM users ORDER BY username ASC");
    $badges = $conn->query("SELECT * FROM badges ORDER BY id DESC");
?>

<div class="main">
    <h1>Admin Panel</h1>
    <section class="card">
        <h2>Felhasználók kezelése</h2>
        <table>
            <tr>
                <th>ID</th><th>Név</th><th>Felhasználónév</th><th>Email</th><th>Admin</th><th>Művelet</th>
            </tr>
            <?php while($user = $users->fetch_assoc()) { ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['lastname'] . ' ' . $user['firstname']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
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
    <section class="card">
        <h2>Fájlok kezelése</h2>
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
    <section class="card">
        <h2>Kommentek kezelése</h2>
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
    <section class="card">
        <h2>Kategóriák kezelése</h2>
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
    <section class="card">
        <h2>Profil CSS kérések</h2>
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
    <section class="card">
        <h2>User badge-ek kezelése</h2>
        <h3>Új badge hozzárendelése</h3>
        <form method="post" action="admin_panel.php" style="margin-bottom: 16px;">
            <input type="hidden" name="action" value="add_user_badge">
            <label for="user_id">Felhasználó:</label>
            <select name="user_id" id="user_id" class="profile-theme-select" required>
                <option value="">Válassz felhasználót</option>
                <?php while ($u = $user_options->fetch_assoc()) { ?>
                    <option value="<?= (int)$u['id'] ?>">
                        <?= htmlspecialchars($u['username']) ?>
                    </option>
                <?php } ?>
            </select>
            <label for="badge_id" style="margin-left: 12px;">Badge:</label>
            <select name="badge_id" id="badge_id" class="profile-theme-select" required>
                <option value="">Válassz badge-et</option>
                <?php while ($b = $badge_options->fetch_assoc()) { ?>
                    <option value="<?= (int)$b['id'] ?>">
                        <?= htmlspecialchars($b['name']) ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit" class="btn-cta" style="margin-left: 12px;">Hozzáadás</button>
        </form>
        <h3>Meglévő user_badge-ek</h3>
        <table>
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
    </section>
    <section class="card">
        <h2>Badge-ek kezelése</h2>
        <h3>Új badge létrehozása</h3>
        <form method="post" action="admin_panel.php" style="margin-bottom:16px;">
            <input type="hidden" name="badge_action" value="create">
            <label for="badge_name">Név:</label>
            <input type="text" id="badge_name" name="name" class="input" required placeholder="pl. Szuper segítő">
            <label for="badge_slug" style="margin-left:12px;">Slug:</label>
            <input type="text" id="badge_slug" name="slug" class="input" required placeholder="pl. super-helper">
            <br><br>
            <label for="badge_desc">Leírás:</label>
            <input type="text" id="badge_desc" name="description" class="input" placeholder="pl. Sok jegyzetet töltött fel">
            <label for="badge_icon" style="margin-left:12px;">Ikon (emoji / rövid kód):</label>
            <input type="text" id="badge_icon" name="icon" class="input" placeholder="pl. ⭐ vagy fa-icon">
            <button type="submit" class="btn-cta" style="margin-left:12px;">Hozzáadás</button>
        </form>
        <h3>Meglévő badge-ek</h3>
        <table>
            <tr>
                <th>ID</th>
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
    </section>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
