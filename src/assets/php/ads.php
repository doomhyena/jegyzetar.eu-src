<?php
    $ads_mappa = __DIR__ . "/../ads";
    $kepek = glob($ads_mappa . "/*.{jpg,jpeg,png,webp}", GLOB_BRACE);
    $baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

    if ($baseUrl === '') $baseUrl = '/';
    $ads_url = $baseUrl . "/assets/ads";

    if ($kepek && count($kepek) > 0) {

        shuffle($kepek);
        $bal = $kepek[0];

        shuffle($kepek);
        $jobb = $kepek[0];

        shuffle($kepek);
        $mobil = $kepek[0];

        echo '<div class="ads-left"><img src="'.$ads_url.'/'.basename($bal).'" alt="Reklám"></div>';
        echo '<div class="ads-mobile"><img src="'.$ads_url.'/'.basename($mobil).'" alt="Reklám"></div>';
    }