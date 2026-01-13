<?php
require_once __DIR__ . '/db.php';

$keresett = isset($_GET['keresett']) ? trim($_GET['keresett']) : '';

if ($keresett === '') {
    exit;
}

$lekerdezes = "SELECT * FROM tags WHERE tags LIKE '%{$keresett}%'";
$talalt_sorok = $conn->query($lekerdezes);

while ($sor = $talalt_sorok->fetch_assoc()) {
    $name = $sor['tags'];
    echo "<div class=\"tag\" data-tag=\"$name\">$name</div>\n";
}

