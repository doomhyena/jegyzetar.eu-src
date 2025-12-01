<?php
//norbi: mail-2fa
	require "assets/php/db.php";
	
	session_start();

	if(!isset($_SESSION['id'])){
		header("Location: reglog.php");
	}
	
	$code = random_int(10000, 99999); // lehet másfajta kód is

	
	$kinek = $_SESSION['email'];
	$targy = "Bejelentkezési kódod: {$code}";
	$uzenet = '
	<h3>Üdv,</h3>
	<p>Láttuk megprobáltál bejelentkezi a fiókodba.</p>
	<p>Ha ez te voltál, akkor ird be a következő kódot a bejelentkezési oldalon:</p>
	<p>Kód: <mark style="background-color: lightblue;">' . $code . '</mark></p>
	<p><b>Ha nem te voltál, akkor mindenféleképpen változtasd meg a jelszavad!</b></p>
	<p>Üdvözlettel,</p>
	<p>Jegyzetár csapata</p>
	'; // átlehet irni akármire ha nem tetszik, rátok bizom
	
	$fejlec = "From: Jegyzetar <noreply@jegyzetar.eu>"."\r\n";
	$fejlec .= "MIME-Version: 1.0\r\n";
	$fejlec .= "Content-type: text/html; charset=UTF-8"."\r\n";
	
	if(mail($kinek, $targy, $uzenet, $fejlec)){
		//2fa_codes táblába menti el a kódokat
		$conn->query("INSERT INTO 2fa_codes VALUES(NULL, $_SESSION[id], '$code')");
		
		header("Location: 2fa.php");
		
	}
	else{
		echo "<script>alert('Hiba')</script>";
		session_destroy();
		header("Location: reglog.php");
		exit;
	}

?>