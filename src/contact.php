<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    require __DIR__ . "/assets/php/db.php";
    require __DIR__ . "/assets/php/lang.php";
    require_once __DIR__ . "/assets/php/functions.php";

    require_once __DIR__ . "/assets/phpmailer/src/PHPMailer.php";
    require_once __DIR__ . "/assets/phpmailer/src/Exception.php";
    require_once __DIR__ . "/assets/phpmailer/src/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $config = json_decode(file_get_contents('config.json'), true);
    if (!$config) {
        die("Error: Could not load config file!");
    }

    function send_smtp_mail(array $config, callable $setup): bool {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = $config['smtp_auth'];
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['smtp_secure'];
        $mail->Port = (int)$config['port'];
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($config['username'], 'Jegyzetár');

        $setup($mail);

        return $mail->send();
    }

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
            $user_name = trim(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? ''));
        }
    }

    if (isset($_POST['send_message'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $msg_body = trim($_POST['message'] ?? '');

        if (empty($name)) {
            $message = t('contact_error_name_required');
            $message_type = 'error';
        } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = t('contact_error_valid_email');
            $message_type = 'error';
        } elseif (empty($subject)) {
            $message = t('contact_error_subject_required');
            $message_type = 'error';
        } elseif (empty($msg_body) || strlen($msg_body) < 10) {
            $message = t('contact_error_message_length');
            $message_type = 'error';
        } else {
            $admin_email = 'admin@jegyzetar.eu';

            $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
            $safeSubject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
            $safeMsgHtml = nl2br(htmlspecialchars($msg_body, ENT_QUOTES, 'UTF-8'));

            $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
            $timestamp = date('Y-m-d H:i:s');

            $adminSubject = "[Jegyzetár Üzenet] " . $subject;

            $adminHtml = '
<!DOCTYPE html>
<html lang="hu">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#0b0f14;font-family:Arial,Helvetica,sans-serif;">
  <div style="max-width:740px;margin:0 auto;padding:24px;">
    <div style="background:#111827;border:1px solid rgba(255,255,255,0.08);border-radius:14px;padding:22px;color:#e5e7eb;">
      <h2 style="margin:0 0 10px 0;color:#fff;font-size:18px;">Új üzenet a Jegyzetár kapcsolati formból</h2>
      <p style="margin:0 0 14px 0;color:#cbd5e1;font-size:13px;line-height:1.6;">
        Az alábbi üzenet érkezett a kapcsolati űrlapról.
      </p>

      <div style="display:block;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:14px;">
        <p style="margin:0 0 6px 0;font-size:13px;color:#cbd5e1;"><strong style="color:#fff;">Feladó:</strong> '.$safeName.'</p>
        <p style="margin:0 0 6px 0;font-size:13px;color:#cbd5e1;"><strong style="color:#fff;">Email:</strong> <a style="color:#60a5fa;text-decoration:none;" href="mailto:'.$safeEmail.'">'.$safeEmail.'</a></p>
        <p style="margin:0;font-size:13px;color:#cbd5e1;"><strong style="color:#fff;">Tárgy:</strong> '.$safeSubject.'</p>
      </div>

      <h3 style="margin:16px 0 8px 0;color:#fff;font-size:14px;">Üzenet</h3>
      <div style="background:rgba(96,165,250,0.10);border:1px solid rgba(96,165,250,0.28);border-left:4px solid rgba(96,165,250,0.7);border-radius:12px;padding:14px;color:#e5e7eb;font-size:13px;line-height:1.6;">
        '.$safeMsgHtml.'
      </div>

      <hr style="border:none;border-top:1px solid rgba(255,255,255,0.08);margin:16px 0;">

      <p style="margin:0;font-size:11px;color:#94a3b8;line-height:1.6;">
        IP: '.$ip.'<br>
        Idő: '.$timestamp.'
      </p>
    </div>
  </div>
</body>
</html>';

            $adminText =
                "Új üzenet a Jegyzetár kapcsolati formból\n\n" .
                "Feladó: {$name}\n" .
                "Email: {$email}\n" .
                "Tárgy: {$subject}\n\n" .
                "Üzenet:\n{$msg_body}\n\n" .
                "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "\n" .
                "Idő: {$timestamp}\n";

            $userReplySubject = "[Jegyzetár] Az üzenetét megkaptuk";

            $userReplyHtml = '
<!DOCTYPE html>
<html lang="hu">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#0b0f14;font-family:Arial,Helvetica,sans-serif;">
  <div style="max-width:640px;margin:0 auto;padding:24px;">
    <div style="background:#111827;border:1px solid rgba(255,255,255,0.08);border-radius:14px;padding:22px;color:#e5e7eb;">
      <h2 style="margin:0 0 10px 0;color:#fff;font-size:18px;">Köszönjük az üzenetét!</h2>
      <p style="margin:0 0 12px 0;color:#cbd5e1;font-size:13px;line-height:1.6;">
        Üdv '.$safeName.',
      </p>
      <p style="margin:0 0 14px 0;color:#cbd5e1;font-size:13px;line-height:1.6;">
        Megkaptuk az üzenetét a következő tárggyal:
        <strong style="color:#fff;">'.$safeSubject.'</strong>
      </p>

      <div style="margin:14px 0;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:14px;">
        <div style="font-size:12px;color:#93c5fd;letter-spacing:0.06em;text-transform:uppercase;margin-bottom:8px;">Az üzenete</div>
        <div style="font-size:13px;line-height:1.6;color:#e5e7eb;">'.$safeMsgHtml.'</div>
      </div>

      <p style="margin:0;color:#cbd5e1;font-size:13px;line-height:1.6;">
        Az adminok hamarosan válaszolni fognak rá.
      </p>

      <hr style="border:none;border-top:1px solid rgba(255,255,255,0.08);margin:16px 0;">

      <p style="margin:0;font-size:12px;line-height:1.6;color:#94a3b8;">
        Üdvözlettel,<br>
        <strong style="color:#e5e7eb;">Jegyzetár csapata</strong>
      </p>
    </div>

    <p style="margin:14px 0 0 0;text-align:center;font-size:11px;color:#64748b;">
      Ez egy automatikus üzenet, kérlek ne válaszolj rá.
    </p>
  </div>
</body>
</html>';

            $userReplyText =
                "Köszönjük az üzenetét!\n\n" .
                "Megkaptuk az üzenetét a következő tárggyal: {$subject}\n\n" .
                "Az üzenete:\n{$msg_body}\n\n" .
                "Az adminok hamarosan válaszolni fognak rá.\n\n" .
                "Üdvözlettel,\nJegyzetár csapata\n";

            try {
                $okAdmin = send_smtp_mail($config, function(PHPMailer $mail) use ($admin_email, $adminSubject, $adminHtml, $adminText, $email, $name) {
                    $mail->addAddress($admin_email);
                    $mail->addReplyTo($email, $name);
                    $mail->isHTML(true);
                    $mail->Subject = $adminSubject;
                    $mail->Body = $adminHtml;
                    $mail->AltBody = $adminText;
                });

                if ($okAdmin) {
                    $user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : null;
                    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';

                    try {
                        db_exec(
                            $conn,
                            "INSERT INTO contact_messages (user_id, sender_name, sender_email, subject, message, ip_address, created_at)
                             VALUES (?, ?, ?, ?, ?, ?, NOW())",
                            "isssss",
                            [$user_id, $name, $email, $subject, $msg_body, $ip_address]
                        );
                    } catch (Exception $e) {
                        error_log("Hiba a contact message mentésekor: " . $e->getMessage());
                    }

                    try {
                        send_smtp_mail($config, function(PHPMailer $mail) use ($email, $name, $userReplySubject, $userReplyHtml, $userReplyText) {
                            $mail->addAddress($email, $name);
                            $mail->isHTML(true);
                            $mail->Subject = $userReplySubject;
                            $mail->Body    = $userReplyHtml;
                            $mail->AltBody = $userReplyText;
                        });
                    } catch (Exception $e) {
                        error_log("User reply mail error: " . $e->getMessage());
                    }

                    $message = t('contact_success_sent');
                    $message_type = 'success';

                    $_POST['name'] = '';
                    $_POST['email'] = '';
                    $_POST['subject'] = '';
                    $_POST['message'] = '';
                } else {
                    $message = t('contact_error_send_failed');
                    $message_type = 'error';
                }
            } catch (Exception $e) {
                error_log("Contact mail error: " . $e->getMessage());
                $message = t('contact_error_send_failed');
                $message_type = 'error';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang ?? 'hu') ?>">
<head>
    <title><?= t('contact_title') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name="author" content="Baranyi Norbert, Csontos Kincső, Szekeres Levente">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
    <?php include __DIR__ . '/assets/php/navbar.php'; ?>
    <main class="main">
        <div style="max-width: 700px; margin: 0 auto; width: 100%;">
            <div class="card">
                <div class="card-head">
                    <div>
                        <h1 style="margin:0;"><?= t('contact_h1') ?></h1>
                        <p class="entry-meta" style="margin:6px 0 0;"><?= t('contact_sub') ?></p>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="toast <?php echo ($message_type === 'success') ? 'toast-success' : 'toast-error'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="form-grid" autocomplete="off">
                    <div class="form-field">
                        <label for="name"><?= t('contact_label_name') ?></label>
                        <input type="text" id="name" name="name" class="input" maxlength="255" required
                            value="<?php echo htmlspecialchars($user_name ?? $_POST['name'] ?? ''); ?>"
                            placeholder="<?= t('contact_placeholder_name') ?>">
                    </div>

                    <div class="form-field">
                        <label for="email"><?= t('contact_label_email') ?></label>
                        <input type="email" id="email" name="email" class="input" maxlength="190" required
                            value="<?php echo htmlspecialchars($user_email ?? $_POST['email'] ?? ''); ?>"
                            placeholder="email@example.com">
                    </div>

                    <div class="form-field">
                        <label for="subject"><?= t('contact_label_subject') ?></label>
                        <input type="text" id="subject" name="subject" class="input" maxlength="255" required
                            value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>"
                            placeholder="<?= t('contact_placeholder_subject') ?>">
                    </div>

                    <div class="form-field">
                        <label for="message"><?= t('contact_label_message') ?></label>
                        <textarea id="message" name="message" class="input" maxlength="5000" required
                            placeholder="<?= t('contact_placeholder_message') ?>"
                            style="min-height: 200px; resize: vertical;"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        <small class="entry-meta"><?= t('contact_hint_chars') ?></small>
                    </div>

                    <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-top:6px;">
                        <button type="submit" name="send_message" class="btn-primary">
                            <?= t('contact_btn_send') ?>
                        </button>
                        <span class="entry-meta"><?= t('contact_hint_no_sensitive') ?></span>
                    </div>
                </form>
            </div>
            <div class="content-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); margin-top: 32px;">
                <div class="card">
                    <div class="card-head">
                        <h3><?= t('contact_card_email_title') ?></h3>
                    </div>
                    <p class="entry-meta">admin@jegyzetar.eu</p>
                </div>
                <div class="card">
                    <div class="card-head">
                        <h3><?= t('contact_card_discord_title') ?></h3>
                    </div>
                    <p class="entry-meta"><?= t('contact_card_discord_text') ?></p>
                </div>
            </div>
        </div>
    </main>
    <?php include __DIR__ . '/assets/php/footer.php'; ?>
</body>
</html>