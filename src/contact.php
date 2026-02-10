<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    session_start();

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    $message = '';
    $message_type = '';

    $user_email = '';
    $user_name = '';

    if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
        $userId = (int)$_SESSION['id'];
        $result = db_query($conn, "SELECT firstname, lastname, email FROM users WHERE id = ? LIMIT 1", "i", [$userId]);
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $user_email = $user['email'];
            $user_name = ($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '');
        }
    }

    if (isset($_POST['send_message'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $msg_body = trim($_POST['message'] ?? '');

        if (empty($name)) {
            $message = 'A név megadása kötelező.';
            $message_type = 'error';
        } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Kérjük, adjon meg érvényes email címet.';
            $message_type = 'error';
        } elseif (empty($subject)) {
            $message = 'A tárgy megadása kötelező.';
            $message_type = 'error';
        } elseif (empty($msg_body) || strlen($msg_body) < 10) {
            $message = 'Az üzenetnek legalább 10 karakter hosszúnak kell lennie.';
            $message_type = 'error';
        } else {
            $admin_email = 'admin@jegyzetar.eu';
            $email_subject = "[Jegyzetár Üzenet] " . htmlspecialchars($subject);
            
            $email_body = '
                <html>
                <head>
                    <meta charset="utf-8">
                </head>
                <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                    <h2 style="color: #2c3e50;">Új üzenet a Jegyzetár kapcsolati formból</h2>
                    <hr>
                    <p><strong>Feladó neve:</strong> ' . htmlspecialchars($name) . '</p>
                    <p><strong>Feladó email:</strong> <a href="mailto:' . htmlspecialchars($email) . '">' . htmlspecialchars($email) . '</a></p>
                    <p><strong>Tárgy:</strong> ' . htmlspecialchars($subject) . '</p>
                    <hr>
                    <h3>Üzenet:</h3>
                    <div style="background-color: #f4f4f4; padding: 15px; border-left: 4px solid #3498db;">
                        ' . nl2br(htmlspecialchars($msg_body)) . '
                    </div>
                    <hr>
                    <p style="color: #7f8c8d; font-size: 12px;">
                        IP cím: ' . htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'N/A') . '<br>
                        Időbélyeg: ' . date('Y-m-d H:i:s') . '
                    </p>
                </body>
                </html>
            ';

            $headers = "From: Jegyzetar Contact <noreply@jegyzetar.eu>\r\n";
            $headers .= "Reply-To: " . htmlspecialchars($email) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";

            if (mail($admin_email, $email_subject, $email_body, $headers)) {
                $user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : null;
                $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
                
                try {
                    db_exec($conn,"INSERT INTO contact_messages (user_id, sender_name, sender_email, subject, message, ip_address, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())","isssss",[$user_id, $name, $email, $subject, $msg_body, $ip_address]);
                } catch (Exception $e) {
                    error_log("Hiba a contact message mentésekor: " . $e->getMessage());
                }

                $reply_body = '
                    <html>
                    <head>
                        <meta charset="utf-8">
                    </head>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                        <h2 style="color: #2c3e50;">Köszönjük az üzenetét!</h2>
                        <p>Üdv,</p>
                        <p>Megkaptuk az üzenetét: "<strong>' . htmlspecialchars($subject) . '</strong>"</p>
                        <p>Az adminok hamarosan válaszolni fognak rá.</p>
                        <hr>
                        <p style="color: #7f8c8d; font-size: 12px;">Jegyzetár csapata</p>
                    </body>
                    </html>
                ';

                $reply_headers = "From: Jegyzetar <noreply@jegyzetar.eu>\r\n";
                $reply_headers .= "MIME-Version: 1.0\r\n";
                $reply_headers .= "Content-type: text/html; charset=UTF-8\r\n";

                mail($email, "[Jegyzetár] Az üzenetét megkaptuk", $reply_body, $reply_headers);

                $message = 'Az üzenetét sikeresen elküldtük! Hamarosan válaszolunk.';
                $message_type = 'success';

                $_POST['name'] = '';
                $_POST['email'] = '';
                $_POST['subject'] = '';
                $_POST['message'] = '';
            } else {
                $message = 'Hiba történt az üzenet küldése során. Kérjük, próbálja később.';
                $message_type = 'error';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title>Kapcsolatfelvétel</title>
    <meta name='description' content='Iskolai jegyzeteket megosztó oldal'>
    <meta name='keywords' content='iskola, jegyzet, megosztás, tanulás'>
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
    <?php include 'assets/php/navbar.php'; ?>
    <main class="main">
        <div style="max-width: 700px; margin: 0 auto; width: 100%;">
            <div class="card">
                <div class="card-head">
                    <div>
                        <h1 style="margin:0;">Kapcsolatfelvétel</h1>
                        <p class="entry-meta" style="margin:6px 0 0;">
                            Van kérdésed vagy javaslat? Írj nekünk!
                        </p>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="toast <?php echo ($message_type === 'success') ? 'toast-success' : 'toast-error'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="form-grid" autocomplete="off">
                    <div class="form-field">
                        <label for="name">Név *</label>
                        <input type="text" id="name" name="name" class="input" maxlength="255" required 
                            value="<?php echo htmlspecialchars($user_name ?? $_POST['name'] ?? ''); ?>"
                            placeholder="Teljes neved">
                    </div>

                    <div class="form-field">
                        <label for="email">Email cím *</label>
                        <input type="email" id="email" name="email" class="input" maxlength="190" required
                            value="<?php echo htmlspecialchars($user_email ?? $_POST['email'] ?? ''); ?>"
                            placeholder="email@example.com">
                    </div>

                    <div class="form-field">
                        <label for="subject">Tárgy *</label>
                        <input type="text" id="subject" name="subject" class="input" maxlength="255" required
                            value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>"
                            placeholder="Miről szeretnél írni?">
                    </div>

                    <div class="form-field">
                        <label for="message">Üzenet *</label>
                        <textarea id="message" name="message" class="input" maxlength="5000" required
                            placeholder="Írd ide az üzenedet..."
                            style="min-height: 200px; resize: vertical;"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        <small class="entry-meta">Minimum 10 karakter, maximum 5000.</small>
                    </div>

                    <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-top:6px;">
                        <button type="submit" name="send_message" class="btn-primary">
                            Üzenet küldése
                        </button>
                        <span class="entry-meta">Ne adj meg jelszót vagy érzékeny adatot.</span>
                    </div>
                </form>
            </div>
            <div class="content-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); margin-top: 32px;">
                <div class="card">
                    <div class="card-head">
                        <h3>E-mail</h3>
                    </div>
                    <p class="entry-meta">admin@jegyzetar.eu</p>
                </div>

                <div class="card">
                    <div class="card-head">
                        <h3>Discord</h3>
                    </div>
                    <p class="entry-meta">
                        Discord szerverünket a Közösség menüpont alatt találod, csatlakozz és írj nekünk ott is!
                    </p>
                </div>
            </div>
        </div>
    </main>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>
