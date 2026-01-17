<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once 'assets/php/functions.php';

    if (!isset($_COOKIE['id']) || !ctype_digit($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $userid = (int)$_COOKIE['id'];

    $found_user = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$userid]);
    $user = $found_user->fetch_assoc();

    if (!$user) {
        header("Location: reglog.php");
        exit;
    }

    // LIMITEK
    $MAX_USER_TOTAL = 60 * 1024 * 1024; // 60 MB / user
    $MAX_FILE_SIZE  = 60 * 1024 * 1024; // ha per-fájl is akarsz limitet

    // (!!!) norbi: visszaállítottam a régi feltöltési logikát (!!!)
    // ezt viszont csináljuk meg normálisan, egyelőre maradhat igy mert nem tudok dolgozni e nélkül...
    if (isset($_POST['upload-btn'])) {

        if (!isset($_FILES['upload-file']) || $_FILES['upload-file']['error'] !== UPLOAD_ERR_OK) {
            echo "<script>alert('Hiba a fájl feltöltésekor.');</script>";
        } else {
            $subject = $_POST['subject'] ?? '';
            $tags = $_POST['applied_tags'] ?? '';
            $description = $_POST['description'] ?? '';
            $displayName = $_POST['name'] ?? '';

            $file_name = $_FILES['upload-file']['name'];
            $tmp_name  = $_FILES['upload-file']['tmp_name'];
            $file_size = $_FILES['upload-file']['size'];

            if ($file_size > $MAX_FILE_SIZE) {
                $mb = round($MAX_FILE_SIZE / 1024 / 1024);
                echo "<script>alert('Túl nagy a fájl. Maximum {$mb} MB-os fájlt tölthetsz fel.');</script>";
            } else {
                $sumRes = db_query(
                    $conn,
                    "SELECT COALESCE(SUM(file_size), 0) AS used_bytes FROM files WHERE uploaded_by = ?",
                    "i",
                    [$user['id']]
                );
                $sumRow   = $sumRes ? $sumRes->fetch_assoc() : ['used_bytes' => 0];
                $usedNow  = (int)$sumRow['used_bytes'];
                $afterNew = $usedNow + $file_size;

                if ($afterNew > $MAX_USER_TOTAL) {
                    $maxMb = round($MAX_USER_TOTAL / 1024 / 1024);
                    $usedMb  = round($usedNow / 1024 / 1024);
                    $fileMb  = round($file_size / 1024 / 1024);
                    echo "<script>alert('Nincs elég hely a felhasználói kvótádban. Max {$maxMb} MB-ot használhatsz. Jelenleg ~{$usedMb} MB-ot használsz, a fájl mérete ~{$fileMb} MB.');</script>";
                } else {
                    $file_type = mime_content_type($tmp_name);
                    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    $allowed_extensions = ['pdf', 'mp4', 'docx'];
                    $allowed_types = [ 'application/pdf', 'video/mp4', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

                    if (!in_array($file_ext, $allowed_extensions, true) || !in_array($file_type, $allowed_types, true)) {
                        echo "<script>alert('Ez a fájltípus nem engedélyezett. Csak PDF, MP4 és DOCX tölthető fel.');</script>";
                    } else {
                        $folder = getcwd();
                        $dir    = $folder . "/users/" . $user['username'] . "/";

                        if (!is_dir($dir)) {
                            mkdir($dir, 0777, true);
                        }

                        $targetPath = $dir . $file_name;
                        if (file_exists($targetPath)) {
                            $base = pathinfo($file_name, PATHINFO_FILENAME);
                            $ext  = pathinfo($file_name, PATHINFO_EXTENSION);
                            $file_name = $base . '_' . time() . '.' . $ext;
                            $targetPath = $dir . $file_name;
                        }

                        if (move_uploaded_file($tmp_name, $targetPath)) {
                            db_stmt($conn, "INSERT INTO files (uploaded_by, name, file_name, description, file_path, subject, tags, file_size) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", "issssssi", [$user['id'], $displayName, $file_name, $description, $targetPath, $subject, $tags, $file_size] )->close();
                            echo "<script>alert('A fájl sikeresen feltöltve!');</script>";
                            header("Location: upload.php");
                            exit;
                        } else {
                            echo "<script>alert('A fájl mozgatása a célmappába nem sikerült.');</script>";
                        }
                    }
                }
            }
        }
    }

    $sql = "SELECT * FROM notifys WHERE toid = $user[id] AND readed = 0";
    $founded_notify = $conn->query($sql);
    $notify_number = mysqli_num_rows($founded_notify);
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
	   <script src="assets/js/script.js"></script>
   </head>
   <body>
        <?php include 'assets/php/navbar.php'; ?>
        <div class="content-wrapper w-full">
            <?php include "assets/php/ads.php"; ?>
            <div class="main w-full max-w-3xl mx-auto px-4 md:px-6 lg:px-8 py-6">
            <h1 class="text-2xl md:text-3xl lg:text-4xl mb-6">Anyag feltöltése</h1>
            <form class="card p-4 md:p-6 flex flex-col gap-4" method="post" enctype="multipart/form-data">
                <label for="name" class="text-sm md:text-base font-semibold">Anyag neve:</label>
                <input class="input w-full text-sm md:text-base" type="text" name="name" placeholder="pl. Fizika ZH anyag" required>
                <label for="description" class="text-sm md:text-base font-semibold">Leírás:</label>
                <textarea class="input w-full text-sm md:text-base" name="description" placeholder="Rövid leírás az anyagról..." rows="4" required></textarea>
                <label for="subject" class="text-sm md:text-base font-semibold">Tárgy:</label>
                <input class="input w-full text-sm md:text-base" type="text" name="subject" placeholder="pl. fizika, történelem" required>
                <label for="tag" class="text-sm md:text-base font-semibold">Címkék:</label>
                <textarea class="input w-full text-sm md:text-base" id="tag" name="applied_tags" placeholder="Címkék..." rows="3" readonly></textarea>
                <?php include 'assets/php/kereso_tag.php'; ?>
                <label for="upload-file" class="text-sm md:text-base font-semibold">Fájl kiválasztása:</label>
                <div class="file-input-wrapper w-full">
                    <input class="input w-full text-sm md:text-base" type="file" name="upload-file" required>
                </div>
                <button type="submit" name="upload-btn" class="btn-cta w-full md:w-auto text-sm md:text-base mt-2">Feltöltés</button>
            </form>
            </div>
        </div>
        <?php include 'assets/php/footer.php'; ?>
   </body>
</html>