<?php
    require "assets/php/db.php";

    if(!isset($_COOKIE['id'])){
        header("Location: index.php");
    }

    $sql = "SELECT * FROM users WHERE id='" . $conn->real_escape_string($_COOKIE['id']) . "'";
    $found_user = $conn->query($sql);
    $current_user = $found_user->fetch_assoc();
    if (!$current_user) {
        header("Location: index.php");
    }

    $sql = "SELECT * FROM users WHERE id='" . $_COOKIE['id'] . "'";
    $found_user = $conn->query($sql);
    $user = $found_user->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="hu">
   <head>
       <title>Admin Panel</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Iskolai jegyzeteket megosztó oldal'>
       <meta name='keywords' content='iskola, jegyzet, megosztás, tanulás'>
       <meta name='author' content='Csontos Kincső, Szekeres Levente'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
       <link rel="stylesheet" href="assets/css/styles.aurora.css">
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
            echo "<div class='card'><h2>⛔ Nincs jogosultságod az admin felület megtekintéséhez.</h2></div>";
            echo "</div>";
            include 'assets/php/footer.php';
            echo '</body></html>';
            exit();
        }

        if (isset($_GET['delete_type']) && isset($_GET['delete_id'])) {
            $type = $_GET['delete_type'];
            $id = intval($_GET['delete_id']);
            switch ($type) {
                case 'user':
                    if ($id != $current_user['id']) {
                        $conn->query("DELETE FROM users WHERE id=$id");
                        $conn->query("DELETE FROM files WHERE uploaded_by=$id");
                        $conn->query("DELETE FROM comments WHERE userid=$id");
                    }
                    break;
                case 'file' :
                    $conn->query("DELETE FROM files WHERE id=$id");
                    $conn->query("DELETE FROM comments WHERE postid=$id");
                    break;
                case 'comment':
                    $conn->query("DELETE FROM comments WHERE id=$id");
                    break;
                case 'category':
                    if (isset($_GET['subject'])) {
                        $subject = $conn->real_escape_string($_GET['subject']);
                        $conn->query("UPDATE files SET subject='' WHERE subject='$subject'");
                    }
                    break;
            }
            echo "<script>location.href='admin_panel.php';</script>";
        }

        $users = $conn->query("SELECT * FROM users ORDER BY id DESC");
        $files = $conn->query("SELECT * FROM files ORDER BY id DESC");
        $comments = $conn->query("SELECT comments.*, users.username FROM comments LEFT JOIN users ON comments.userid=users.id ORDER BY comments.id DESC");
        $categories = $conn->query("SELECT DISTINCT subject FROM files WHERE subject != '' ORDER BY subject ASC");
    ?>
    
    <div class="main">
        <h1>🛡️ Admin Panel</h1>
        
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
                    $uploader = $conn->query("SELECT username FROM users WHERE id=" . intval($f['uploaded_by']))->fetch_assoc();
                ?>
                <tr>
                    <td><?= $f['id'] ?></td>
                    <td><?= htmlspecialchars($f['name']) ?></td>
                    <td><?= htmlspecialchars($f['description']) ?></td>
                    <td><?= htmlspecialchars($f['subject']) ?></td>
                    <td><?= htmlspecialchars($uploader['username'] ?? 'Ismeretlen') ?></td>
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
    </div>
    
    <?php include 'assets/php/footer.php'; ?>
   </body>
</html>