<?php

    require_once "db.php";
    require_once "functions.php";
    require_once "lang.php";

    session_start();

    $reporterId = null;

    if (isset($_COOKIE['id']) && ctype_digit($_COOKIE['id'])) {
        $reporterId = (int)$_COOKIE['id'];
    } elseif (isset($_SESSION['id']) && ctype_digit((string)$_SESSION['id'])) {
        $reporterId = (int)$_SESSION['id'];
    }

    if (!$reporterId) {
        header("Location: /reglog.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /index.php");
        exit;
    }

    $type = $_POST['type'] ?? '';
    $targetId = $_POST['target_id'] ?? '';
    $reason = trim($_POST['reason'] ?? '');
    $redirect = $_POST['redirect']  ?? '/index.php';

    $allowedTypes = ['user', 'group', 'note'];

    if (!in_array($type, $allowedTypes, true) || !ctype_digit((string)$targetId)) {
        header("Location: " . $redirect);
        exit;
    }

    $targetId = (int)$targetId;

    if ($reason === '') {
        $reason = 'Nincs megadott indok.';
    }

    $existing = db_query($conn, "SELECT id FROM reports WHERE reporter_id = ? AND target_type = ? AND target_id = ? AND status = 'open' LIMIT 1", "isi", [$reporterId, $type, $targetId]);

    if ($existing && $existing->num_rows > 0) {
        header("Location: " . $redirect);
        exit;
    }

    db_stmt($conn, "INSERT INTO reports (reporter_id, target_type, target_id, reason) VALUES (?, ?, ?, ?)", "isis", [$reporterId, $type, $targetId, $reason]);

    header("Location: " . $redirect);
    exit;
