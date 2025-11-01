<?php
    require "assets/php/db.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: index.php");
    }

    $userid = $_GET['userid'];
    $sql = "SELECT * FROM users WHERE id='" . $userid . "'";
    $found_user = $conn->query($sql);
    $user = $found_user->fetch_assoc();

    if ($_GET['userid'] == $_COOKIE['id']) {
        $userid = $_COOKIE['id'];
        $sql = "SELECT * FROM users WHERE id='$userid'";
        $found_user = $conn->query($sql);
        $logged_in_user = $found_user->fetch_assoc();

        if (isset($_POST['pfp-btn']) && isset($_FILES['profile_picture'])) {
            $file_name = $_FILES['profile_picture']['name'];
            $tmp_name = $_FILES['profile_picture']['tmp_name'];
            $target_dir = __DIR__ . "/users/" . $logged_in_user['username'] . "/";
            $target_file = $target_dir . basename($file_name);

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (!empty($logged_in_user['profile_picture'])) {
                $old_file = $target_dir . $logged_in_user['profile_picture'];
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }

            if (move_uploaded_file($tmp_name, $target_file)) {
                $conn->query("UPDATE users SET profile_picture='$file_name' WHERE id='$userid'");
                header("Location: profile.php?userid=$userid");
                exit;
            } else {
                echo "<script>alert('Hiba történt a fájl feltöltésekor.');</script>";
            }
        }
    }

    if (isset($_POST['edit-email-btn'])) {
        header("Location: edit_email.php?userid=" . $user['id']);
        exit;
    }

    $sql = "SELECT * FROM notifys WHERE toid = $user[id] AND readed = 0";
    $founded_notify = $conn->query($sql);
    $notify_number = mysqli_num_rows($founded_notify);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title>Profil</title>
    <meta charset='UTF-8'>
    <meta name='description' content='Iskolai jegyzeteket megosztó oldal'>
    <meta name='keywords' content='iskola, jegyzet, megosztás, tanulás'>
    <meta name='author' content='Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.aurora.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
<?php
    include 'assets/php/navbar.php';

    $folder = getcwd();
    if (!empty($user['profile_picture'])) {
        $profile_picture_path = "users/" . $user['username'] . "/" . $user['profile_picture'];
    } else {
        $profile_picture_path = "assets/img/default_profile_picture.jpg";
    }
?>

