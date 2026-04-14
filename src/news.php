<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    $isLoggedIn = auth_is_logged_in();
    $user = $isLoggedIn ? auth_user($conn) : null;
    $isAdmin = $user && !empty($user['admin']) && (int)$user['admin'] === 1;

    $errors = [];
    $success = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin && isset($_POST['news_action']) && $_POST['news_action'] === 'create') {
        $title = trim((string)($_POST['title'] ?? ''));
        $content = trim((string)($_POST['content'] ?? ''));

        if ($title === '') {
            $errors[] = t('news_error_title_required');
        }
        if ($content === '') {
            $errors[] = t('news_error_content_required');
        }

        if (empty($errors)) {
            db_exec(
                $conn,
                "INSERT INTO announcements (title, content, created_by, created_at) VALUES (?, ?, ?, NOW())",
                "ssi",
                [$title, $content, (int)$user['id']]
            );

            $success = t('news_success_posted');
        }
    }

    $announcements = db_query(
        $conn,
        "SELECT a.*, u.username FROM announcements a LEFT JOIN users u ON u.id = a.created_by ORDER BY created_at DESC"
    );

?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang ?? 'hu') ?>">
<head>
    <title><?= t('news_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('news_meta_description') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
    <?php include 'assets/php/navbar.php'; ?>
    <div class="content-wrapper w-full">
        <div class="main w-full max-w-6xl mx-auto px-4 md:px-6 lg:px-8">
            <section class="card mb-6">
                <div class="card-body p-5">
                    <h1 class="text-2xl font-bold mb-4"><?= t('news_heading') ?></h1>

                    <?php if ($success): ?>
                        <div class="toast toast-success" role="status"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="toast toast-error" role="alert">
                            <?php foreach ($errors as $error): ?>
                                <div><?= htmlspecialchars($error) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($isAdmin): ?>
                        <form method="POST" class="mb-6">
                            <input type="hidden" name="news_action" value="create">
                            <div class="form-field mb-3">
                                <label for="title"><?= t('news_form_title') ?></label>
                                <input id="title" name="title" type="text" class="input" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                            </div>
                            <div class="form-field mb-3">
                                <label for="content"><?= t('news_form_content') ?></label>
                                <textarea id="content" name="content" class="input" rows="6" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                            </div>
                            <button type="submit" class="btn-cta"><?= t('news_form_submit') ?></button>
                        </form>
                    <?php else: ?>
                        <p class="mb-4"><?= t('news_readonly_message') ?></p>
                    <?php endif; ?>

                    <?php if ($announcements && $announcements->num_rows > 0): ?>
                        <div class="space-y-4">
                            <?php while ($news = $announcements->fetch_assoc()): ?>
                                <article class="card p-4">
                                    <h2 class="font-bold text-xl mb-1"><?= htmlspecialchars($news['title']) ?></h2>
                                    <div class="text-xs opacity-70 mb-2">
                                        <?= t('news_posted_by') ?> <strong><?= htmlspecialchars($news['username'] ?? t('news_anonymous')) ?></strong>
                                        <?= t('news_posted_at') ?> <?= htmlspecialchars($news['created_at']) ?>
                                    </div>
                                    <p><?= nl2br(htmlspecialchars($news['content'])) ?></p>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p><?= t('news_no_items') ?></p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>
