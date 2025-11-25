<?php
    $supported_langs = ['hu', 'en', 'de'];

    if (isset($_GET['lang']) && in_array($_GET['lang'], $supported_langs, true)) {
        $_SESSION['lang'] = $_GET['lang'];
        if (!headers_sent()) {
            setcookie('lang', $_GET['lang'], time() + 60*60*24*30, '/');
        }
    }

    $lang = $_SESSION['lang'] ?? ($_COOKIE['lang'] ?? 'hu');
    if (!in_array($lang, $supported_langs, true)) {
        $lang = 'hu';
    }

    if (isset($user['language']) && in_array($user['language'], $supported_langs, true)) {
        $lang = $user['language'];
    }

    $translations = [];

    $sql = $conn->query(
        "SELECT t_key, text FROM translations WHERE lang_code = '" .
        $conn->real_escape_string($lang) . "'"
    );
    while ($row = $sql->fetch_assoc()) {
        $translations[$row['t_key']] = $row['text'];
    }

    if (!function_exists('t')) {
        function t(string $key, string $fallback = ''): string {
            global $translations;
            if (isset($translations[$key])) {
                return $translations[$key];
            }
            return $fallback !== '' ? $fallback : $key;
        }
    }