<div class="main">
    <h1><?= htmlspecialchars($user['firstname']) ?> profilja</h1>
    <div class="home-grid">
        <aside class="content-aside">
            <div class="card profile-center">
                <img class='profile-picture' src='<?= htmlspecialchars($profile_picture_path) ?>' alt='Profilkép'>
                <h2 class="profile-name"><?= htmlspecialchars($user['lastname'] . ' ' . $user['firstname']) ?></h2>
                <p class="entry-meta profile-username">@<?= htmlspecialchars($user['username']) ?></p>
                <?php if ($_GET['userid'] == $_COOKIE['id']): ?>
                    <div class="profile-actions">
                        <form method='POST' enctype='multipart/form-data'>
                            <label for='profile_picture' class='btn-ghost'>
                                Profilkép feltöltése
                            </label>
                            <input type='file' name='profile_picture' id='profile_picture' accept='image/*' style='display: none;' onchange='this.form.submit()'>
                            <input type='hidden' name='pfp-btn' value='1'>
                        </form>

                        <form method='POST'>
                            <button type='submit' name='edit-email-btn' class='btn-ghost'>Email szerkesztése</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card">
                <h3>Profil adatok</h3>
                <div class="profile-info-card">
                    <div class="profile-info-item">
                        <div class="profile-info-label">Teljes név</div>
                        <div class="profile-info-value"><?= htmlspecialchars($user['lastname'] . ' ' . $user['firstname']) ?></div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-label">Felhasználónév</div>
                        <div class="profile-info-value">@<?= htmlspecialchars($user['username']) ?></div>
                    </div>
                    <?php if ($_GET['userid'] == $_COOKIE['id']): ?>
                        <div class="profile-info-item">
                            <div class="profile-info-label">Email</div>
                            <div class="profile-info-value"><?= htmlspecialchars($user['email']) ?></div>
                        </div>

                        <div class="profile-info-item">
                            <div class="profile-info-label">Regisztráció</div>
                            <div class="profile-info-value"><?= htmlspecialchars($user['registration_date']) ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($_GET['userid'] != $_COOKIE['id']):
                $sql = "SELECT * FROM friends WHERE (fromid='" . $_COOKIE['id'] . "' AND toid='" . $_GET['userid'] . "') OR (fromid='" . $_GET['userid'] . "' AND toid='" . $_COOKIE['id'] . "')";
                $result = $conn->query($sql);
                ?>
                <div class="card">
                    <h3>Barátság</h3>
                    <?php if ($result->num_rows > 0):
                        $friendship = $result->fetch_assoc();
                        ?>
                        <p class="entry-meta">
                            <?php
                            if ($friendship['status'] == 1) {
                                echo "Ti már barátok vagytok!";
                            } elseif ($friendship['fromid'] == $_COOKIE['id']) {
                                echo "Te küldted a barátfelkérést.";
                            } else {
                                echo "A felhasználó küldött neked barátfelkérést.";
                            }
                            ?>
                        </p>
                    <?php else: ?>
                        <form method='post' action='assets/php/add_friend.php'>
                            <input type='hidden' name='toid' value='<?= $_GET['userid'] ?>'>
                            <button type='submit' class='btn-cta'>Barátnak jelölés</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </aside>
        <section class="content-main">
            <div class="section-titlebar">
                <h3>Feltöltött anyagok</h3>
            </div>
            <?php
            $sql = "SELECT * FROM files WHERE uploaded_by='$user[id]' ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0): ?>
                <div class="content-grid grid-large">
                    <?php while ($file = $result->fetch_assoc()):
                        if ($file['uploaded_by'] == $user['id']):
                            $uploader_id = $file['uploaded_by'];
                            $uploader_query = $conn->query("SELECT username FROM users WHERE id='$uploader_id'");
                            $uploader = $uploader_query->fetch_assoc();
                            $file_extension = pathinfo($file['file_name'], PATHINFO_EXTENSION);
                            ?>
                            <article class="card">
                                <header class="card-head">
                                    <h4 class="entry-title"><?= htmlspecialchars($file['name']) ?></h4>
                                    <a class="entry-download-btn" href="assets/php/download.php?id=<?= $file['id'] ?>">
                                        <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M12 3v10m0 0l-4-4m4 4l4-4M4 17v3h16v-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                        Letöltés
                                    </a>
                                </header>
                                <?php if (!empty($file['subject'])): ?>
                                    <p class="entry-meta">Tárgy: <strong><?= htmlspecialchars($file['subject']) ?></strong></p>
                                <?php endif; ?>
                                <p><?= htmlspecialchars($file['description']) ?></p>
                                <?php if ($file_extension === 'docx'): ?>
                                    <p><b>Ez egy .docx fájl. A megtekintéshez töltsd le és nyisd meg Microsoft Word-ben.</b></p>
                                <?php elseif ($file_extension === 'mp4'): ?>
                                    <video controls class='file-preview'>
                                        <source src='users/<?= htmlspecialchars($uploader['username']) ?>/<?= htmlspecialchars($file['file_name']) ?>' type='video/mp4'>
                                        A te böngésződ nem támogatja a videocímkét.
                                    </video>
                                <?php elseif ($file_extension === 'pdf'): ?>
                                    <iframe src='users/<?= htmlspecialchars($uploader['username']) ?>/<?= htmlspecialchars($file['file_name']) ?>' width='100%' height='500px'></iframe>
                                <?php endif; ?>

                                <?php if (!empty($file['tags'])): ?>
                                    <p class="entry-meta">Címkék: <?= htmlspecialchars($file['tags']) ?></p>
                                <?php endif; ?>

                                <?php if ($_COOKIE['id'] == $file['uploaded_by']): ?>
                                    <form method='POST' action='assets/php/delete.php'>
                                        <input type='hidden' name='file_id' value='<?= $file['id'] ?>'>
                                        <button type='submit' class='btn-ghost btn-delete'>Törlés</button>
                                    </form>
                                <?php endif; ?>
                            </article>
                        <?php
                        endif;
                    endwhile; ?>
                </div>
            <?php else: ?>
                <div class="card">
                    <p>Még nincsenek feltöltött fájlok.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>