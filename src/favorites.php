<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    if (!isset($_COOKIE['id']) || !ctype_digit($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $user_id = (int)$_COOKIE['id'];

    $user_result = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$user_id]);
    if (!$user_result || $user_result->num_rows === 0) {
        header("Location: reglog.php");
        exit;
    }
    $user = $user_result->fetch_assoc();

    $notify_number = 0;
    $nf = db_query($conn, "SELECT id FROM notifys WHERE toid = ? AND readed = 0", "i", [$user_id]);
    if ($nf) {
        $notify_number = (int)$nf->num_rows;
    }

    $page_theme = 'default';

    $sql = "SELECT fav.created_at AS favorited_at, f.*, u.username AS uploader_username, IFNULL(r.avg_rating, 0) AS avg_rating, IFNULL(r.rating_count, 0) AS rating_count FROM favorites fav INNER JOIN files f ON f.id = fav.file_id LEFT JOIN users u ON u.id = f.uploaded_by LEFT JOIN ( SELECT file_id, AVG(rating) AS avg_rating, COUNT(*) AS rating_count FROM ratings GROUP BY file_id) r ON r.file_id = f.id WHERE fav.user_id = ? ORDER BY fav.created_at DESC";
    $favRes = db_query($conn, $sql, "i", [$user_id]);
    $favorites = [];
    if ($favRes) {
        while ($row = $favRes->fetch_assoc()) {
            $favorites[] = $row;
        }
    }

?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title><?= htmlspecialchars(t('nav_favorites') ?: 'Kedvenceim') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body class="theme-<?= htmlspecialchars($page_theme) ?>">
<?php include 'assets/php/navbar.php'; ?>
<div class="content-wrapper w-full">
    <?php include "assets/php/ads.php"; ?>
    <div class="main w-full max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6">
        <div class="fav-header">
            <div class="fav-titlebar">
                <div class="fav-title">
                    <svg class="fav-heart" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                    </svg>
                    <h1><?= htmlspecialchars(t('nav_favorites') ?: 'Kedvenceim') ?></h1>
                </div>
                <div class="fav-count">
                    <span class="fav-count-pill">
                        <?= (int)count($favorites) ?>
                    </span>
                </div>
            </div>
            <p class="fav-subtitle entry-meta">
                Itt vannak a kedvencnek jelölt anyagaid, gyors elérés, letöltés, részletek.
            </p>
        </div>
        <?php if (!empty($favorites)): ?>
            <div class="fav-grid">
                <?php foreach ($favorites as $f):
                    $file_id = (int)$f['id'];
                    $title = (string)($f['name'] ?? '');
                    $desc = trim((string)($f['description'] ?? ''));
                    $subject = trim((string)($f['subject'] ?? ''));
                    $tags_raw = trim((string)($f['tags'] ?? ''));
                    $downloads = isset($f['download_count']) ? (int)$f['download_count'] : 0;

                    $uploader_username = (string)($f['uploader_username'] ?? 'ismeretlen');
                    $uploader_safe = htmlspecialchars($uploader_username);

                    $ext = strtolower(pathinfo((string)($f['file_name'] ?? ''), PATHINFO_EXTENSION));
                    $ext = $ext !== '' ? $ext : 'file';

                    $favorited_at = !empty($f['favorited_at']) ? (string)$f['favorited_at'] : '';

                    $avg = (float)($f['avg_rating'] ?? 0);
                    $cnt = (int)($f['rating_count'] ?? 0);

                    $thumb = '';
                    if (!empty($f['tn_name'])) {
                        $candidate = __DIR__ . "/users/" . $uploader_username . "/" . $f['tn_name'];
                        if (is_file($candidate)) {
                            $thumb = "users/" . rawurlencode($uploader_username) . "/" . rawurlencode($f['tn_name']);
                        }
                    }

                    $tags = [];
                    if ($tags_raw !== '') {
                        $tags = array_values(array_filter(array_map('trim', preg_split('/[,;]+/u', $tags_raw))));
                    }
                    ?>
                    <article class="card fav-card">
                        <div class="fav-top">
                            <div class="fav-thumb">
                                <?php if ($thumb !== ''): ?>
                                    <img src="<?= htmlspecialchars($thumb) ?>" alt="" loading="lazy">
                                <?php else: ?>
                                    <div class="fav-thumb-fallback" aria-hidden="true">
                                        <?= fav_file_icon_svg($ext) ?>
                                        <span class="fav-ext"><?= htmlspecialchars(strtoupper($ext)) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="fav-main">
                                <div class="fav-head">
                                    <h2 class="fav-titleline">
                                        <a class="fav-titlelink" href="note.php?id=<?= $file_id ?>">
                                            <?= htmlspecialchars($title) ?>
                                        </a>
                                    </h2>
                                    <a class="uploader-name fav-uploader" href="profile.php?user=<?= rawurlencode($uploader_username) ?>">
                                        @<?= $uploader_safe ?>
                                    </a>
                                </div>
                                <div class="fav-meta-row">
                                    <?php if ($subject !== ''): ?>
                                        <span class="fav-chip fav-chip-subject"><?= htmlspecialchars($subject) ?></span>
                                    <?php endif; ?>
                                    <span class="fav-chip"><?= htmlspecialchars(strtoupper($ext)) ?></span>
                                    <span class="fav-chip"><?= t('label_downloads') ?>: <strong><?= $downloads ?></strong></span>
                                    <?php if (!empty($f['file_size'])): ?>
                                        <span class="fav-chip"><?= htmlspecialchars(format_bytes((int)$f['file_size'])) ?></span>
                                    <?php endif; ?>
                                    <span class="fav-chip fav-chip-rating">
                                        <?= fav_star_row($avg) ?>
                                        <strong><?= number_format($avg, 2) ?></strong>
                                        <span class="fav-rating-count">(<?= $cnt ?>)</span>
                                    </span>
                                    <?php if ($favorited_at !== ''): ?>
                                        <span class="fav-chip fav-chip-favdate" title="Kedvencekhez adva">
                                            ❤ <?= htmlspecialchars(substr($favorited_at, 0, 10)) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($desc !== ''): ?>
                                    <p class="fav-desc"><?= htmlspecialchars($desc) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($tags)): ?>
                                    <div class="fav-tags" aria-label="<?= htmlspecialchars(t('label_tags')) ?>">
                                        <?php foreach (array_slice($tags, 0, 8) as $tg): ?>
                                            <span class="tag-pill"><?= htmlspecialchars($tg) ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($tags) > 8): ?>
                                            <span class="tag-pill tag-pill-more">+<?= count($tags) - 8 ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="fav-actions">
                                    <a class="btn-ghost" href="note.php?id=<?= $file_id ?>"><?= t('btn_details') ?></a>
                                    <a class="btn-cta" href="assets/php/download.php?id=<?= $file_id ?>">
                                        <svg class="icon icon-download" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M12 3v10m0 0l-4-4m4 4l4-4M4 17v3h16v-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                        <?= t('btn_download') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card fav-empty">
                <div class="fav-empty-icon" aria-hidden="true">♡</div>
                <h2><?= htmlspecialchars(t('empty_no_files') ?: 'Még nincsenek kedvenceid') ?></h2>
                <p class="entry-meta">Nyiss meg egy jegyzetet és nyomj rá a kedvencek gombra, utána itt megjelenik.</p>
                <div class="fav-empty-actions">
                    <a class="btn-cta" href="search.php"><?= htmlspecialchars(t('nav_search') ?: 'Keresés') ?></a>
                    <a class="btn-ghost" href="index.php"><?= htmlspecialchars(t('nav_home') ?: 'Főoldal') ?></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
