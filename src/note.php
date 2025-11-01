<?php
// norbi: note.php (sok részt a index.php-bol emeltem at)
// -->jegyzet neve
// -->jegyzet megtekintes/letoltes
// -->kommenteles
// -->ertekeles

require "assets/php/db.php";

if (!isset($_COOKIE['id'])) {
    header("Location: index.php");
    exit;
}

$sql = "SELECT * FROM users WHERE id='" . $_COOKIE['id'] . "'";
$found_user = $conn->query($sql);
$user = $found_user->fetch_assoc();

$sql = "SELECT * FROM notifys WHERE toid = $user[id] AND readed = 0";
$founded_notify = $conn->query($sql);
$notify_number = mysqli_num_rows($founded_notify);

if (isset($_POST['comment-btn'])) {
    if (isset($_POST['post_id'])) {
        $postid = (int)$_POST['post_id'];
        $text = $conn->real_escape_string($_POST['comment-text']);
        $conn->query("INSERT INTO comments (userid, postid, text) VALUES ('{$user['id']}', '{$postid}', '{$text}')");

        if (isset($_GET['uploader'])) {
            $uploader = (int)$_GET['uploader'];
            $conn->query("INSERT INTO notifys (fromid, toid, notifytype, readed) VALUES ('{$user['id']}', '{$uploader}', 'comment', 0)");
        }
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        echo "<script>alert('Hiba történt a komment írásakor!');</script>";
    }
}

if (isset($_POST['rate-btn']) && isset($_POST['rate_file_id']) && isset($_POST['rating'])) {
    $file_id = (int)$_POST['rate_file_id'];
    $rating  = (int)$_POST['rating'];
    $user_id = (int)$user['id'];

    $check_sql = "SELECT id FROM ratings WHERE file_id = $file_id AND user_id = $user_id";
    $check_result = $conn->query($check_sql);
    if ($check_result && $check_result->num_rows > 0) {
        $conn->query("UPDATE ratings SET rating = $rating WHERE file_id = $file_id AND user_id = $user_id");
    } else {
        $conn->query("INSERT INTO ratings (file_id, user_id, rating) VALUES ($file_id, $user_id, $rating)");
    }

    echo "<meta http-equiv='refresh' content='0'>";
    exit;
}

$note_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($note_id > 0) {
    $sql = "SELECT * FROM files WHERE id = $note_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $note = $result->fetch_assoc();
    } else {
        $note = null;
    }
} else {
    $note = null;
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <title>Jegyzet</title>
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
    <?php include 'assets/php/navbar.php'; ?>
    <div class="main">
        <?php if ($note): ?>
            <?php
            $file_id = (int)$note['id'];

            $file_name = htmlspecialchars($note['name']);
            $ext = pathinfo($note['file_name'], PATHINFO_EXTENSION);


            $uploader_q = $conn->query("SELECT * FROM users WHERE id=" . (int)$note['uploaded_by']);
            $uploader = $uploader_q ? $uploader_q->fetch_assoc() : ['username' => 'ismeretlen'];
            $username = htmlspecialchars($uploader['username'] ?? 'ismeretlen');

            // értékelések lekérése 
            $avg_q = $conn->query("SELECT IFNULL(AVG(rating),0) as avg_rating, COUNT(id) as rating_count FROM ratings WHERE file_id = $file_id");
            $avg_data = $avg_q ? $avg_q->fetch_assoc() : ['avg_rating' => 0, 'rating_count' => 0];
            $avg = number_format((float)$avg_data['avg_rating'], 2, '.', '');
            $cnt = (int)$avg_data['rating_count'];

            $user_dir = "users/" . ($uploader['username'] ?? '') . "/";
            $safe_path = $user_dir . $note['file_name'];
            ?>


            <article class="card note-card">
                <header class="card-head">
                    <h1 class="entry-title"><?= $file_name ?></h1>
                    <a class="entry-download-btn" href="assets/php/download.php?id=<?= $file_id ?>">
                        <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 3v10m0 0l-4-4m4 4l4-4M4 17v3h16v-3"></path>
                        </svg>
                        Letöltés
                    </a>
                </header>

                <?php if ($ext === 'docx'): ?>
                    <p><b>Ez egy .docx fájl. A megtekintéshez töltsd le és nyisd meg Microsoft Word-ben.</b></p>
                <?php elseif ($ext === 'mp4'): ?>
                    <video controls class="file-preview">
                        <source src="<?= htmlspecialchars($safe_path) ?>" type="video/mp4">
                        A te böngésződ nem támogatja a videocímkét.
                    </video>
                <?php elseif ($ext === 'pdf'): ?>
                    <iframe src="<?= htmlspecialchars($safe_path) ?>" width="100%" height="500"></iframe>
                <?php endif; ?>

                <p>Feltöltötte:
                    <a class="uploader-name" href="profile.php?userid=<?= (int)$note['uploaded_by'] ?>"><?= $username ?></a>
                </p>
                <p><b>Átlag értékelés:</b> <?= $avg ?> (<?= $cnt ?> értékelés)</p>

                <form method="post" action="" class="rating">
                    <input type="hidden" name="rate_file_id" value="<?= $file_id ?>">
                    <div class="star-rating" aria-label="Értékelés 1–5">
                        <?php
                        $usr_rate = 0;
                        $rs = $conn->query("SELECT rating FROM ratings WHERE file_id = $file_id AND user_id = " . (int)$user['id']);
                        if ($rs && $rs->num_rows > 0) {
                            $usr_rate = (int)$rs->fetch_assoc()['rating'];
                        }
                        for ($i = 5; $i >= 1; $i--) {
                            $checked = ($usr_rate === $i) ? 'checked' : '';
                            $input_id = "star{$i}_note_{$file_id}";
                            echo '<input type="radio" id="' . $input_id . '" name="rating" value="' . $i . '" ' . $checked . '>';
                            echo '<label for="' . $input_id . '" title="' . $i . ' csillag">★</label>';
                        }
                        ?>
                    </div>
                    <button type="submit" name="rate-btn" class="rate-btn">Küldés</button>
                </form>

                <div class="comments-section">
                    <h3>Kommentek</h3>
                    <?php
                    $comments_sql = "SELECT c.*, u.username FROM comments c 
                                   JOIN users u ON c.userid = u.id 
                                   WHERE c.postid = $file_id 
                                   ORDER BY c.id DESC";
                    $comments_result = $conn->query($comments_sql);

                    if ($comments_result && $comments_result->num_rows > 0):
                        while ($comment = $comments_result->fetch_assoc()):
                    ?>
                            <div class="comment">
                                <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
                                <p><?= htmlspecialchars($comment['text']) ?></p>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <p class="entry-meta">Még nincs komment.</p>
                    <?php endif; ?>

                    <form method="post" action="" class="comment-form filters-inner">
                        <input type="hidden" name="post_id" value="<?= $file_id ?>">
                        <textarea name="comment-text" class="input" placeholder="Írj kommentet..." required rows="3"></textarea>
                        <button type="submit" name="comment-btn" class="btn-search">
                            <svg class="icon icon-send" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Küldés</span>
                        </button>
                    </form>
                </div>
            </article>
        <?php else: ?>
            <div class="card">
                <h1>Jegyzet nem található!</h1>
                <p>A keresett jegyzet nem létezik vagy törölve lett.</p>
                <a href="index.php" class="btn-cta">Vissza a főoldalra</a>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'assets/php/footer.php'; ?>
</body>

</html>