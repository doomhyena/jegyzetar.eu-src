<?php
    // norbi: mail-2fa (PHPMailer verzió)
    require __DIR__ . "/db.php";
    require_once __DIR__ . "/functions.php";

    require_once __DIR__ . "/../phpmailer/src/PHPMailer.php";
    require_once __DIR__ . "/../phpmailer/src/Exception.php";
    require_once __DIR__ . "/../phpmailer/src/SMTP.php";
    session_start();

    $config = json_decode(file_get_contents(__DIR__ . "/../../config.json"), true);

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if (!$config) {
        die("Error: Could not load config file!");
    }

    if (!isset($_SESSION['id'], $_SESSION['email']) || !ctype_digit((string)$_SESSION['id'])) {
        header("Location: " . base_url("reglog.php"));
        exit;
    }

    $userId = (int)$_SESSION['id'];

    $recipientEmail = filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        session_destroy();
        header("Location: " . base_url("reglog.php"));
        exit;
    }

    $code = random_int(10000, 99999);

    $subject = "Bejelentkezési kódod: {$code}";

    $messageHtml = '
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bejelentkezési kód</title>
</head>
<body style="margin:0;padding:0;background:#0b0f14;font-family:Arial,Helvetica,sans-serif;">
  <div style="max-width:640px;margin:0 auto;padding:24px;">
    <div style="background:#111827;border:1px solid rgba(255,255,255,0.08);border-radius:14px;padding:24px;color:#e5e7eb;">
      <h2 style="margin:0 0 12px 0;font-size:20px;line-height:1.3;color:#ffffff;">Bejelentkezési kód</h2>

      <p style="margin:0 0 12px 0;font-size:14px;line-height:1.6;color:#cbd5e1;">
        Láttuk, hogy bejelentkezési kísérlet történt a fiókodba.
      </p>

      <p style="margin:0 0 16px 0;font-size:14px;line-height:1.6;color:#cbd5e1;">
        Ha ez te voltál, írd be ezt a kódot a bejelentkezési oldalon:
      </p>

      <div style="margin:18px 0;padding:16px;border-radius:12px;background:rgba(96,165,250,0.12);border:1px solid rgba(96,165,250,0.35);text-align:center;">
        <div style="font-size:12px;letter-spacing:0.08em;color:#93c5fd;margin-bottom:8px;text-transform:uppercase;">Kód</div>
        <div style="font-size:34px;font-weight:800;letter-spacing:0.18em;color:#e5e7eb;">'.$code.'</div>
      </div>

      <div style="margin-top:14px;padding:12px 14px;border-radius:12px;background:rgba(245,158,11,0.10);border:1px solid rgba(245,158,11,0.35);">
        <p style="margin:0;font-size:13px;line-height:1.6;color:#fde68a;">
          <strong>Ha nem te voltál:</strong> azonnal változtasd meg a jelszavad, és ellenőrizd a fiókod biztonsági beállításait.
        </p>
      </div>

      <hr style="border:none;border-top:1px solid rgba(255,255,255,0.08);margin:18px 0;">

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
</html>
';

    $messageText =
        "Bejelentkezési kód\n\n" .
        "Láttuk, hogy bejelentkezési kísérlet történt a fiókodba.\n" .
        "Ha ez te voltál, írd be ezt a kódot:\n\n" .
        "KÓD: {$code}\n\n" .
        "Ha nem te voltál, azonnal változtasd meg a jelszavad.\n\n" .
        "Üdvözlettel,\nJegyzetár csapata\n";

    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = $config['smtp_auth'];
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['smtp_secure'];
        $mail->Port = (int)$config['port'];

        $mail->CharSet = 'UTF-8';

        $mail->setFrom($config['username'], 'Jegyzetár');
        $mail->addAddress($recipientEmail);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $messageHtml;
        $mail->AltBody = $messageText;

        $mail->send();

        db_exec($conn, "INSERT INTO 2fa_codes (userid, code) VALUES (?, ?)", "is", [$userId, (string)$code]);

        header("Location: " . base_url("2fa.php"));
        exit;

    } catch (Exception $e) {
        error_log("Mail error (2fa): " . $e->getMessage());
        session_destroy();
        header("Location: " . base_url("reglog.php"));
        exit;
    }
