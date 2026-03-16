<?php
    ini_set('session.gc_maxlifetime', 3600);
    ini_set('session.cookie_lifetime', 3600);

    session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();

    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__ . '/../php/db.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    if (!isset($_GET['code'])) {
        http_response_code(400);
        exit('Missing authorization code');
    }

    if (!isset($_GET['state'])) {
        http_response_code(400);
        exit('Missing state parameter');
    }

    $storedState = $_SESSION['oauth_state'] ?? null;
    if (!$storedState || $_GET['state'] !== $storedState) {
        $debug = [
            'session_state' => $storedState,
            'get_state' => $_GET['state'] ?? 'not set',
            'session_id' => session_id(),
            'session_status' => session_status(),
            'session_save_path' => session_save_path(),
            'time' => time(),
            'server' => $_SERVER['HTTP_HOST'] ?? 'unknown',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
        ];

        $logFile = __DIR__ . '/../logs/oauth_debug.log';
        file_put_contents($logFile, date('Y-m-d H:i:s') . ' - ' . json_encode($debug) . PHP_EOL, FILE_APPEND);

        echo '<pre>Debug Info:<br>';
        print_r($debug);
        echo '</pre>';

        http_response_code(400);
        exit('Invalid state parameter (security check failed). Please try again.
        <br><br>
        <a href="/jegyzetar.eu-src/src/reglog.php">Back to login</a>');
    }
    unset($_SESSION['oauth_state']);

    $code = $_GET['code'];
    $clientId = $_ENV['DISCORD_CLIENT_ID'];
    $clientSecret = $_ENV['DISCORD_CLIENT_SECRET'];
    $redirectUri = $_ENV['DISCORD_REDIRECT_URI'];

    $ch = curl_init('https://discord.com/api/oauth2/token');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_POSTFIELDS => http_build_query([
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ]),
    ]);
    $tokenRes = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (empty($tokenRes['access_token'])) {
        exit('Token error');
    }

    $accessToken = $tokenRes['access_token'];

    $ch = curl_init('https://discord.com/api/users/@me');
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
        CURLOPT_RETURNTRANSFER => true,
    ]);
    $userinfo = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (empty($userinfo['id'])) {
        exit('Userinfo error');
    }

    $sub = $conn->real_escape_string($userinfo['id']);
    $email = $conn->real_escape_string($userinfo['email'] ?? '');
    $usernameD = $conn->real_escape_string($userinfo['username'] ?? '');
    $display = $conn->real_escape_string($userinfo['global_name'] ?? $usernameD);

    $avatar = '';
    if (!empty($userinfo['avatar'])) {
        $avatar = "https://cdn.discordapp.com/avatars/{$userinfo['id']}/{$userinfo['avatar']}.png?size=256";
    }
    $avatar = $conn->real_escape_string($avatar);

    $linkMode = isset($_SESSION['oauth_link_mode']) && (int)$_SESSION['oauth_link_mode'] === 1;
    if ($linkMode) {
        unset($_SESSION['oauth_link_mode']);
        if (isset($_COOKIE['id'])) {
            $uid = (int)$_COOKIE['id'];
            $userRes = $conn->query("SELECT username FROM users WHERE id=$uid LIMIT 1");
            $userData = $userRes ? $userRes->fetch_assoc() : null;
            $username = $userData['username'] ?? '';

            $conn->query("
                UPDATE users
                SET oauth_provider='discord',
                    oauth_sub='$sub'
                WHERE id=$uid
            ");
            $_SESSION['profile_toast'] = 'Discord fiók sikeresen összekapcsolva!';
            $redirectUrl = !empty($username) ? '/jegyzetar.eu-src/src/profile.php?user=' . urlencode($username) : '/jegyzetar.eu-src/src/index.php';
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    $found = $conn->query("SELECT id FROM users WHERE oauth_provider='discord' AND oauth_sub='$sub' LIMIT 1");
    if ($found && $found->num_rows) {
        $uid = (int)$found->fetch_assoc()['id'];
        setcookie('id', $uid, time() + 3600, '/');
        header('Location: /jegyzetar.eu-src/src/index.php');
        exit;
    }

    if ($email) {
        $sel = $conn->query("SELECT id FROM users WHERE email='$email' LIMIT 1");
        if ($sel && $sel->num_rows) {
            $uid = (int)$sel->fetch_assoc()['id'];
            $conn->query("
                UPDATE users
                SET oauth_provider='discord',
                    oauth_sub='$sub',
                    profile_picture='$avatar'
                WHERE id=$uid
            ");
            setcookie('id', $uid, time() + 3600, '/');
            header('Location: /jegyzetar.eu-src/src/index.php');
            exit;
        }
    }

    $baseUsername = strtolower($display ?: $usernameD ?: 'user');
    $baseUsername = preg_replace('/[^a-z0-9_]/i', '', $baseUsername);
    $username     = substr($baseUsername, 0, 20);

    $check = $conn->query("SELECT id FROM users WHERE username='$username' LIMIT 1");
    $counter = 1;
    while ($check && $check->num_rows) {
        $try = substr($username . $counter, 0, 20);
        $check = $conn->query("SELECT id FROM users WHERE username='$try' LIMIT 1");
        if (!$check || !$check->num_rows) {
            $username = $try;
            break;
        }
        $counter++;
    }

    $regdate = date('Y-m-d H:i:s');

    $conn->query("
        INSERT INTO users
            (username, email, firstname, lastname, password, oauth_provider, oauth_sub, email_verified, profile_picture, registration_date)
        VALUES
            ('$username', '$email', '$display', '', '', 'discord', '$sub', 1, '$avatar', '$regdate')
    ");

    $uid = (int)$conn->insert_id;

    setcookie('id', $uid, time() + 3600, '/');
    header('Location: /jegyzetar.eu-src/src/index.php');
    exit;