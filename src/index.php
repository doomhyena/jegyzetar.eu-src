<?php
    require "assets/php/db.php";

    //norbi: engem az index.phpra dob errorokkal szval ezt itt hagyom
    if(!isset($_COOKIE['id'])){
        header("Location: reglog.php");
    }


    $sql = "SELECT * FROM users WHERE id='" . $conn->real_escape_string($_COOKIE['id']) . "'";
    $found_user = $conn->query($sql);
    $user = $found_user ? $found_user->fetch_assoc() : null;

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

    $today = date("m-d");
    $sql = "SELECT nevek FROM namedays WHERE datum='$today'";
    $result = $conn->query($sql);
    $nameday = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['nevek'] : "Nincs névnap ma.";
    $popular_sql = "SELECT f.*,
        IFNULL(AVG(r.rating),0) as avg_rating, COUNT(r.id) as rating_count
        FROM files f
        LEFT JOIN ratings r ON f.id = r.file_id
        GROUP BY f.id
        ORDER BY avg_rating DESC, rating_count DESC
        LIMIT 8";
    $popular_result = $conn->query($popular_sql);

    $latest_result = $conn->query("SELECT * FROM files ORDER BY id DESC LIMIT 12");
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Főoldal</title>
    <meta name="description" content="Iskolai jegyzeteket megosztó oldal">
    <meta name="keywords" content="iskola, jegyzet, megosztás, tanulás">
    <meta name="author" content="Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.aurora.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="main">
    <div class="home-grid">
        <section class="hero card">
            <div class="hero-body">
                <div class="hero-text">
                    <h1 class="hero-title">Szia, <?= htmlspecialchars($user['firstname']) ?>!</h1>
                    <p class="hero-sub">Mai névnap: <strong><?= htmlspecialchars($nameday) ?></strong></p>
                </div>
                <div class="hero-actions">
                    <a class="btn-cta" href="upload.php">+ Új jegyzet</a>
                    <a class="btn-ghost" href="myfiles.php">Saját feltöltéseim</a>
                </div>
            </div>
            <div class="hero-pills">
                <a href="search.php?q=pdf" class="pill">PDF</a>
                <a href="search.php?q=mp4" class="pill">Videó</a>
                <a href="search.php?q=docx" class="pill">Word</a>
                <a href="search.php?sort=top" class="pill">Top értékelt</a>
                <a href="search.php?sort=new" class="pill">Legújabb</a>
            </div>
        </section>
        <section class="filters card">
            <form class="filters-inner" action="search.php" method="get">
                <input class="input" type="text" name="q" placeholder="Keresés jegyzet cím, tárgy vagy fájltípus alapján…">
                <select class="select" name="sort">
                    <option value="new">Legújabb</option>
                    <option value="top">Legjobb értékelt</option>
                </select>
                <button class="btn-search" type="submit" aria-label="Keresés">
                    <svg class="icon icon-search" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15.5 15.5L21 21M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>Keresés</span>
                </button>
            </form>
        </section>
        <section class="content-main">
            <div class="section-titlebar">
                <h3>Új feltöltések</h3>
                <a class="link-more" href="search.php?sort=new">Összes →</a>
            </div>
            <?php if ($latest_result && $latest_result->num_rows > 0): ?>
                <div class="content-grid grid-large">
                    <?php while ($file = $latest_result->fetch_assoc()):
                        $uploader_q = $conn->query("SELECT * FROM users WHERE id=" . (int)$file['uploaded_by']);
                        $uploader = $uploader_q ? $uploader_q->fetch_assoc() : ['username' => 'ismeretlen'];

                        $file_id = (int)$file['id'];
                        $file_name = htmlspecialchars($file['name']);
                        $username  = htmlspecialchars($uploader['username'] ?? 'ismeretlen');
                        $ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);

                        $avg_q = $conn->query("SELECT IFNULL(AVG(rating),0) as avg_rating, COUNT(id) as rating_count FROM ratings WHERE file_id = $file_id");
                        $avg_data = $avg_q ? $avg_q->fetch_assoc() : ['avg_rating' => 0, 'rating_count' => 0];
                        $avg = number_format((float)$avg_data['avg_rating'], 2, '.', '');
                        $cnt = (int)$avg_data['rating_count'];

                        $user_dir = "users/" . ($uploader['username'] ?? '') . "/";
                        $safe_path = $user_dir . $file['file_name'];
                        ?>
                        <!--norbi: egyedi fileid a hrefhez -->
                        <article class="card note-card" id="file-<?= $file_id ?>">
                            <header class="card-head">
                                <h4 class="entry-title"><?= $file_name ?></h4>
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
                                <a class="uploader-name" href="profile.php?userid=<?= (int)$file['uploaded_by'] ?>"><?= $username ?></a>
                            </p>
                            <p><b>Átlag értékelés:</b> <?= $avg ?> (<?= $cnt ?> értékelés)</p>

                            <form method="post" action="" class="rating">
                                <input type="hidden" name="rate_file_id" value="<?= $file_id ?>">
                                <div class="star-rating" aria-label="Értékelés 1–5">
                                    <?php
                                    $usr_rate = 0;
                                    $rs = $conn->query("SELECT rating FROM ratings WHERE file_id = $file_id AND user_id = ".(int)$user['id']);
                                    if ($rs && $rs->num_rows > 0) { $usr_rate = (int)$rs->fetch_assoc()['rating']; }
                                    for ($i = 5; $i >= 1; $i--) {
                                        $checked = ($usr_rate === $i) ? 'checked' : '';
                                        $input_id = "star{$i}_new_{$file_id}";
                                        echo '<input type="radio" id="'.$input_id.'" name="rating" value="'.$i.'" '.$checked.'>';
                                        echo '<label for="'.$input_id.'" title="'.$i.' csillag">★</label>';
                                    }
                                    ?>
                                </div>
                                <button type="submit" name="rate-btn" class="rate-btn">Küldés</button>
                            </form>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>Nincs új feltöltés.</p>
            <?php endif; ?>
        </section>

        <aside class="content-aside">
            <div class="section-titlebar">
                <h3>Top értékelt</h3>
                <a class="link-more" href="search.php?sort=top">Összes →</a>
            </div>
            <?php if ($popular_result && $popular_result->num_rows > 0): ?>
                <div class="list-compact">
                    <?php while ($file = $popular_result->fetch_assoc()):
                        $uploader_q = $conn->query("SELECT * FROM users WHERE id=" . (int)$file['uploaded_by']);
                        $uploader = $uploader_q ? $uploader_q->fetch_assoc() : ['username' => 'ismeretlen'];

                        $file_id = (int)$file['id'];
                        $file_name = htmlspecialchars($file['name']);
                        $username  = htmlspecialchars($uploader['username'] ?? 'ismeretlen');
                        $avg = number_format((float)$file['avg_rating'], 2, '.', '');
                        $cnt = (int)$file['rating_count'];
                        ?>
                        <article class="mini-card">
                            <div class="mini-main">
                                <h4 class="mini-title"><?= $file_name ?></h4>
                                <p class="mini-meta">Átlag: <b><?= $avg ?></b> · <?= $cnt ?> ért.</p>
                                <a class="uploader-name" href="profile.php?userid=<?= (int)$file['uploaded_by'] ?>"><?= $username ?></a>
                            </div>
                            <a class="mini-download" href="assets/php/download.php?id=<?= $file_id ?>" title="Letöltés">
                                <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 3v10m0 0l-4-4m4 4l4-4M4 17v3h16v-3"></path>
                                </svg>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>Még nincs elég értékelés.</p>
            <?php endif; ?>
        </aside>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
