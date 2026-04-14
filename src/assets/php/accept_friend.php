<?php
    require "db.php";

    if (!auth_is_logged_in() || !isset($_POST['fromid'])) {
        header("Location: ../../index.php");
        exit;
    }

    $myid   = (int)auth_user_id();
    $fromid = (int)$_POST['fromid'];

    $conn->query("UPDATE friends SET status = 1 WHERE fromid=$fromid AND toid=$myid");
    $user_res = $conn->query("SELECT username FROM users WHERE id=$fromid LIMIT 1");
    $from_username = ($user_res && $user_res->num_rows) ? $user_res->fetch_assoc()['username'] : '';

    if ($from_username) {
        header("Location: ../../profile.php?user=" . urlencode($from_username));
    } else {
        header("Location: ../../notify.php");
    }
    exit;
