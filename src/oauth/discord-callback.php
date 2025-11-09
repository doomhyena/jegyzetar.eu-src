<?php
    session_start();
    require __DIR__ . '/../assets/php/db.php';

    require __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    if (!isset($_GET['code'], $_GET['state']) || $_GET['state'] !== ($_SESSION['oauth_state'] ?? '')) {
        http_response_code(400); exit('Invalid state');
    }
    unset($_SESSION['oauth_state']);

    $code = $_GET['code'];

    $ch = curl_init('https://discord.com/api/oauth2/token');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_POSTFIELDS => http_build_query([
            'client_id' => $_ENV['DISCORD_CLIENT_ID'],
            'client_secret' => $_ENV['DISCORD_CLIENT_SECRET'],
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $_ENV['DISCORD_REDIRECT_URI']
        ]),
    ]);
    $tokenRes = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (empty($tokenRes['access_token'])) { exit('Token error'); }
    $accessToken = $tokenRes['access_token'];

    $ch = curl_init('https://discord.com/api/users/@me');
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken],
        CURLOPT_RETURNTRANSFER => true,
    ]);
    $userinfo = json_decode(curl_exec($ch), true);
    curl_close($ch);

    $sub = $conn->real_escape_string($userinfo['id']);
    $email = $conn->real_escape_string($userinfo['email'] ?? '');
    $usernameD = $conn->real_escape_string($userinfo['username'] ?? '');
    $display = $conn->real_escape_string($userinfo['global_name'] ?? $usernameD);
    $avatar = '';
    if (!empty($userinfo['avatar'])) {
        $avatar = "https://cdn.discordapp.com/avatars/{$userinfo['id']}/{$userinfo['avatar']}.png?size=256";
    }
    $avatar = $conn->real_escape_string($avatar);

    $found = $conn->query("SELECT id FROM users WHERE oauth_provider='discord' AND oauth_sub='$sub' LIMIT 1");
    if ($found && $found->num_rows) {
        $uid = (int)$found->fetch_assoc()['id'];
        setcookie('id', $uid, time()+3600, '/');
        header('Location: /index.php');
    }

    if ($email) {
        $sel = $conn->query("SELECT id FROM users WHERE email='$email' LIMIT 1");
        if ($sel && $sel->num_rows) {
            $uid = (int)$sel->fetch_assoc()['id'];
            $conn->query("UPDATE users SET oauth_provider='discord', oauth_sub='$sub', profile_picture='$avatar' WHERE id=$uid");
            setcookie('id', $uid, time()+3600, '/');
            header('Location: /index.php');
        }
    }

    $username = substr(preg_replace('/[^a-z0-9_]/i','', strtolower($display ?: $usernameD ?: 'user')),0,20);
    $regdate = date('Y-m-d H:i:s');
    $conn->query("
          INSERT INTO users (username, email, firstname, lastname, password, oauth_provider, oauth_sub, email_verified, profile_picture, registration_date)
          VALUES ('$username', '$email', '$display', '', '', 'discord', '$sub', 1, '$avatar', '$regdate')
        ");
    $uid = (int)$conn->insert_id;

    setcookie('id', $uid, time()+3600, '/');
    header('Location: /index.php');
