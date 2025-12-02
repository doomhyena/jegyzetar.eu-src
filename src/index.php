<?php
    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once 'assets/php/functions.php';

    $isLoggedIn = isset($_COOKIE['id']);
    $user = null;
    $notify_number = 0;

    if ($isLoggedIn) {
        $uid = (int)$_COOKIE['id'];

        $res = $conn->query("SELECT id, username FROM users WHERE id = $uid LIMIT 1");
        $user = ($res && $res->num_rows) ? $res->fetch_assoc() : null;

        $nf = $conn->query("SELECT id FROM notifys WHERE toid = $uid AND readed = 0");
        $notify_number = $nf ? $nf->num_rows : 0;
    }

    $currentUserId = $user['id'] ?? 0;

    $today = date("m-d");
    $nd = $conn->query("SELECT nevek FROM namedays WHERE datum = '".$conn->real_escape_string($today)."' LIMIT 1");
    $nameday = ($nd && $nd->num_rows > 0)
            ? $nd->fetch_assoc()['nevek']
            : t('nameday_none_today');

    $popular_sql = "
    SELECT f.*, IFNULL(AVG(r.rating),0) AS avg_rating, COUNT(r.id) AS rating_count
    FROM files f
    LEFT JOIN ratings r ON f.id = r.file_id
    GROUP BY f.id
    ORDER BY avg_rating DESC, rating_count DESC
    LIMIT 8";
    $popular_result = $conn->query($popular_sql);

    $latest_result = $conn->query("SELECT * FROM files ORDER BY id DESC LIMIT 12");

    // norbi: kedvencezés kezelése
    if (isset($_POST['favorite-btn'])) {
        if (!$isLoggedIn) {
            header("Location: reglog.php");
            exit;
        }

        $file_id = (int)$_POST['favorite_file_id'];

        $check_sql = "SELECT id FROM favorites WHERE file_id = $file_id AND user_id = $currentUserId";
        $check_result = $conn->query($check_sql);

        if ($check_result && $check_result->num_rows > 0) {
            $conn->query("DELETE FROM favorites WHERE file_id = $file_id AND user_id = $currentUserId");
        } else {
            $conn->query("INSERT INTO favorites (file_id, user_id) VALUES ($file_id, $currentUserId)");
        }

        header("Location: index.php#file-$file_id");
        exit;
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
    }

    $displayName = $user['username'] ?? t('guest');
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title><?= t('index_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
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
                    <h1 class="hero-title">
                        <?= t('hero_greeting') . " " . $displayName ?>!
                    </h1>
                    <p class="hero-sub"><?= t('hero_nameday') ?>: <strong><?= $nameday ?></strong></p>
                </div>
                <div class="hero-actions">
                    <a class="btn-cta" href="<?= $isLoggedIn ? 'upload.php' : 'reglog.php' ?>">
                        + <?= t('nav_upload') ?>
                    </a>
                </div>
            </div>
        </section>
        <section class="content-main">
            <div class="section-titlebar">
                <h3><?= t('home_new_uploads') ?></h3>
                <a class="link-more" href="search.php?sort=new"><?= t('home_all_arrow') ?></a>
            </div>
            <?php if ($latest_result && $latest_result->num_rows > 0): ?>
                <div class="content-grid grid-large">
                    <?php while ($file = $latest_result->fetch_assoc()):
                            $uploader_q = $conn->query("SELECT username FROM users WHERE id=".(int)$file['uploaded_by']);
                            $uploader = $uploader_q ? $uploader_q->fetch_assoc() : ['username'=>'ismeretlen'];

                            $file_id = (int)$file['id'];
                            $name = htmlspecialchars($file['name']);
                            $username = htmlspecialchars($uploader['username']);
                            $ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);

                            $avg_q = $conn->query("SELECT AVG(rating) AS avg, COUNT(*) AS c FROM ratings WHERE file_id=$file_id");
                            $avg = 0; $cnt = 0;
                            if ($avg_q && $avg_q->num_rows) {
                                $d = $avg_q->fetch_assoc();
                                $avg = number_format((float)$d['avg'],2);
                                $cnt = (int)$d['c'];
                            }

                            // norbi: kedvenc státusz ellenőrzése
                            $is_favorite = false;
                            if ($isLoggedIn) {
                                $fav_check = $conn->query("SELECT id FROM favorites WHERE file_id = $file_id AND user_id = $currentUserId");
                                if ($fav_check && $fav_check->num_rows > 0) {
                                    $is_favorite = true;
                                }
                            }

                            $safe_path = "users/$username/".$file['file_name'];
                        ?>
                        <article class="card note-card" id="file-<?= $file_id ?>">
                            <header class="card-head">
                                <h4 class="entry-title"><?= $name ?></h4>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <!-- norbi: kedvencezési gomb index.php kártyáihoz, nem muszaj itt lennie btw -->
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="favorite_file_id" value="<?= $file_id ?>">
                                        <button type="submit" name="favorite-btn" class="favorite-btn <?= $is_favorite ? 'favorited' : '' ?>" <?= !$isLoggedIn ? 'disabled' : '' ?>>
                                            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                                <?php if ($is_favorite): ?>
                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                                                <?php else: ?>
                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="none" stroke="currentColor" stroke-width="2"/>
                                                <?php endif; ?>
                                            </svg>
                                        </button>
                                    </form>
                                    <a class="note-desc-btn" href="note.php?id=<?= (int)$file['id'] ?>">
                                        <?= t('btn_details') ?>
                                    </a>
                                    <a class="entry-download-btn" href="assets/php/download.php?id=<?= $file_id ?>">
                                        <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M5 20h14M12 3v12m0 0l-4-4m4 4 4-4"
                                                  fill="none"
                                                  stroke="currentColor"
                                                  stroke-width="2"
                                                  stroke-linecap="round"
                                                  stroke-linejoin="round" />
                                        </svg>
                                        <?= t('btn_download') ?>
                                    </a>

                                </div>
                            </header>
                            <?php if ($ext=="mp4"): ?>
                                <video controls class="file-preview">
                                    <source src="<?= $safe_path ?>" type="video/mp4">
                                </video>
                            <?php elseif ($ext=="pdf"): ?>
                                <iframe src="<?= $safe_path ?>" width="100%" height="500"></iframe>
                            <?php elseif ($ext=="docx"): ?>
                                <p><?= t('note_docx_download_hint') ?>
                                </p>
                            <?php endif; ?>
                            <p><?= t('label_uploaded_by') ?>
                                <a class="uploader-name" href="profile.php?userid=<?= $file['uploaded_by'] ?>">@<?= $username ?></a>
                            </p>
                            <p>
                                <b><?= t('label_rating_average') ?></b>
                                <?= $avg ?> (<?= $cnt ?> <?= t('suffix_ratings_paren') ?>)
                            </p>
                            <form method="post">
                                <input type="hidden" name="rate_file_id" value="<?= $file_id ?>">
                                <div class="star-rating">
                                    <?php
                                        $usr_rate = 0;
                                        if ($isLoggedIn) {
                                            $r = $conn->query("SELECT rating FROM ratings WHERE file_id=$file_id AND user_id=$currentUserId");
                                            if ($r && $r->num_rows) $usr_rate = $r->fetch_assoc()['rating'];
                                        }

                                        for ($i = 5; $i >= 1; $i--) {
                                            $checked = ($usr_rate == $i) ? "checked" : "";
                                            echo "<input type='radio' name='rating' value='$i' $checked ".(!$isLoggedIn ? "disabled":"").">";
                                            echo "<label>★</label>";
                                        }
                                    ?>
                                </div>
                                <?php if ($isLoggedIn): ?>
                                    <button type="submit" name="rate-btn"><?= t('btn_send') ?></button>                                <?php else: ?>
                                    <button disabled><?= t('btn_login_to_rate') ?></button>
                                <?php endif; ?>  -->
                            </form>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </section>
        <aside class="content-aside">
            <div class="section-titlebar">
                <h3><?= t('sidebar_top_rated') ?></h3>
            </div>
            <?php if ($popular_result && $popular_result->num_rows > 0): ?>
                <div class="list-compact">
                    <?php while ($file = $popular_result->fetch_assoc()): ?>
                        <p><?= htmlspecialchars($file['name']) ?> — ⭐ <?= number_format($file['avg_rating'],2) ?></p>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>