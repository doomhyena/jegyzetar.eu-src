<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $supported_langs = ['hu', 'en', 'de'];

    if (isset($_GET['lang']) && in_array($_GET['lang'], $supported_langs, true)) {
        $lang = $_GET['lang'];
        $_SESSION['lang'] = $lang;
        if (!headers_sent()) {
            setcookie('lang', $lang, time() + 60*60*24*30, '/');
        }
    } else {
        if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $supported_langs, true)) {
            $lang = $_SESSION['lang'];
        } elseif (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $supported_langs, true)) {
            $lang = $_COOKIE['lang'];
        } else {
            $lang = 'hu';
        }
    }

    if (!in_array($lang, $supported_langs, true)) {
        $lang = 'hu';
    }

    $translations = [];

    if (!isset($conn)) {
        return;
    }

    if ($stmt = $conn->prepare("SELECT t_key, lang_code, text FROM translations WHERE lang_code IN (?, 'hu')")) {
        $stmt->bind_param('s', $lang);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            if (!isset($translations[$row['t_key']]) || $row['lang_code'] === $lang) {
                $translations[$row['t_key']] = $row['text'];
            }
        }

        $stmt->close();
    }

    if (!function_exists('t')) {
        function t(string $key, ...$args): string
        {
            global $translations;
            $text = $translations[$key] ?? $key;
            if (count($args) > 0) {
                return sprintf($text, ...$args);
            }
            return $text;
        }
    }
