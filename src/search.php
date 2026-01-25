<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require "assets/php/db.php";
    require_once "assets/php/functions.php";

    $isLoggedIn = isset($_COOKIE['id']);
    $user = null;
    $notify_number = 0;

    if ($isLoggedIn) {
        $uid = (int)$_COOKIE['id'];
        $res = db_query($conn, "SELECT id, username, firstname FROM users WHERE id = ? LIMIT 1", "i", [$uid]);
        if ($res && $res->num_rows > 0) {
            $user = $res->fetch_assoc();
        }

        $nf = db_query($conn, "SELECT id FROM notifys WHERE toid = ? AND readed = 0", "i", [$uid]);
        $notify_number = $nf->num_rows;
    }

    $en_csoportjaim = [];

    if ($isLoggedIn) {
        $cg = db_query($conn, "SELECT id, name FROM groups WHERE owner_id = ? ORDER BY name ASC", "i", [$uid]);

        while ($cg && $row = $cg->fetch_assoc()) {
            $en_csoportjaim[] = $row;
        }
    }

    $q = trim($_GET['q'] ?? '');
    $scope = strtolower($_GET['scope'] ?? 'all');
    $type = strtolower($_GET['type'] ?? 'all');

    $levelRaw = $_GET['level'] ?? 'all';
    $tagRaw  = trim($_GET['tag'] ?? '');
    $mode = strtolower($_GET['mode'] ?? 'all');
    if (!in_array($mode, ['all','any'], true)) $mode = 'all';
    $debug = (int)($_GET['debug'] ?? 0);
    $cursor = $_GET['cursor'] ?? null;
    $sort = strtolower($_GET['sort'] ?? 'newest');
    $page = (int)($_GET['page'] ?? 1);
    if ($page < 1) $page = 1;
    $perPage = 24;

    if (!in_array($scope, ['files','users','all'], true)) $scope = 'all';
    if (!in_array($type, ['all','pdf','mp4','docx'], true)) $type = 'all';
    if (!in_array($sort, ['relevance','newest','oldest','downloads','rating','new','old'], true)) $sort = 'newest';
    if ($sort === 'new') $sort = 'newest';
    if ($sort === 'old') $sort = 'oldest';

    $eduStage = null;
    $eduLevel = null;
    $eduIsNullOnly = false;
    if ($levelRaw !== 'all' && $levelRaw !== '' && $levelRaw !== null) {
        if ($levelRaw === 'none') {
            $eduIsNullOnly = true;
        } elseif (preg_match('/^(hs|uni)-(\d+)$/', (string)$levelRaw, $m)) {
            $eduStage = $m[1];
            $eduLevel = (int)$m[2];

            if ($eduStage === 'hs'  && ($eduLevel < 9 || $eduLevel > 13)) { $eduStage = null; $eduLevel = null; }
            if ($eduStage === 'uni' && ($eduLevel < 1 || $eduLevel > 7))  { $eduStage = null; $eduLevel = null; }
        }
    }

    $userResult = null;

    if ($scope !== 'files') {
        $where = '';
        $types = '';
        $params = [];
        $order = 'ORDER BY u.username ASC';

        if ($q !== '') {
            $like  = '%' . $q . '%';
            $start = $q . '%';

            $where = "WHERE u.username LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ?";
            $types = 'sss';
            $params = [$like, $like, $like];

            $order = "ORDER BY CASE WHEN u.username LIKE ? THEN 0 WHEN u.firstname LIKE ? THEN 1 WHEN u.lastname LIKE ? THEN 2 ELSE 3 END, u.username ASC";

            $types .= 'sss';
            array_push($params, $start, $start, $start);
        }

        $sqlUsers = "SELECT u.id, u.username, u.firstname, u.lastname, u.profile_picture FROM users u $where $order LIMIT 50";
        $userResult = db_query($conn, $sqlUsers, $types, $params);
    }

    $fileResult = null;
    $fileTotal = 0;

    $hasEduStage = false;
    $hasEduLevel = false;
    $hasCreatedAt = false;
    $hasDownloads = false;
    $hasIsPublic = false;

    $columnsRes = $conn->query("SHOW COLUMNS FROM files");
    if ($columnsRes) {
        while ($c = $columnsRes->fetch_assoc()) {
            $col = strtolower($c['Field'] ?? '');
            if ($col === 'edu_stage') $hasEduStage = true;
            if ($col === 'edu_level') $hasEduLevel = true;
            if ($col === 'created_at') $hasCreatedAt = true;
            if ($col === 'download_count' || $col === 'downloads' || $col === 'downloaded') $hasDownloads = true;
            if ($col === 'is_public') $hasIsPublic = true;
        }
    }

    $useEdu = ($hasEduStage && $hasEduLevel);

    if ($scope !== 'users') {
        $tokens = tokenize_query($q);
        $needlesForUi = $tokens ?: ($q !== '' ? [$q] : []);

        $whereBase = [];
        $typesBase = '';
        $paramsBase = [];

        if ($hasIsPublic) {
            $whereBase[] = 'f.is_public = 1';
        }

        if ($q !== '' && $tokens) {
            $clauses = [];
            foreach ($tokens as $tok) {
                $clauses[] = "(f.name LIKE ? OR f.description LIKE ? OR f.subject LIKE ? OR f.file_name LIKE ?)";
                $like = '%' . $tok . '%';
                $typesBase .= 'ssss';
                array_push($paramsBase, $like, $like, $like, $like);
            }
            $glue = ($mode === 'any') ? ' OR ' : ' AND ';
            $whereBase[] = '(' . implode($glue, $clauses) . ')';
        } elseif ($q !== '') {
            $like = '%' . $q . '%';
            $whereBase[] = "(f.name LIKE ? OR f.description LIKE ? OR f.subject LIKE ? OR f.file_name LIKE ?)";
            $typesBase .= 'ssss';
            array_push($paramsBase, $like, $like, $like, $like);
        }

        if ($type !== 'all') {
            $whereBase[] = "LOWER(f.file_name) LIKE ?";
            $typesBase .= 's';
            $paramsBase[] = '%.' . strtolower($type);
        }

        if ($tagRaw !== '') {
            $whereBase[] = 'f.tags LIKE ?';
            $typesBase .= 's';
            $paramsBase[] = '%' . $tagRaw . '%';
        }

        $whereForFacets = $whereBase;
        $typesForFacets = $typesBase;
        $paramsForFacets = $paramsBase;

        if ($useEdu) {
            if ($eduIsNullOnly) {
                $whereBase[] = '(f.edu_stage IS NULL OR f.edu_level IS NULL)';
            } elseif ($eduStage !== null && $eduLevel !== null) {
                $whereBase[] = 'f.edu_stage = ? AND f.edu_level = ?';
                $typesBase .= 'si';
                array_push($paramsBase, $eduStage, $eduLevel);
            }
        }

        $whereSql = $whereBase ? 'WHERE ' . implode(' AND ', $whereBase) : '';

        $eduFacet = [];
        if ($useEdu) {
            $facetWhereSql = $whereForFacets ? ('WHERE ' . implode(' AND ', $whereForFacets)) : '';
            $sqlEduFacet = "
                    SELECT f.edu_stage AS s, f.edu_level AS l, COUNT(*) AS c
                    FROM files f
                    $facetWhereSql
                    GROUP BY f.edu_stage, f.edu_level
                    ORDER BY (f.edu_stage IS NULL OR f.edu_level IS NULL) ASC, f.edu_stage ASC, f.edu_level ASC
                ";
            $facetRes = db_query($conn, $sqlEduFacet, $typesForFacets, $paramsForFacets);
            if ($facetRes) {
                while ($r = $facetRes->fetch_assoc()) {
                    $sVal = $r['s'] ?? null;
                    $lVal = $r['l'] ?? null;
                    $k = ($sVal === null || $lVal === null) ? 'none' : ($sVal . '-' . (int)$lVal);
                    $eduFacet[$k] = (int)($r['c'] ?? 0);
                }
            }
        }

        $sqlCount = "SELECT COUNT(*) AS c FROM files f $whereSql";
        $countRes = db_query($conn, $sqlCount, $typesBase, $paramsBase);
        if ($countRes && $countRes->num_rows) {
            $fileTotal = (int)($countRes->fetch_assoc()['c'] ?? 0);
        }

        $didYouMean = [];
        if ($q !== '' && $fileTotal < 3) {
            $qNorm = strip_accents($q);

            $titleWhere = [];
            if ($hasIsPublic) { $titleWhere[] = 'f.is_public = 1'; }
            $titleWhereSql = $titleWhere ? ('WHERE ' . implode(' AND ', $titleWhere)) : '';
            $candRes = $conn->query("SELECT f.name FROM files f $titleWhereSql ORDER BY f.id DESC LIMIT 250");
            $best = [];
            if ($candRes) {
                while ($row = $candRes->fetch_assoc()) {
                    $name = (string)($row['name'] ?? '');
                    $nNorm = strip_accents($name);
                    $d = levenshtein($qNorm, $nNorm);
                    $best[] = ['d'=>$d, 'v'=>$name];
                }
            }
            usort($best, fn($a,$b)=> $a['d'] <=> $b['d']);
            foreach (array_slice($best, 0, 5) as $b) {
                if ($b['v'] !== '' && strip_accents($b['v']) !== $qNorm) $didYouMean[] = $b['v'];
            }

            $tagCands = [];
            $tagRes = $conn->query("SELECT f.tags FROM files f WHERE f.tags IS NOT NULL AND f.tags <> '' LIMIT 250");
            if ($tagRes) {
                while ($row = $tagRes->fetch_assoc()) {
                    $rawTags = (string)($row['tags'] ?? '');
                    $parts = preg_split('/[,;]+/u', $rawTags) ?: [];
                    foreach ($parts as $t) {
                        $t = trim($t);
                        if ($t === '') continue;
                        $tagCands[$t] = true;
                    }
                }
            }
            if ($tagCands) {
                $bestTags = [];
                foreach (array_keys($tagCands) as $t) {
                    $d = levenshtein($qNorm, strip_accents($t));
                    $bestTags[] = ['d'=>$d, 'v'=>$t];
                }
                usort($bestTags, fn($a,$b)=> $a['d'] <=> $b['d']);
                foreach (array_slice($bestTags, 0, 5) as $b) {
                    if ($b['v'] !== '' && !in_array($b['v'], $didYouMean, true)) $didYouMean[] = $b['v'];
                }
            }

            $didYouMean = array_values(array_unique(array_slice($didYouMean, 0, 5)));
        }

        $scoreSql = '0 AS score';
        $debugSql = '';
        if ($q !== '') {
            $scoreSql = "(
                    (CASE WHEN f.name LIKE CONCAT('%', ?, '%') THEN 30 ELSE 0 END) +
                    (CASE WHEN f.description LIKE CONCAT('%', ?, '%') THEN 10 ELSE 0 END) +
                    (CASE WHEN f.subject LIKE CONCAT('%', ?, '%') THEN 6 ELSE 0 END)
                ) AS score";
            $debugSql = ",
                    (CASE WHEN f.name LIKE CONCAT('%', ?, '%') THEN 30 ELSE 0 END) AS s_title,
                    (CASE WHEN f.description LIKE CONCAT('%', ?, '%') THEN 10 ELSE 0 END) AS s_desc,
                    (CASE WHEN f.subject LIKE CONCAT('%', ?, '%') THEN 6 ELSE 0 END) AS s_subject
                ";
        }

        $orderSql = 'ORDER BY f.id DESC';
        if ($sort === 'relevance' && $q !== '') {
            $orderSql = 'ORDER BY score DESC, f.id DESC';
        } elseif ($sort === 'oldest') {
            $orderSql = 'ORDER BY f.id ASC';
        } elseif ($sort === 'downloads' && $hasDownloads) {
            $orderSql = 'ORDER BY COALESCE(f.download_count, f.downloads, f.downloaded, 0) DESC, f.id DESC';
        } elseif ($sort === 'rating') {
            $orderSql = 'ORDER BY avg_rating DESC, rating_count DESC, f.id DESC';
        }

        if ($useEdu) {
            if ($sort === 'rating') {
                $orderSql = 'ORDER BY (f.edu_stage IS NULL OR f.edu_level IS NULL) ASC, f.edu_stage ASC, f.edu_level ASC, avg_rating DESC, rating_count DESC, f.id DESC';
            } elseif ($sort === 'relevance' && $q !== '') {
                $orderSql = 'ORDER BY (f.edu_stage IS NULL OR f.edu_level IS NULL) ASC, f.edu_stage ASC, f.edu_level ASC, score DESC, f.id DESC';
            } elseif ($sort === 'oldest') {
                $orderSql = 'ORDER BY (f.edu_stage IS NULL OR f.edu_level IS NULL) ASC, f.edu_stage ASC, f.edu_level ASC, f.id ASC';
            } else {
                $orderSql = 'ORDER BY (f.edu_stage IS NULL OR f.edu_level IS NULL) ASC, f.edu_stage ASC, f.edu_level ASC, f.id DESC';
            }
        }

        $offset = ($page - 1) * $perPage;
        $useKeyset = false;
        $cursorId = null;

        if ($cursor !== null && $cursor !== '' && ctype_digit((string)$cursor) && in_array($sort, ['newest','oldest'], true)) {
            $useKeyset = true;
            $cursorId = (int)$cursor;
        }

        $pagingWhereSql = $whereSql;
        $typesPaged = $typesBase;
        $paramsPaged = $paramsBase;

        if ($useKeyset) {
            if ($sort === 'newest') {
                $pagingWhereSql = ($pagingWhereSql ? ($pagingWhereSql . ' AND ') : 'WHERE ') . 'f.id < ?';
                $typesPaged .= 'i';
                $paramsPaged[] = $cursorId;
                $orderSql = 'ORDER BY f.id DESC';
            } else {
                $pagingWhereSql = ($pagingWhereSql ? ($pagingWhereSql . ' AND ') : 'WHERE ') . 'f.id > ?';
                $typesPaged .= 'i';
                $paramsPaged[] = $cursorId;
                $orderSql = 'ORDER BY f.id ASC';
            }
        }

        if ($sort === 'rating') {
            $sqlFiles = "SELECT f.*, $scoreSql $debugSql, IFNULL(AVG(r.rating), 0) AS avg_rating, COUNT(r.id) AS rating_count FROM files f LEFT JOIN ratings r ON r.file_id = f.id $pagingWhereSql GROUP BY f.id $orderSql LIMIT ?";
            $typesPaged .= 'i';
            $paramsPaged[] = $perPage;
        } else {
            $sqlFiles = "SELECT f.*, $scoreSql $debugSql FROM files f $pagingWhereSql $orderSql LIMIT ?";
            $typesPaged .= 'i';
            $paramsPaged[] = $perPage;
        }

        if ($q !== '') {
            $typesPaged .= 'sss';
            array_push($paramsPaged, $q, $q, $q);
            if ($debugSql !== '') {
                $typesPaged .= 'sss';
                array_push($paramsPaged, $q, $q, $q);
            }
        }

        if (!$useKeyset) {
            $sqlFiles .= " OFFSET ?";
            $typesPaged .= 'i';
            $paramsPaged[] = $offset;
        }

        $fileResultQ = db_query($conn, $sqlFiles, $typesPaged, $paramsPaged);

        $fileResult = null;
        $lastCursor = null;
        if ($fileResultQ) {
            $ids = [];
            $buffered = [];
            while ($row = $fileResultQ->fetch_assoc()) {
                $buffered[] = $row;
                $ids[] = (int)$row['id'];
                $lastCursor = (int)$row['id'];
            }
            $_SESSION['search_ids'] = $ids;
            $_SESSION['search_params'] = $_GET;

            $fileResult = $buffered;
        }

        $GLOBALS['__search_tokens'] = $needlesForUi;
        $GLOBALS['__search_eduFacet'] = $eduFacet;
        $GLOBALS['__search_didYouMean'] = $didYouMean;
        $GLOBALS['__search_keyset'] = $useKeyset;
        $GLOBALS['__search_lastCursor'] = $lastCursor;
    }

    require "assets/php/lang.php";
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-iconre" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="content-wrapper w-full">
    <?php include "assets/php/ads.php"; ?>
    <div class="main w-full max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6">
        <section class="card search-panel mb-6 md:mb-8 p-4 md:p-6">
            <form method="GET" class="search-form-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <input type="hidden" name="page" value="1">
                <div class="form-field md:col-span-2 lg:col-span-4">
                    <label for="q" class="text-sm md:text-base mb-2 block"><?= t('search_placeholder') ?? 'Keresés...' ?></label>
                    <div class="search-input-wrapper flex gap-2">
                        <input type="text" name="q" id="q" value="<?= htmlspecialchars($q) ?>" class="input flex-1 text-sm md:text-base" placeholder="Írd be mit keresel...">
                        <button type="submit" class="btn-cta flex-shrink-0">
                            <svg class="icon icon-search w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="form-field">
                    <label class="text-sm md:text-base mb-2 block"><?= t('label_scope') ?? 'Hol keressünk?' ?></label>
                    <select name="scope" class="select w-full text-sm md:text-base">
                        <option value="all" <?= $scope === 'all' ? 'selected' : '' ?>>Mindenhol</option>
                        <option value="files" <?= $scope === 'files' ? 'selected' : '' ?>>Csak fájlok</option>
                        <option value="users" <?= $scope === 'users' ? 'selected' : '' ?>>Csak felhasználók</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="text-sm md:text-base mb-2 block"><?= t('label_type') ?? 'Fájltípus' ?></label>
                    <select name="type" class="select w-full text-sm md:text-base">
                        <option value="all" <?= $type === 'all' ? 'selected' : '' ?>>Összes típus</option>
                        <option value="pdf" <?= $type === 'pdf' ? 'selected' : '' ?>>PDF</option>
                        <option value="mp4" <?= $type === 'mp4' ? 'selected' : '' ?>>Videó (MP4)</option>
                        <option value="docx" <?= $type === 'docx' ? 'selected' : '' ?>>Word (DOCX)</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="text-sm md:text-base mb-2 block">Szint</label>
                    <select name="level" class="select w-full text-sm md:text-base">
                        <option value="all"  <?= ($levelRaw === 'all' || $levelRaw === '' || $levelRaw === null) ? 'selected' : '' ?>>Összes</option>
                        <option value="none" <?= ($levelRaw === 'none') ? 'selected' : '' ?>>Nincs megadva</option>
                        <optgroup label="Technikum (9-13)">
                            <?php for ($y = 9; $y <= 13; $y++): $v = "hs-$y"; ?>
                                <option value="<?= $v ?>" <?= ((string)$levelRaw === (string)$v) ? 'selected' : '' ?>><?= $y ?>. évfolyam</option>
                            <?php endfor; ?>
                        </optgroup>
                        <optgroup label="Egyetem (1-7. félév)">
                            <?php for ($sm = 1; $sm <= 7; $sm++): $v = "uni-$sm"; ?>
                                <option value="<?= $v ?>" <?= ((string)$levelRaw === (string)$v) ? 'selected' : '' ?>><?= $sm ?>. félév</option>
                            <?php endfor; ?>
                        </optgroup>
                    </select>
                </div>
                <div class="form-field">
                    <label class="text-sm md:text-base mb-2 block">Tag</label>
                    <input type="text" name="tag" value="<?= htmlspecialchars($tagRaw) ?>" class="input w-full text-sm md:text-base" placeholder="pl. Tankönyv">
                </div>
                <div class="form-field">
                    <label class="text-sm md:text-base mb-2 block">Keresési mód</label>
                    <select name="mode" class="select w-full text-sm md:text-base">
                        <option value="all" <?= $mode === 'all' ? 'selected' : '' ?>>Minden szó (AND)</option>
                        <option value="any" <?= $mode === 'any' ? 'selected' : '' ?>>Bármely szó (OR)</option>
                    </select>
                </div>
                <div class="form-field md:col-span-2 lg:col-span-1">
                    <label class="text-sm md:text-base mb-2 block"><?= t('label_sort') ?? 'Rendezés' ?></label>
                    <select name="sort" class="select w-full text-sm md:text-base">
                        <option value="relevance" <?= $sort === 'relevance' ? 'selected' : '' ?>>Relevancia</option>
                        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Legújabb elöl</option>
                        <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Legrégebbi elöl</option>
                        <option value="downloads" <?= $sort === 'downloads' ? 'selected' : '' ?>>Legtöbb letöltés</option>
                        <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Legjobb értékelés</option>
                    </select>
                    <?php if ($sort === 'downloads' && !$hasDownloads): ?>
                        <p class="text-xs opacity-70">(Nincs letöltésszámláló oszlop a <code>files</code>-ban)</p>
                    <?php endif; ?>
                </div>
            </form>
            <?php
                $eduFacet = $GLOBALS['__search_eduFacet'] ?? [];
                $facetBuildUrl = function($levelVal) {
                    $p = $_GET;
                    $p['page'] = 1;
                    $p['level'] = $levelVal;
                    return 'search.php?' . http_build_query($p);
                };
            ?>
            <?php if (!empty($eduFacet) && $useEdu): ?>
                <div class="mb-6 -mt-2 flex flex-wrap gap-2 items-center">
                    <span class="text-sm opacity-70 mr-2">Szint:</span>
                    <?php
                        $allCnt = array_sum($eduFacet);
                        $isAllActive = ($levelRaw === 'all' || $levelRaw === '' || $levelRaw === null);
                    ?>
                    <a href="<?= htmlspecialchars($facetBuildUrl('all')) ?>"
                       class="px-3 py-1 rounded-full border text-sm transition-colors <?= $isAllActive ? 'bg-sky-400/20 border-sky-400/40 text-sky-200' : 'bg-white/5 hover:bg-white/10 border-white/10' ?>">
                        Összes (<?= (int)$allCnt ?>)
                    </a>
                    <?php
                        $noneCnt = (int)($eduFacet['none'] ?? 0);
                        $noneActive = ($levelRaw === 'none');
                    ?>
                    <a href="<?= htmlspecialchars($facetBuildUrl('none')) ?>"
                       class="px-3 py-1 rounded-full border text-sm transition-colors <?= $noneActive ? 'bg-sky-400/20 border-sky-400/40 text-sky-200' : 'bg-white/5 hover:bg-white/10 border-white/10' ?>">
                        Nincs megadva (<?= $noneCnt ?>)
                    </a>
                    <?php for ($y = 9; $y <= 13; $y++):
                        $k = "hs-$y";
                        $cnt = (int)($eduFacet[$k] ?? 0);
                        $active = ((string)$levelRaw === (string)$k);
                        $disabled = ($cnt === 0);
                        ?>
                        <a href="<?= htmlspecialchars($facetBuildUrl($k)) ?>"
                           class="px-3 py-1 rounded-full border text-sm transition-colors <?= $active ? 'bg-sky-400/20 border-sky-400/40 text-sky-200' : ($disabled ? 'opacity-40 pointer-events-none bg-white/5 border-white/10' : 'bg-white/5 hover:bg-white/10 border-white/10') ?>">
                            <?= $y ?>. évf. (<?= $cnt ?>)
                        </a>
                    <?php endfor; ?>

                    <?php for ($sm = 1; $sm <= 7; $sm++):
                        $k = "uni-$sm";
                        $cnt = (int)($eduFacet[$k] ?? 0);
                        $active = ((string)$levelRaw === (string)$k);
                        $disabled = ($cnt === 0);
                        ?>
                        <a href="<?= htmlspecialchars($facetBuildUrl($k)) ?>"
                           class="px-3 py-1 rounded-full border text-sm transition-colors <?= $active ? 'bg-sky-400/20 border-sky-400/40 text-sky-200' : ($disabled ? 'opacity-40 pointer-events-none bg-white/5 border-white/10' : 'bg-white/5 hover:bg-white/10 border-white/10') ?>">
                            <?= $sm ?>. félév (<?= $cnt ?>)
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </section>
        <?php if ($userResult && $userResult->num_rows > 0): ?>
            <h3 class="text-xl md:text-2xl mb-4"><?= t('result_users') ?></h3>
            <div class="list-compact flex flex-col gap-3 mb-6">
                <?php while ($u = $userResult->fetch_assoc()):
                    $usernameRaw = $u['username'];
                    $username = htmlspecialchars($usernameRaw);
                    $fullname = htmlspecialchars(trim(($u['lastname'] ?? '').' '.($u['firstname'] ?? '')));
                    $pfp = "assets/img/default_profile_picture.jpg";
                    if (!empty($u['profile_picture'])) {
                        $fs = __DIR__ . "/users/$usernameRaw/{$u['profile_picture']}";
                        if (file_exists($fs)) {
                            $pfp = "users/$usernameRaw/{$u['profile_picture']}";
                        }
                    }
                    ?>
                    <article class="mini-card flex items-center gap-3 p-3 rounded-lg bg-white/5 border border-white/10">
                        <img src="<?= htmlspecialchars($pfp) ?>" alt="" class="w-12 h-12 md:w-14 md:h-14 rounded-full object-cover border-2 border-white/10 flex-shrink-0">
                        <div class="mini-main flex-1 min-w-0">
                            <h4 class="mini-title text-base md:text-lg font-semibold truncate"><?= $fullname ?: '@'.$username ?></h4>
                            <p class="mini-meta text-sm md:text-base opacity-75 truncate">@<?= $username ?></p>
                        </div>
                        <a href="profile.php?username=<?= urlencode($usernameRaw) ?>"
                           class="btn-ghost text-sm md:text-base flex-shrink-0"
                           title="<?= t('open_profile') ?>">
                            <?= t('open_profile') ?>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
        <?php if (is_array($fileResult) && count($fileResult) > 0): ?>
            <div class="flex flex-wrap items-end justify-between gap-3 mt-6 md:mt-8 mb-4">
                <h3 class="text-xl md:text-2xl"><?= t('result_files') ?></h3>
                <?php
                    $showFrom = ($page - 1) * $perPage + 1;
                    $showTo   = min($fileTotal, $page * $perPage);
                    $needlesForUi = $GLOBALS['__search_tokens'] ?? [];
                ?>
                <div class="text-sm md:text-base opacity-80">
                    Mutatom: <strong><?= (int)$showFrom ?>-<?= (int)$showTo ?></strong> / <?= (int)$fileTotal ?>
                </div>
            </div>
            <?php
                $currentEduHeader = '__init__';
                $renderEduHeader = function($s, $l) {
                    if ($s === null || $l === null || $s === '' || $l === '') return 'Nincs megadva';
                    $l = (int)$l;
                    if ($s === 'hs') return "Technikum - {$l}. évfolyam";
                    if ($s === 'uni') return "Egyetem - {$l}. félév";
                    return 'Nincs megadva';
                };
            ?>
            <div class="content-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <?php foreach ($fileResult as $f):
                    $uploaderQ = db_query($conn, "SELECT username FROM users WHERE id = ?", "i", [(int)($f['uploaded_by'] ?? 0)]);
                    $uploader  = $uploaderQ && $uploaderQ->num_rows ? $uploaderQ->fetch_assoc()['username'] : null;
                    $stVal = ($useEdu ? ($f['edu_stage'] ?? null) : null);
                    $lvVal = ($useEdu ? ($f['edu_level'] ?? null) : null);
                    $eduKey = ($useEdu ? (($stVal === null || $lvVal === null) ? '__null__' : ((string)$stVal . '-' . (string)$lvVal)) : '__nogroup__');
                    if ($useEdu && $eduKey !== $currentEduHeader):
                        $currentEduHeader = $eduKey;
                        ?>
                        <div class="md:col-span-2 lg:col-span-3">
                            <div class="sticky top-0 z-10 mb-3 rounded-xl bg-white/5 backdrop-blur border border-white/10 px-4 py-2">
                                <h4 class="text-base md:text-lg font-semibold">
                                    <?= htmlspecialchars($renderEduHeader($stVal, $lvVal)) ?>
                                </h4>
                            </div>
                        </div>
                    <?php endif; ?>
                    <article class="card p-4 break-words flex flex-col">
                        <h4 class="entry-title text-lg md:text-xl mb-2 truncate">
                            <?= highlight_many_html($f['name'] ?? '', $needlesForUi) ?>
                        </h4>
                        <?php if (!empty($f['description'])):
                            $snippet = build_snippet((string)$f['description'], $needlesForUi, 90);
                            ?>
                            <p class="text-sm md:text-base opacity-80 mb-3 line-clamp-3">
                                <?= highlight_many_html($snippet, $needlesForUi) ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($uploader): ?>
                            <p class="entry-meta text-sm md:text-base mb-4">
                                <?= t('label_uploaded_by') ?>
                                <a class="uploader-name" href="profile.php?username=<?= urlencode($uploader) ?>">
                                    @<?= htmlspecialchars($uploader) ?>
                                </a>
                            </p>
                        <?php endif; ?>

                        <div class="mt-auto flex flex-wrap gap-2 items-center">
                            <?php if ($sort === 'rating' && isset($f['avg_rating'])): ?>
                                <span class="text-sm opacity-80">⭐ <?= number_format((float)$f['avg_rating'], 2) ?> (<?= (int)($f['rating_count'] ?? 0) ?>)</span>
                            <?php elseif ($sort === 'relevance' && isset($f['score']) && $q !== ''): ?>
                                <?php if (!empty($debug) && isset($f['s_title'])): ?>
                                    <span class="text-sm opacity-80">
                                        score=<?= (int)$f['score'] ?> (title=<?= (int)($f['s_title'] ?? 0) ?>, desc=<?= (int)($f['s_desc'] ?? 0) ?>, subj=<?= (int)($f['s_subject'] ?? 0) ?>)
                                    </span>
                                <?php else: ?>
                                    <span class="text-sm opacity-80">Pont: <?= (int)$f['score'] ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <a class="note-link text-sm md:text-base ml-auto" href="note.php?id=<?= (int)$f['id'] ?>">
                                <?= t('btn_details') ?>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <?php
                $useKeyset = $GLOBALS['__search_keyset'] ?? false;
                $lastCursor = $GLOBALS['__search_lastCursor'] ?? null;

                $totalPages = (int)max(1, ceil($fileTotal / max(1, $perPage)));
                $buildUrl = function(int $targetPage) {
                    $params = $_GET;
                    $params['page'] = $targetPage;
                    unset($params['cursor']);
                    return 'search.php?' . http_build_query($params);
                };

                $buildCursorUrl = function($cursorVal) {
                    $params = $_GET;
                    $params['page'] = 1;
                    $params['cursor'] = $cursorVal;
                    return 'search.php?' . http_build_query($params);
                };
            ?>
            <?php if ($useKeyset && $lastCursor !== null): ?>
                <div class="flex items-center justify-center gap-3 mt-8">
                    <a class="btn-sm btn-ghost" href="<?= htmlspecialchars($buildCursorUrl((int)$lastCursor)) ?>">Továbbiak →</a>
                    <a class="btn-sm btn-ghost opacity-70" href="<?= htmlspecialchars($buildUrl(1)) ?>">Vissza lapozáshoz</a>
                </div>
            <?php elseif ($totalPages > 1): ?>
                <div class="flex items-center justify-center gap-3 mt-8">
                    <a class="btn-sm btn-ghost <?= $page <= 1 ? 'opacity-50 pointer-events-none' : '' ?>" href="<?= htmlspecialchars($buildUrl(max(1, $page - 1))) ?>">← Előző</a>
                    <span class="text-sm opacity-80">Oldal <?= (int)$page ?> / <?= (int)$totalPages ?></span>
                    <a class="btn-sm btn-ghost <?= $page >= $totalPages ? 'opacity-50 pointer-events-none' : '' ?>" href="<?= htmlspecialchars($buildUrl(min($totalPages, $page + 1))) ?>">Következő →</a>
                </div>
            <?php endif; ?>
        <?php elseif ($scope !== 'users'): ?>
            <div class="mt-6 md:mt-8 card p-4 md:p-6 opacity-90">
                <h3 class="text-xl md:text-2xl mb-2">Nincs találat</h3>
                <?php
                $didYouMean = $GLOBALS['__search_didYouMean'] ?? [];
                $qNorm = $q !== '' ? strip_accents($q) : '';
                $suggestLinks = [];
                foreach ($didYouMean as $sug) {
                    $p = $_GET;
                    $p['q'] = $sug;
                    $p['page'] = 1;
                    $suggestLinks[] = ['label'=>$sug, 'url'=>'search.php?' . http_build_query($p)];
                }
                ?>
                <?php if ($q !== '' && $qNorm !== '' && $qNorm !== mb_strtolower($q, 'UTF-8')): ?>
                    <p class="mb-3 opacity-80">Tipp: próbáld ékezetek nélkül: <a class="underline" href="<?= htmlspecialchars('search.php?' . http_build_query(array_merge($_GET, ['q'=>$qNorm,'page'=>1]))) ?>"><?= htmlspecialchars($qNorm) ?></a></p>
                <?php endif; ?>
                <?php if (!empty($suggestLinks)): ?>
                    <div class="mb-3">
                        <p class="opacity-80 mb-2">Erre gondoltál?</p>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($suggestLinks as $sl): ?>
                                <a class="px-3 py-1 rounded-full bg-white/5 hover:bg-white/10 border border-white/10 text-sm" href="<?= htmlspecialchars($sl['url']) ?>">
                                    <?= htmlspecialchars($sl['label']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <ul class="list-disc pl-5 opacity-80">
                    <li>Próbáld meg rövidebb kulcsszóval.</li>
                    <li>Töröld a szűrőket (szint / tag / típus), és nézd meg úgy.</li>
                    <li>Ha csak böngésznél, hagyd üresen a keresést.</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>