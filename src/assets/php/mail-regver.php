<?php
    // norbi: mail-regver (PHPMailer verzió)
    require __DIR__ . "/db.php";
    require_once __DIR__ . "functions.php";

    require_once __DIR__ . "/../phpmailer/src/PHPMailer.php";
    require_once __DIR__ . "/../phpmailer/src/Exception.php";
    require_once __DIR__ . "/../phpmailer/src/SMTP.php";
    session_start();

    $config = json_decode(file_get_contents(__DIR__ . "/../../config.json"), true);

    if (!$config) {
        die("Error: Could not load config file!");
    }

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if (!isset($_SESSION['ver_id'], $_SESSION['email'])) {
        header("Location: " . base_url("reglog.php"));
        exit;
    }

    $userId = (int)$_SESSION['ver_id'];
    $recipientEmail = filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        session_destroy();
        header("Location: " . base_url("reglog.php"));
        exit;
    }

    $token = random_int(100000, 999999);

    // $verifyUrl = "https://jegyzetar.eu-src/src/reg-ver.php?token=" . urlencode((string)$token);
    $verifyUrl = "https://jegyzetar.eu/reg-ver.php?token=" . urlencode((string)$token);

    $subject = "Regisztráció visszaigazolás";

    $messageHtml = '
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Regisztráció visszaigazolás</title>
</head>
<body style="margin:0;padding:0;background:#0b0f14;font-family:Arial,Helvetica,sans-serif;">
  <div style="max-width:640px;margin:0 auto;padding:24px;">
    <div style="background:#111827;border:1px solid rgba(255,255,255,0.08);border-radius:14px;padding:24px;color:#e5e7eb;">
      <h2 style="margin:0 0 12px 0;font-size:20px;line-height:1.3;color:#ffffff;">Üdv!</h2>

      <p style="margin:0 0 16px 0;font-size:14px;line-height:1.6;color:#cbd5e1;">
        Köszönjük a regisztrációt. A fiókod aktiválásához kattints az alábbi gombra:
      </p>

      <div style="margin:20px 0;">
        <a href="'.$verifyUrl.'"
           style="display:inline-block;background:#22c55e;color:#0b0f14;text-decoration:none;
                  padding:12px 16px;border-radius:10px;font-weight:700;font-size:14px;">
          Regisztráció visszaigazolása
        </a>
      </div>

      <p style="margin:0 0 10px 0;font-size:13px;line-height:1.6;color:#94a3b8;">
        Ha a gomb nem működik, másold be ezt a linket a böngészőbe:
      </p>

      <p style="margin:0 0 18px 0;font-size:12px;line-height:1.6;word-break:break-all;">
        <a href="'.$verifyUrl.'" style="color:#60a5fa;text-decoration:none;">'.$verifyUrl.'</a>
      </p>

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
        "Üdv!\n\n" .
        "A regisztráció visszaigazolásához nyisd meg ezt a linket:\n" .
        $verifyUrl . "\n\n" .
        "Üdvözlettel,\nJegyzetár csapata\n";

    try {
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
        $mail->addAddress($recipientEmail);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $messageHtml;
        $mail->AltBody = $messageText;

        $mail->send();

        db_stmt(
            $conn,
            "INSERT INTO tokens (user_id, token) VALUES (?, ?)",
            "ii",
            [$userId, $token]
        )->close();

        header("Location: " . base_url("reglog.php"));
        exit;

    } catch (Exception $e) {
        error_log("Mail error (reg-ver): " . $e->getMessage());
        session_destroy();
        header("Location: " . base_url("reglog.php"));
        exit;
    }
