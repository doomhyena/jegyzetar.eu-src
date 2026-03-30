<?php
    require "assets/php/db.php";

    session_start();

    $token = trim((string)($_GET['token'] ?? ''));
    if ($token === '' || !ctype_digit($token)) {
        header("Location: reglog.php");
        exit;
    }

    $tokenInt = (int)$token;

    $stmt = $conn->prepare("SELECT id, user_id FROM tokens WHERE token = ? LIMIT 1");
    if ($stmt === false) {
        error_log('reg-ver: prepare failed: ' . $conn->error);
        header("Location: reglog.php");
        exit;
    }

    $stmt->bind_param('i', $tokenInt);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($tokenId, $userId);
        $stmt->fetch();

        $update = $conn->prepare("UPDATE users SET email_verified = 1 WHERE id = ?");
        if ($update) {
            $update->bind_param('i', $userId);
            $update->execute();
            $update->close();
        }

        $delete = $conn->prepare("DELETE FROM tokens WHERE id = ?");
        if ($delete) {
            $delete->bind_param('i', $tokenId);
            $delete->execute();
            $delete->close();
        }

        $stmt->close();

        header("Location: reglog.php");
        exit;
    }

    $stmt->close();

    header("Location: reglog.php");
    exit;
