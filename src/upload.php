<?php
    require  "assets/php/db.php";

    if(!isset($_COOKIE['id'])){
        header("Location: reglog.php");
    }

    $userid = $_COOKIE['id'];
    $sql = "SELECT * FROM users WHERE id='$userid'";
    $found_user = $conn->query($sql);
    $user = $found_user->fetch_assoc();

    if(isset($_POST['upload-btn'])){
        $subject = $_POST['subject'];
        $tags = $_POST['tags'];

        if (empty($subject) || empty($tags)) {
            echo "<script>alert('Kérjük, adja meg a tárgyat és a címkéket!')</script>";
            exit;
        }
        $file_name = $_FILES['upload-file']['name'];
        $tmp_name = $_FILES['upload-file']['tmp_name'];
        $file_type = mime_content_type($tmp_name);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['pdf', 'mp4', 'docx'];
        $allowed_types = ['application/pdf', 'video/mp4', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        if (!in_array($file_ext, $allowed_extensions) || !in_array($file_type, $allowed_types)) {
            echo "<script>alert('Csak PDF, MP4 vagy DOCX fájlokat lehet feltölteni!')</script>";
            header("Location: upload.php");
        }

        $folder = getcwd();
        $dir = $folder . "/users/" . $user['username'] . "/";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true); 
        }

        $description = $_POST['description'];
        $path =  $folder . "/users/" . $user['username'] . "/".$file_name;

        if(move_uploaded_file($tmp_name, $path)){
            $conn->query("INSERT INTO files (uploaded_by, name, file_name, description, file_path) VALUES ('$user[id]', '{$_POST['name']}', '$file_name', '$description', '$path')");
            echo "<script>alert('A fájl sikeresen feltöltve!')</script>";
			header("Location: upload.php");
        } else {
            echo "<script>alert('A fájl feltöltése sikertelen!')</script>";
			header("Location: upload.php");
        }
    }

    $sql = "SELECT * FROM notifys WHERE toid = $user[id] AND readed = 0";
    $founded_notify = $conn->query($sql);
    $notify_number = mysqli_num_rows($founded_notify);
    require "assets/php/lang.php";

?>
<!DOCTYPE html>
<html lang="hu">
   <head>
       <title>Feltöltés</title>
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
        
        <div class="main" style="max-width: 700px;">
            <h1>Anyag feltöltése</h1>
            
            <form class="card" method="post" enctype="multipart/form-data">
                <label for="name">Anyag neve:</label>
                <input class="input" type="text" name="name" placeholder="pl. Fizika ZH anyag" required>
                
                <label for="description">Leírás:</label>
                <textarea class="input" name="description" placeholder="Rövid leírás az anyagról..." rows="4" required></textarea>
                <label for="subject">Tárgy:</label>
                <input class="input" type="text" name="subject" placeholder="pl. fizika, történelem" required>
                <label for="tags">Kulcsszavak, címkék:</label>
                <input class="input" type="text" name="tags" placeholder="pl. ZH, jegyzet, beadandó" required>
                <label for="upload-file">Fájl kiválasztása:</label>
                <div class="file-input-wrapper">
                    <input class="input" type="file" name="upload-file" required>
                </div>
                <button type="submit" name="upload-btn" class="btn-cta">Feltöltés</button>
            </form>
        </div>
        <?php include 'assets/php/footer.php'; ?>
   </body>
</html>