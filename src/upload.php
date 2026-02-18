<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";
    require_once "assets/php/premium.php";

    if (!isset($_COOKIE['id']) || !ctype_digit($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    if (($_GET['profanity'] ?? '') === '1') {
        echo "<script>alert('Ne használj trágár szavakat jegyzet feltöltésnél!')</script>";
    }

    $userid = (int)$_COOKIE['id'];

    $premium_van = user_premium($conn, $userid);

    $found_user = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$userid]);
    $user = ($found_user && $found_user->num_rows > 0) ? $found_user->fetch_assoc() : null;

    if (!$user) {
        header("Location: reglog.php");
        exit;
    }

    $hasEduStage = false;
    $hasEduLevel = false;
    $hasIsPublic = false;
    $hasIsPrivate = false;
    $hasContentType = false;
    $hasNoteMarkdown = false;
    $hasNoteExcerpt = false;


    $cols = $conn->query("SHOW COLUMNS FROM files");
    if ($cols) {
        while ($c = $cols->fetch_assoc()) {
            $f = strtolower($c['Field'] ?? '');
            if ($f === 'edu_stage')  $hasEduStage = true;
            if ($f === 'edu_level')  $hasEduLevel = true;
            if ($f === 'is_public')  $hasIsPublic = true;
            if ($f === 'is_private') $hasIsPrivate = true;
            if ($f === 'content_type')   $hasContentType = true;
            if ($f === 'note_markdown')  $hasNoteMarkdown = true;
            if ($f === 'note_excerpt')   $hasNoteExcerpt = true;
        }
    }


    $MAX_USER_TOTAL = 60 * 1024 * 1024; // 60 MB / user

    $MAX_FILE_SIZE = $premium_van
        ? 60 * 1024 * 1024   // prémium: 60MB
        : 5  * 1024 * 1024;  // free: 5MB

    $uploadError = '';

    if (isset($_POST['upload-btn'])) {

        $mode = (string)($_POST['content_mode'] ?? 'file'); // 'file' | 'markdown'
        if (!in_array($mode, ['file', 'markdown'], true)) $mode = 'file';

        $lekerdezes = "SELECT * FROM profanity_filter";
        $talalt_sorok = $conn->query($lekerdezes);
        if ($talalt_sorok) {
            while ($sor = $talalt_sorok->fetch_assoc()) {
                $badword = (string)($sor['words'] ?? '');
                if ($badword === '') continue;

                $hay1 = (string)($_POST['name'] ?? '');
                $hay2 = (string)($_POST['description'] ?? '');
                $hay3 = ($mode === 'markdown') ? (string)($_POST['markdown_note'] ?? '') : '';

                if (
                    stripos($hay1, $badword) !== false ||
                    stripos($hay2, $badword) !== false ||
                    ($hay3 !== '' && stripos($hay3, $badword) !== false)
                ) {
                    header("Location: upload.php?profanity=1");
                    exit;
                }
            }
        }

        $displayName = trim((string)($_POST['name'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $subject     = trim((string)($_POST['subject'] ?? ''));
        $tags        = trim((string)($_POST['applied_tags'] ?? ''));

        $is_private = 0;
        if ($hasIsPrivate) {
            $is_private = isset($_POST['is_private']) ? 1 : 0;
            if ($is_private === 1 && !$premium_van) {
                $uploadError = 'A privát jegyzet csak prémium előfizetőknek elérhető.';
            }
        }

        $edu_stage = null;
        $edu_level = null;
        if ($hasEduStage && $hasEduLevel) {
            $levelRaw = (string)($_POST['level'] ?? 'none');
            if ($levelRaw === 'none' || $levelRaw === '' || $levelRaw === 'all') {
                $edu_stage = null;
                $edu_level = null;
            } elseif (preg_match('/^(hs|uni)-(\d+)$/', $levelRaw, $m)) {
                $edu_stage = $m[1];
                $edu_level = (int)$m[2];
                if ($edu_stage === 'hs'  && ($edu_level < 9 || $edu_level > 13)) { $edu_stage = null; $edu_level = null; }
                if ($edu_stage === 'uni' && ($edu_level < 1 || $edu_level > 7))  { $edu_stage = null; $edu_level = null; }
            }
        }

        $is_public = 1;
        if ($hasIsPublic) {
            $is_public = isset($_POST['is_public']) ? 1 : 0;
        }

        if ($uploadError === '' && ($displayName === '' || $description === '' || $subject === '')) {
            $uploadError = 'Kérlek tölts ki minden kötelező mezőt (név, leírás, tárgy).';
        }

        if ($uploadError === '') {

            $folder = getcwd();
            $dir    = $folder . "/users/" . $user['username'] . "/";
            if (!is_dir($dir)) mkdir($dir, 0777, true);

            $base = preg_replace('/[^a-zA-Z0-9._-]+/', '_', $displayName);
            $base = trim($base, '_');
            if ($base === '') $base = 'jegyzet';
            $base = substr($base, 0, 60);

            $file_name = '';
            $targetPath = '';
            $file_size = 0;

        if ($mode === 'markdown') {

            if (!$hasContentType || !$hasNoteMarkdown || !$hasNoteExcerpt) {
                $uploadError = "A jegyzet feltöltéshez hiányzik az adatbázis frissítés (content_type / note_markdown / note_excerpt).";
            } else {
                $md = (string)($_POST['markdown_note'] ?? '');
                $md = str_replace("\r\n", "\n", $md);

                if (trim($md) === '') {
                    $uploadError = 'A Markdown jegyzet nem lehet üres.';
                } else {
                    $MAX_MD_SIZE = 2 * 1024 * 1024; // 2MB
                    $file_size = strlen($md); // byte

                    if ($file_size > $MAX_MD_SIZE) {
                        $uploadError = 'A Markdown jegyzet maximum 2MB lehet.';
                    } else {
                        $sumRes = db_query($conn, "SELECT COALESCE(SUM(file_size), 0) AS used_bytes FROM files WHERE uploaded_by = ?", "i", [$user['id']]);
                        $sumRow   = $sumRes ? $sumRes->fetch_assoc() : ['used_bytes' => 0];
                        $usedNow  = (int)($sumRow['used_bytes'] ?? 0);
                        $afterNew = $usedNow + $file_size;

                        if ($afterNew > $MAX_USER_TOTAL) {
                            $maxMb = round($MAX_USER_TOTAL / 1024 / 1024);
                            $usedMb = round($usedNow / 1024 / 1024);
                            $mdMb = round($file_size / 1024 / 1024, 2);
                            $uploadError = "Nincs elég hely a kvótádban. Max {$maxMb} MB. Jelenleg ~{$usedMb} MB, a jegyzet ~{$mdMb} MB.";
                        } else {
                            $excerpt = trim(preg_replace('/\s+/', ' ', $md));
                            $excerpt = mb_substr($excerpt, 0, 255, 'UTF-8');

                            $colsIns = ['uploaded_by', 'name', 'file_name', 'description', 'file_path', 'subject', 'tags', 'file_size', 'content_type', 'note_markdown', 'note_excerpt'];
                            $vals = [$user['id'], $displayName, null, $description, null, $subject, $tags, $file_size, 'note', $md, $excerpt];
                            $types= "issssssi" . "sss";

                            if ($hasIsPrivate) {
                                $colsIns[] = 'is_private';
                                $vals[] = $is_private;
                                $types .= 'i';
                            }
                            if ($hasIsPublic) {
                                $colsIns[] = 'is_public';
                                $vals[] = $is_public;
                                $types .= 'i';
                            }
                            if ($hasEduStage && $hasEduLevel) {
                                $colsIns[] = 'edu_stage';
                                $colsIns[] = 'edu_level';
                                $vals[] = $edu_stage;
                                $vals[] = $edu_level;
                                $types .= 'si';
                            }

                            $placeholders = implode(',', array_fill(0, count($colsIns), '?'));
                            $sql = "INSERT INTO files (" . implode(',', $colsIns) . ") VALUES ($placeholders)";
                            db_stmt($conn, $sql, $types, $vals)->close();

                            header("Location: upload.php?ok=1");
                            exit;
                        }
                    }
                }
            }
        } else {
                if (!isset($_FILES['upload-file']) || ($_FILES['upload-file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                    $uploadError = 'Hiba a fájl feltöltésekor.';
                } else {
                    $file_name = (string)($_FILES['upload-file']['name'] ?? '');
                    $tmp_name  = (string)($_FILES['upload-file']['tmp_name'] ?? '');
                    $file_size = (int)($_FILES['upload-file']['size'] ?? 0);

                    if ($file_size <= 0) {
                        $uploadError = 'Üres fájl vagy ismeretlen fájlméret.';
                    } elseif ($file_size > $MAX_FILE_SIZE) {
                        $mb = round($MAX_FILE_SIZE / 1024 / 1024);
                        $uploadError = "Túl nagy a fájl. Maximum {$mb} MB-os fájlt tölthetsz fel.";
                    } else {
                        $sumRes = db_query($conn, "SELECT COALESCE(SUM(file_size), 0) AS used_bytes FROM files WHERE uploaded_by = ?", "i", [$user['id']]);
                        $sumRow   = $sumRes ? $sumRes->fetch_assoc() : ['used_bytes' => 0];
                        $usedNow  = (int)($sumRow['used_bytes'] ?? 0);
                        $afterNew = $usedNow + $file_size;

                        if ($afterNew > $MAX_USER_TOTAL) {
                            $maxMb = round($MAX_USER_TOTAL / 1024 / 1024);
                            $usedMb  = round($usedNow / 1024 / 1024);
                            $fileMb  = round($file_size / 1024 / 1024);
                            $uploadError = "Nincs elég hely a felhasználói kvótádban. Max {$maxMb} MB-ot használhatsz. Jelenleg ~{$usedMb} MB-ot használsz, a fájl mérete ~{$fileMb} MB.";
                        } else {
                            $file_type = @mime_content_type($tmp_name);
                            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                            $allowed_extensions = ['pdf', 'mp4', 'docx'];
                            $allowed_types = ['application/pdf','video/mp4','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

                            if (!in_array($file_ext, $allowed_extensions, true) || !in_array((string)$file_type, $allowed_types, true)) {
                                $uploadError = 'Ez a fájltípus nem engedélyezett. Csak PDF, MP4 és DOCX tölthető fel.';
                            } else {
                                $targetPath = $dir . $file_name;
                                if (file_exists($targetPath)) {
                                    $base2 = pathinfo($file_name, PATHINFO_FILENAME);
                                    $ext2  = pathinfo($file_name, PATHINFO_EXTENSION);
                                    $file_name = $base2 . '_' . time() . '.' . $ext2;
                                    $targetPath = $dir . $file_name;
                                }

                                if (!move_uploaded_file($tmp_name, $targetPath)) {
                                    if ($hasContentType) {
                                        $colsIns[] = 'content_type';
                                        $vals[] = 'file';
                                        $types .= 's';
                                    }
                                    $uploadError = 'A fájl mozgatása a célmappába nem sikerült.';
                                }
                            }
                        }
                    }
                }
            }

            if ($uploadError === '' && $targetPath !== '' && $file_name !== '') {
                $colsIns = ['uploaded_by', 'name', 'file_name', 'description', 'file_path', 'subject', 'tags', 'file_size'];
                $vals = [$user['id'], $displayName, $file_name, $description, $targetPath, $subject, $tags, $file_size];
                $types = "issssssi";

                if ($hasIsPrivate) { $colsIns[] = 'is_private'; $vals[] = $is_private; $types .= 'i'; }
                if ($hasIsPublic)  { $colsIns[] = 'is_public';  $vals[] = $is_public;  $types .= 'i'; }
                if ($hasEduStage && $hasEduLevel) {
                    $colsIns[] = 'edu_stage'; $colsIns[] = 'edu_level';
                    $vals[] = $edu_stage; $vals[] = $edu_level;
                    $types .= 'si';
                }

                $placeholders = implode(',', array_fill(0, count($colsIns), '?'));
                $sql = "INSERT INTO files (" . implode(',', $colsIns) . ") VALUES ($placeholders)";
                db_stmt($conn, $sql, $types, $vals)->close();

                header("Location: upload.php?ok=1");
                exit;
            }
        }
    }

    $notify_number = 0;
    $nf = $conn->query("SELECT id FROM notifys WHERE toid = " . (int)$user['id'] . " AND readed = 0");
    if ($nf) {
        $notify_number = (int)$nf->num_rows;
    }

    $levelOptions = [];
    if ($hasEduStage && $hasEduLevel) {
        $levelOptions[] = ['none', 'Nincs megadva'];
        for ($i = 9; $i <= 13; $i++) $levelOptions[] = ["hs-$i", "Technikum - $i. évfolyam"];
        for ($i = 1; $i <= 7; $i++) $levelOptions[] = ["uni-$i", "Egyetem - $i. félév"];
    }
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title>Feltöltés</title>
    <meta charset='UTF-8'>
    <meta name='description' content='Iskolai jegyzeteket megosztó oldal'>
    <meta name='keywords' content='iskola, jegyzet, megosztás, tanulás'>
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="content-wrapper w-full">
    <?php include "assets/php/ads.php"; ?>
    <div class="main w-full max-w-3xl mx-auto px-4 md:px-6 lg:px-8 py-6">
        <h1 class="text-2xl md:text-3xl lg:text-4xl mb-6">Anyag feltöltése</h1>
        <?php if (isset($_GET['ok'])): ?>
            <div class="toast toast-success">A fájl sikeresen feltöltve!</div>
        <?php endif; ?>
        <?php if ($uploadError !== ''): ?>
            <div class="toast toast-error"><?= htmlspecialchars($uploadError) ?></div>
        <?php endif; ?>
        <form class="card p-4 md:p-6 flex flex-col gap-4" method="post" enctype="multipart/form-data">
            <label for="name" class="text-sm md:text-base font-semibold">Anyag neve:</label>
            <input class="input w-full text-sm md:text-base" type="text" name="name" placeholder="pl. Fizika ZH anyag" required>
            <label for="description" class="text-sm md:text-base font-semibold">Leírás:</label>
            <textarea class="input w-full text-sm md:text-base" name="description" placeholder="Rövid leírás az anyagról..." rows="4" required></textarea>
            <label for="subject" class="text-sm md:text-base font-semibold">Tárgy:</label>
            <input class="input w-full text-sm md:text-base" type="text" name="subject" placeholder="pl. fizika, történelem" required>
            <?php if ($hasIsPrivate): ?>
                <label style="margin-top:6px;">
                    <input type="checkbox" name="is_private" value="1" <?= $premium_van ? '' : 'disabled' ?>>
                    Privát jegyzet (csak te látod) – prémium
                </label>
                <?php if (!$premium_van): ?>
                    <p class="entry-meta">A privát feltöltéshez prémium szükséges.</p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($hasEduStage && $hasEduLevel): ?>
                <label for="level" class="text-sm md:text-base font-semibold">Évfolyam / félév:</label>
                <select class="input w-full text-sm md:text-base" name="level" id="level">
                    <?php foreach ($levelOptions as $opt): ?>
                        <option value="<?= htmlspecialchars($opt[0]) ?>"><?= htmlspecialchars($opt[1]) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <?php if ($hasIsPublic): ?>
                <label class="checkbox-fancy" style="margin-top:6px;">
                    <input type="checkbox" name="is_public" checked>
                    <span class="checkbox-box"></span>
                    <span>Nyilvános (megjelenjen a keresőben)</span>
                </label>
            <?php endif; ?>
            <label for="tag" class="text-sm md:text-base font-semibold">Címkék:</label>
            <textarea class="input w-full text-sm md:text-base" id="tag" name="applied_tags" placeholder="Címkék..." rows="3" readonly></textarea>
            <?php include 'assets/php/kereso_tag.php'; ?>
            <label class="text-sm md:text-base font-semibold">
                Feltöltés típusa:
            </label>
            <div class="flex gap-4 items-center">
                <label class="flex gap-2 items-center">
                    <input type="radio" name="content_mode" value="file" checked>
                    <span>Fájl feltöltése</span>
                </label>
                <label class="flex gap-2 items-center">
                    <input type="radio" name="content_mode" value="markdown">
                    <span>Markdown jegyzet írása</span>
                </label>
            </div>
            <div id="file_wrap">
                <label for="upload-file" class="text-sm md:text-base font-semibold">Fájl kiválasztása:</label>
                <div class="file-input-wrapper w-full">
                    <input class="input w-full text-sm md:text-base" type="file" name="upload-file">
                    <p class="entry-meta" style="margin-top:6px;">
                        Engedélyezett: PDF, MP4, DOCX • Max: <?= $premium_van ? '60MB' : '5MB' ?>
                    </p>
                </div>
            </div>
            <div id="markdown_wrap" style="display:none;">
                <label for="markdown_note" class="text-sm md:text-base font-semibold">Markdown jegyzet:</label>
                <textarea class="input w-full text-sm md:text-base" name="markdown_note" id="markdown_note" rows="10" placeholder="# Cím&#10;&#10;- Lista&#10;- **Félkövér**&#10;- ```kód```&#10;&#10;Ide írd a jegyzetet..."></textarea>
                <p class="entry-meta" style="margin-top:6px;">
                    Tipp: Markdown szintaxis (#, **félkövér**, - lista, `kód` stb.). Max 2MB.
                </p>
            </div>
            <button type="submit" name="upload-btn" class="btn-cta w-full md:w-auto text-sm md:text-base mt-2">Feltöltés</button>
        </form>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>

