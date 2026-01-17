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

    $currentUserId = (int)$_COOKIE['id'];
    $userRes = db_query($conn, "SELECT id, username, admin FROM users WHERE id = ? LIMIT 1", "i", [$currentUserId]);
    $user = ($userRes && $userRes->num_rows > 0) ? $userRes->fetch_assoc() : null;
    if (!$user) {
        header("Location: reglog.php");
        exit;
    }
    $isAdmin = isset($user['admin']) && (int)$user['admin'] === 1;

    $noteId = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? (int)$_GET['id'] : 0;
    if ($noteId <= 0) {
        http_response_code(400);
        exit("Hibás jegyzet azonosító.");
    }

    $noteRes = db_query($conn, "SELECT id, name, uploaded_by FROM files WHERE id = ? LIMIT 1", "i", [$noteId]);
    $note = ($noteRes && $noteRes->num_rows > 0) ? $noteRes->fetch_assoc() : null;
    if (!$note) {
        http_response_code(404);
        exit("Jegyzet nem található.");
    }

    $isOwner = ((int)$note['uploaded_by'] === (int)$currentUserId);
    if (!$isOwner) {
        http_response_code(403);
        exit("Nincs jogosultságod ehhez a statisztikához.");
    }

    $totRes = db_query($conn, "SELECT IFNULL(SUM(views),0) as views, IFNULL(SUM(downloads),0) as downloads, IFNULL(SUM(favorites),0) as favorites, IFNULL(SUM(ratings_count),0) as ratings_count, IFNULL(SUM(ratings_sum),0) as ratings_sum, IFNULL(SUM(flashcards),0) as flashcards FROM file_stats_daily WHERE file_id = ?",  "i",  [$noteId]);
    $tot = $totRes ? $totRes->fetch_assoc() : [
        'views' => 0, 'downloads' => 0, 'favorites' => 0,
        'ratings_count' => 0, 'ratings_sum' => 0, 'flashcards' => 0
    ];

    $ratingsCount = (int)$tot['ratings_count'];
    $ratingsSum = (int)$tot['ratings_sum'];
    $avgRating = $ratingsCount > 0 ? round($ratingsSum / $ratingsCount, 2) : 0.0;

    $trendRes = db_query($conn, "SELECT day, views, downloads, favorites, ratings_count, ratings_sum, flashcards FROM file_stats_daily WHERE file_id = ? ORDER BY day DESC LIMIT 14", "i", [$noteId]);

    $trend = [];
    if ($trendRes) {
        while ($row = $trendRes->fetch_assoc()) $trend[] = $row;
        $trend = array_reverse($trend);
    }

    $eventsRes = db_query($conn, "SELECT event_type, user_id, rating, INET6_NTOA(ip) AS ip_text, user_agent, created_at FROM file_events WHERE file_id = ? ORDER BY created_at DESC LIMIT 25", "i", [$noteId]);

    $events = [];
    if ($eventsRes) {
        while ($row = $eventsRes->fetch_assoc()) $events[] = $row;
    }

    $chartLabels = array_map(fn($d) => $d['day'], $trend);
    $chartViews = array_map(fn($d) => (int)$d['views'], $trend);
    $chartDownloads = array_map(fn($d) => (int)$d['downloads'], $trend);
    $chartFavorites = array_map(fn($d) => (int)$d['favorites'], $trend);
    $chartRatings = array_map(fn($d) => (int)$d['ratings_count'], $trend);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title>Jegyzet statisztika</title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name="author" content="Baranyi Norbert, Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="content-wrapper">
    <?php include "assets/php/ads.php"; ?>
    <div class="main">
        <div class="section-titlebar">
            <h1 class="entry-title">Statisztika: <?= htmlspecialchars($note['name']) ?></h1>
            <div class="stats-actions">
                <a class="btn-ghost" href="note.php?id=<?= (int)$noteId ?>">Vissza a jegyzethez</a>
            </div>
        </div>
        <div class="stats-grid">
            <article class="card">
                <h3>Összesített adatok</h3>
                <p class="entry-meta">Ezek a <b>file_stats_daily</b> összesítései.</p>
                <div class="profile-info-card">
                    <div class="profile-info-item">
                        <div class="profile-info-label">Megtekintések</div>
                        <div class="profile-info-value"><?= (int)$tot['views'] ?></div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-label">Letöltések</div>
                        <div class="profile-info-value"><?= (int)$tot['downloads'] ?></div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-label">Kedvencek</div>
                        <div class="profile-info-value"><?= (int)$tot['favorites'] ?></div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-label">Értékelések száma</div>
                        <div class="profile-info-value"><?= $ratingsCount ?></div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-label">Átlag értékelés</div>
                        <div class="profile-info-value"><?= htmlspecialchars((string)$avgRating) ?></div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-label">Flashcards</div>
                        <div class="profile-info-value"><?= (int)$tot['flashcards'] ?></div>
                    </div>
                </div>
                <?php if ($isAdmin): ?>
                    <p class="admin-note">Admin mód: nyers IP + teljes User-Agent látszik.</p>
                <?php else: ?>
                    <p class="admin-note">Privát mód: IP anonimizálva, User-Agent rövidítve.</p>
                <?php endif; ?>
            </article>
            <article class="card">
                <h3>Mini grafikon (14 nap)</h3>
                <?php if (!empty($trend)): ?>
                    <div class="chart-wrap">
                        <canvas id="statsChart" width="900" height="280"
                                aria-label="Megtekintések, letöltések, kedvencek, értékelések grafikon"
                                role="img"></canvas>
                    </div>
                    <div class="chart-legend">
                        <span><span class="legend-dot" style="background:#60a5fa"></span>Megtekintések</span>
                        <span><span class="legend-dot" style="background:#34d399"></span>Letöltések</span>
                        <span><span class="legend-dot" style="background:#facc15"></span>Kedvencek</span>
                        <span><span class="legend-dot" style="background:#f97316"></span>Értékelések (db)</span>
                        <span class="legend-hint">Bal tengely: views/downloads • Jobb tengely: favorites/ratings</span>
                    </div>
                <?php else: ?>
                    <p class="entry-meta">Még nincs elég adat a grafikonhoz.</p>
                <?php endif; ?>
            </article>
        </div>
        <div class="stats-stack">
            <article class="card">
                <h3>Trend (utolsó 14 nap)</h3>
                <?php if (!empty($trend)): ?>
                    <div class="trend-table-wrap">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Nap</th>
                                <th>Megtekintés</th>
                                <th>Letöltés</th>
                                <th>Kedvenc</th>
                                <th>Értékelés db</th>
                                <th>Értékelés összeg</th>
                                <th>Flashcards</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($trend as $d): ?>
                                <tr>
                                    <td><?= htmlspecialchars($d['day']) ?></td>
                                    <td><?= (int)$d['views'] ?></td>
                                    <td><?= (int)$d['downloads'] ?></td>
                                    <td><?= (int)$d['favorites'] ?></td>
                                    <td><?= (int)$d['ratings_count'] ?></td>
                                    <td><?= (int)$d['ratings_sum'] ?></td>
                                    <td><?= (int)$d['flashcards'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="entry-meta">Még nincs napi statisztika ehhez a jegyzethez.</p>
                <?php endif; ?>
            </article>
            <article class="card">
                <h3>Utolsó események (file_events)</h3>
                <p class="entry-meta">Utolsó 25 esemény (olvasható feed formában).</p>

                <?php if (!empty($events)): ?>
                    <div class="events-feed">
                        <?php foreach ($events as $e): ?>
                            <?php
                                [$ico, $label] = fmt_event_label((string)$e['event_type']);
                                $uid = ($e['user_id'] !== null) ? (int)$e['user_id'] : null;
                                $rating = ($e['rating'] !== null) ? (int)$e['rating'] : null;

                                $ipText = $e['ip_text'] ?? null;
                                $ipShown = $isAdmin ? ($ipText ?: '—') : anonymize_ip($ipText ?: '—');

                                $ua = (string)($e['user_agent'] ?? '—');
                                $uaShort = $ua;
                                if (!$isAdmin && $ua !== '—') {
                                    $uaShort = mb_strlen($ua, 'UTF-8') > 120 ? (mb_substr($ua, 0, 120, 'UTF-8') . '…') : $ua;
                                }
                            ?>
                            <div class="event-item">
                                <div class="event-head">
                                    <div class="event-left">
                                        <span class="event-badge"><?= htmlspecialchars($ico) ?> <?= htmlspecialchars($label) ?></span>
                                        <span class="event-time"><?= htmlspecialchars($e['created_at']) ?></span>
                                    </div>

                                    <div class="event-right">
                                        <span class="event-pill">User: <?= $uid !== null ? (string)$uid : '—' ?></span>
                                        <span class="event-pill">Rating: <?= $rating !== null ? (string)$rating : '—' ?></span>

                                        <?php if ($isAdmin): ?>
                                            <span class="event-pill event-pill-danger">IP: <?= htmlspecialchars($ipShown) ?></span>
                                        <?php else: ?>
                                            <span class="event-pill">IP: <?= htmlspecialchars($ipShown) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="event-body">
                                    <div class="ua-label">User-Agent</div>
                                    <?php if ($isAdmin): ?>
                                        <div class="ua-box ua-full"><?= htmlspecialchars($ua ?: '—') ?></div>
                                    <?php else: ?>
                                        <div class="ua-box ua-short"><?= htmlspecialchars($uaShort ?: '—') ?></div>

                                        <?php if ($ua !== '—' && mb_strlen($ua, 'UTF-8') > 120): ?>
                                            <details class="ua-details">
                                                <summary>Részletek</summary>
                                                <div class="ua-box ua-full"><?= htmlspecialchars($ua) ?></div>
                                            </details>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="entry-meta">Nincs esemény ehhez a jegyzethez.</p>
                <?php endif; ?>
            </article>
        </div>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
<?php if (!empty($trend)): ?>
    <script>
        (() => {
            const labels = <?= json_encode($chartLabels, JSON_UNESCAPED_UNICODE) ?>;
            const views = <?= json_encode($chartViews, JSON_UNESCAPED_UNICODE) ?>;
            const downloads = <?= json_encode($chartDownloads, JSON_UNESCAPED_UNICODE) ?>;
            const favorites = <?= json_encode($chartFavorites, JSON_UNESCAPED_UNICODE) ?>;
            const ratings = <?= json_encode($chartRatings, JSON_UNESCAPED_UNICODE) ?>;
            const canvas = document.getElementById('statsChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            const dpr = window.devicePixelRatio || 1;
            const cssW = canvas.clientWidth || 900;
            const cssH = canvas.clientHeight || 280;
            canvas.width  = Math.floor(cssW * dpr);
            canvas.height = Math.floor(cssH * dpr);
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            const W = cssW, H = cssH;
            const pad = {l: 46, r: 46, t: 14, b: 30};
            const maxLeft  = Math.max(1, ...views, ...downloads);
            const maxRight = Math.max(1, ...favorites, ...ratings);
            const xStep = (labels.length > 1) ? (W - pad.l - pad.r) / (labels.length - 1) : 0;
            const yLeft  = v => pad.t + (H - pad.t - pad.b) * (1 - (v / maxLeft));
            const yRight = v => pad.t + (H - pad.t - pad.b) * (1 - (v / maxRight));

            ctx.clearRect(0,0,W,H);

            ctx.globalAlpha = 0.12;
            ctx.strokeStyle = '#94a3b8';
            ctx.lineWidth = 1;
            for (let i=0;i<=4;i++){
                const y = pad.t + (H - pad.t - pad.b) * (i/4);
                ctx.beginPath();
                ctx.moveTo(pad.l, y);
                ctx.lineTo(W - pad.r, y);
                ctx.stroke();
            }
            ctx.globalAlpha = 1;

            function drawLine(arr, color, mapY){
                ctx.strokeStyle = color;
                ctx.lineWidth = 2;
                ctx.beginPath();
                arr.forEach((v,i)=>{
                    const x = pad.l + xStep * i;
                    const y = mapY(v);
                    if (i===0) ctx.moveTo(x,y);
                    else ctx.lineTo(x,y);
                });
                ctx.stroke();

                ctx.fillStyle = color;
                arr.forEach((v,i)=>{
                    const x = pad.l + xStep * i;
                    const y = mapY(v);
                    ctx.beginPath();
                    ctx.arc(x,y,3,0,Math.PI*2);
                    ctx.fill();
                });
            }

            drawLine(views, '#60a5fa', yLeft);
            drawLine(downloads, '#34d399', yLeft);
            drawLine(favorites, '#facc15', yRight);
            drawLine(ratings, '#f97316', yRight);

            ctx.fillStyle = '#cbd5e1';
            ctx.font = '12px system-ui, -apple-system, Segoe UI, Roboto, Arial';
            const every = Math.max(1, Math.floor(labels.length / 6));
            labels.forEach((lab,i)=>{
                if (i % every !== 0 && i !== labels.length - 1) return;
                const x = pad.l + xStep * i;
                ctx.fillText(String(lab).slice(5), x - 14, H - 10); // MM-DD
            });

            ctx.globalAlpha = 0.7;
            ctx.fillText('0',  pad.l - 18, H - pad.b);
            ctx.fillText(String(maxLeft),  6, pad.t + 10);
            ctx.fillText(String(maxRight), W - pad.r + 6, pad.t + 10);
            ctx.globalAlpha = 1;
        })();
    </script>
<?php endif; ?>
</body>
</html>
