<?php
    session_start();

    require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $clientId  = $_ENV['DISCORD_CLIENT_ID'];
    $redirectUri = $_ENV['DISCORD_REDIRECT_URI'];
    $scope  = 'identify email';
    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth_state'] = $state;

    $params = [
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'response_type' => 'code',
        'scope' => $scope,
        'state' => $state,
        'prompt' => 'consent'
    ];

    $url = 'https://discord.com/api/oauth2/authorize?' . http_build_query($params);

    header('Location: ' . $url);
    exit;
