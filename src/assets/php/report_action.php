<?php

    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_login();
    require_once "db.php";
    require_once "lang.php";
    require_once "functions.php";

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php");
        exit;
    }

    $reporterId = (int)auth_user_id();
    $type = trim($_POST['type'] ?? '');
    $targetId = isset($_POST['target_id']) ? (int)$_POST['target_id'] : 0;
    $reason = trim($_POST['reason'] ?? '');
    $redirect = $_POST['redirect'] ?? 'index.php';

    $allowedPrefixes = ['note.php', 'profile.php', 'group.php', 'index.php'];
    $safeRedirect = 'index.php';
    foreach ($allowedPrefixes as $prefix) {
        if (str_starts_with(ltrim($redirect, '/'), $prefix)) {
            $safeRedirect = htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8');
            break;
        }
    }

    $allowedTypes = ['user', 'note', 'group'];
    if (!in_array($type, $allowedTypes, true) || $targetId <= 0) {
        header("Location: " . $safeRedirect . (str_contains($safeRedirect, '?') ? '&' : '?') . 'report_status=invalid');
        exit;
    }

    $existing = db_query(
        $conn,
        "SELECT id FROM reports WHERE reporter_id = ? AND target_type = ? AND target_id = ? AND status = 'open' LIMIT 1",
        "isi",
        [$reporterId, $type, $targetId]
    );
    if ($existing && $existing->num_rows > 0) {
        header("Location: " . $safeRedirect . (str_contains($safeRedirect, '?') ? '&' : '?') . 'report_status=already_reported');
        exit;
    }

    try {
        db_exec(
            $conn,
            "INSERT INTO reports (reporter_id, target_type, target_id, reason, status, created_at)
            VALUES (?, ?, ?, ?, 'open', NOW())",
            "isis",
            [
                $reporterId,
                $type,
                $targetId,
                ($reason !== '' ? $reason : null),
            ]
        );
        header("Location: " . $safeRedirect . (str_contains($safeRedirect, '?') ? '&' : '?') . 'report_status=sent');
    } catch (Throwable $e) {
        header("Location: " . $safeRedirect . (str_contains($safeRedirect, '?') ? '&' : '?') . 'report_status=error');
    }
    exit;
    