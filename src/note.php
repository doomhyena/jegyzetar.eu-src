<?php
    // norbi: note.php (sok részt a index.php-bol emeltem at)
    // -->jegyzet neve
    // -->jegyzet megtekintes/letoltes
    // -->kommenteles
    // -->ertekeles
    // -->egységes stilus a tobbi oldallal
    require "assets/php/db.php";

    if(!isset($_COOKIE['id'])){
        header("Location: reglog.php");
    }

    $sql = "SELECT * FROM users WHERE id='" . $_COOKIE['id'] . "'";
    $found_user = $conn->query($sql);
    $user = $found_user->fetch_assoc();

    $sql = "SELECT * FROM notifys WHERE toid = $user[id] AND readed = 0";
    $founded_notify = $conn->query($sql);
    $notify_number = mysqli_num_rows($founded_notify);
    $note_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($note_id <= 0) {
        http_response_code(400);
        $note = null;
    } else {
        $stmt = $conn->prepare("SELECT * FROM files WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $note_id);
        $stmt->execute();
        $note = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
    }


    // norbi: kedvencezés
    if (isset($_POST['favorite-btn'])) {
        if (isset($_POST['favorite_file_id'])) {
            $file_id = (int)$_POST['favorite_file_id'];
            $user_id = (int)$user['id'];

            $check_sql = "SELECT id FROM favorites WHERE file_id = $file_id AND user_id = $user_id";
            $check_result = $conn->query($check_sql);
            
            if ($check_result && $check_result->num_rows > 0) {
                $conn->query("DELETE FROM favorites WHERE file_id = $file_id AND user_id = $user_id");
            } else {
                $conn->query("INSERT INTO favorites (file_id, user_id) VALUES ($file_id, $user_id)");
            }

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

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

    require "assets/php/lang.php";
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title>Jegyzet</title>
    <meta charset="UTF-8">
    <meta name="description" content="Iskolai jegyzeteket megosztó oldal">
    <meta name="keywords" content="iskola, jegyzet, megosztás, tanulás">
    <meta name="author" content="Baranyai Norbert, Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.aurora.css">
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

            // norbi: kedvenc státusz ellenőrzése
            $is_favorite = false;
            $fav_check = $conn->query("SELECT id FROM favorites WHERE file_id = $file_id AND user_id = " . (int)$user['id']);
            if ($fav_check && $fav_check->num_rows > 0) {
                $is_favorite = true;
            }

            $user_dir = "users/" . ($uploader['username'] ?? '') . "/";
            $safe_path = $user_dir . $note['file_name'];
            ?>


            <article class="card note-card">
                <header class="card-head">
                    <h1 class="entry-title"><?= $file_name ?></h1>
                    <div style="display: flex; gap: 8px;">
                        <!-- norbi: kedvencezési gomb -->
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="favorite_file_id" value="<?= $file_id ?>">
                            <button type="submit" name="favorite-btn" class="favorite-btn <?= $is_favorite ? 'favorited' : '' ?>">
                                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <?php if ($is_favorite): ?>
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                                    <?php else: ?>
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="none" stroke="currentColor" stroke-width="2"/>
                                    <?php endif; ?>
                                </svg>
                                <span><?= $is_favorite ? 'Kedvencek' : 'Kedvencezés' ?></span>
                            </button>
                        </form>
                        <a class="entry-download-btn" href="assets/php/download.php?id=<?= $file_id ?>">
                            <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 3v10m0 0l-4-4m4 4l4-4M4 17v3h16v-3"></path>
                            </svg>
                            Letöltés
                        </a>
                    </div>
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
                <!-- norbi: átlag értékelés és értékelési forma egységesítése-->
                <div class="rating-section">
                    <h3>Értékelés</h3>
                    <p><b>Átlag értékelés:</b> <?= $avg ?> (<?= $cnt ?> értékelés)</p>
                    
                    <form method="post" action="" class="rating-form filters-inner">
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
                        <!-- norbi: rating stilus illeszkedése a többiekhez -->
                        <button type="submit" name="rate-btn" class="rate-btn">
                            <svg class="icon icon-star" viewBox="0 0 24 24" aria-hidden="true">
                                <polygon points="12,2 15,8 22,9 17,14 18,21 12,18 6,21 7,14 2,9 9,8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Értékelés küldése</span>
                        </button>
                    </form>
                </div>

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
