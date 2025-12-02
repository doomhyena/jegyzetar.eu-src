<?php

if (!isset($_COOKIE['id'])) {
    header("Location: reglog.php");
    exit;
}

$aktualis_felhasznalo_id = $_COOKIE['id'];

if (!isset($_GET['id'])) {
    exit("Nincs megadva csoport ID.");
}

$csoport_id = $_GET['id'];

$csoport_lekerdezes = $conn->query("SELECT * FROM groups WHERE id='$csoport_id'");
if (!$csoport_lekerdezes || $csoport_lekerdezes->num_rows == 0) {
    exit("Nincs ilyen csoport.");
}

$csoport_adat   = $csoport_lekerdezes->fetch_assoc();
$csoport_nev    = $csoport_adat['name'];
$csoport_leiras = $csoport_adat['description'];
$tulaj_id       = $csoport_adat['owner_id'];
$privat         = $csoport_adat['is_private'];

$aktualis_felhasznalo_tag     = false;
$aktualis_felhasznalo_tulaj   = false;
$aktualis_felhasznalo_pending = false;

$tagsag_lekerdezes = $conn->query("
    SELECT * 
    FROM group_members 
    WHERE group_id='$csoport_id' 
      AND user_id='$aktualis_felhasznalo_id'
");

if ($tagsag_lekerdezes && $tagsag_lekerdezes->num_rows > 0) {
    $tagsag_sor = $tagsag_lekerdezes->fetch_assoc();

    if ($tagsag_sor['status'] == 'accepted') {
        $aktualis_felhasznalo_tag = true;
    }
    if ($tagsag_sor['status'] == 'pending') {
        $aktualis_felhasznalo_pending = true;
    }
    if ($tagsag_sor['role'] == 'owner') {
        $aktualis_felhasznalo_tulaj = true;
    }
}

if ($aktualis_felhasznalo_id == $tulaj_id) {
    $aktualis_felhasznalo_tulaj = true;
    $aktualis_felhasznalo_tag   = true;
}

?>