<?php
    require "db.php";

    if (!auth_is_logged_in() || !isset($_POST['toid'])) {
        header("Location: ../../index.php");
        exit;
    }

    $fromid = (int)auth_user_id();
    $toid   = (int)$_POST['toid'];

    $user_check = $conn->query("SELECT username FROM users WHERE id = $toid LIMIT 1");
    if ($user_check->num_rows === 0) {
        die("Hiba: a felhasználó nem létezik.");
    }

    $to_user = $user_check->fetch_assoc();
    $to_username = $to_user['username'];

    $check = $conn->query("SELECT * FROM friends WHERE (fromid=$fromid AND toid=$toid) OR (fromid=$toid AND toid=$fromid)");
    if ($check->num_rows === 0) {
        $conn->query("INSERT INTO friends (fromid, toid, status) VALUES ($fromid, $toid, 0)");
        $conn->query("INSERT INTO notifys (fromid, toid, notifytype, readed) VALUES ($fromid, $toid, 'friend', 0)");
    }

    header("Location: ../../profile.php?user=" . urlencode($to_username));
    exit;