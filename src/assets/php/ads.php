<?php
    require_once __DIR__ . "/premium.php";

    $premium_van = false;

    if (auth_is_logged_in()) {
        $aktualis_felhasznalo_id = (int)auth_user_id();
        $premium_van = user_premium($conn, $aktualis_felhasznalo_id);
    }

    if ($premium_van) return;

    $ads_mappa = __DIR__ . "/../ads";

    if (!is_dir($ads_mappa)) return;

    $kepek = glob($ads_mappa . "/*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);

    if (!$kepek || count($kepek) === 0) return;

    $ads_url = "assets/ads";

    shuffle($kepek); $bal   = basename($kepek[0]);
    shuffle($kepek); $mobil = basename($kepek[0]);

    echo '<div class="ads-left"><img src="' . $ads_url . '/' . $bal   . '" alt="Reklám"></div>';
    echo '<div class="ads-mobile"><img src="' . $ads_url . '/' . $mobil . '" alt="Reklám"></div>';
