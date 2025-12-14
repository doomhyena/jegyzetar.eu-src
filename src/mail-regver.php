<?php
	// norbi: mail-regver
	require "assets/php/db.php";
	require_once "assets/php/functions.php";

	session_start();

	if (!isset($_SESSION['ver_id'])) {
		header("Location: reglog.php");
		exit;
	}

	$userId = (int)$_SESSION['ver_id'];

	$token = random_int(100000, 999999);

	$kinek = $_SESSION['email'];
	$targy = "Regisztráció visszaigazolás";
	$uzenet = '
		<h3>Üdv,</h3>
		<a href="localhost/jegyzetar.eu-src/src/reg-ver.php?token='.$token.'">Regisztráció visszaigazolás</a>
		<p>Üdvözlettel,</p>
		<p>Jegyzetár csapata</p>
	';

	$fejlec  = "From: Jegyzetar <noreply@jegyzetar.eu>" . "\r\n";
	$fejlec .= "MIME-Version: 1.0\r\n";
	$fejlec .= "Content-type: text/html; charset=UTF-8" . "\r\n";

	if (mail($kinek, $targy, $uzenet, $fejlec)) {
		$conn->query("INSERT INTO tokens VALUES(NULL, $userId, $token)");
		header("Location: reglog.php");
		exit;
	} else {
		session_destroy();
		header("Location: reglog.php");
		exit;
	}
