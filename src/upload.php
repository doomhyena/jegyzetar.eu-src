<?php
    require "assets/php/db.php";
    require "assets/php/lang.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
    }

?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title><?= t('upload_title') ?></title>
    <meta name="description" content="Iskolai jegyzeteket megosztó oldal">
    <meta name="keywords" content="iskola, jegyzet, megosztás, tanulás">
    <meta name='author' content='Baranyai Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.aurora.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>

<?php include 'assets/php/navbar.php'; ?>
<div class="main" style="max-width: 700px;">
    <h1><?= t('upload_heading') ?></h1>
    <form class="card" method="post" enctype="multipart/form-data">
        <label for="name"><?= t('upload_label_name') ?></label>
        <input class="input" type="text" name="name"
               placeholder="<?= t('upload_placeholder_name') ?>" required>
        <label for="description"><?= t('upload_label_description') ?></label>
        <textarea class="input" name="description"
                  placeholder="<?= t('upload_placeholder_description') ?>"
                  rows="4" required></textarea>
        <label for="subject"><?= t('upload_label_subject') ?></label>
        <input class="input" type="text" name="subject"
               placeholder="<?= t('upload_placeholder_subject') ?>" required>
        <label for="tags"><?= t('upload_label_tags') ?></label>
        <input class="input" type="text" name="tags"
               placeholder="<?= t('upload_placeholder_tags') ?>" required>
        <label for="upload-file"><?= t('upload_label_file') ?></label>
        <div class="file-input-wrapper">
            <input class="input" type="file" name="upload-file" required>
        </div>
        <button type="submit" name="upload-btn" class="btn-cta">
            <?= t('upload_btn_upload') ?>
        </button>
    </form>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
