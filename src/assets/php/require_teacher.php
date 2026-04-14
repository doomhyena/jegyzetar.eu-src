<?php

require_login();

$user = auth_user($conn);
$is_admin = isset($user['admin']) && (int)$user['admin'] === 1;
$is_teacher = isset($user['teacher']) && (int)$user['teacher'] === 1;

// Ha admin vagy tanár átenged. Egyébként tilt.
if (!$is_admin && !$is_teacher) {
    http_response_code(403);
    echo "<script>alert('Csak tanár vagy admin tölthet fel a főoldalra!');
	window.location.href = 'index.php';
	</script>";
    exit;
}