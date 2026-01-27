<?php

    function premium_aktiv($premium_ig) {
        if ($premium_ig == "" || !$premium_ig) return false;
        return strtotime($premium_ig) >= time();
    }

    function user_premium($conn, $felhasznalo_id) {
        $felhasznalo_id = (int)$felhasznalo_id;

        $premium_lekerdezes = $conn->query("
            SELECT MAX(premium_ig) AS premium_ig
            FROM premium_users
            WHERE user_id = $felhasznalo_id
        ");

        if ($premium_lekerdezes && $premium_lekerdezes->num_rows > 0) {
            $premium_sor = $premium_lekerdezes->fetch_assoc();
            return premium_aktiv($premium_sor['premium_ig']);
        }

        return false;
    }

    function premium_aktivalas_30nap($conn, $felhasznalo_id) {
        $felhasznalo_id = (int)$felhasznalo_id;

        $van = $conn->query("SELECT id FROM premium_users WHERE user_id = $felhasznalo_id LIMIT 1");

        if ($van && $van->num_rows > 0) {
            return $conn->query("
                UPDATE premium_users
                SET premium_ig = DATE_ADD(NOW(), INTERVAL 30 DAY)
                WHERE user_id = $felhasznalo_id
            ");
        } else {
            return $conn->query("
                INSERT INTO premium_users (user_id, premium_ig, created_at)
                VALUES ($felhasznalo_id, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW())
            ");
        }
    }

    function premium_datum($conn, $felhasznalo_id) {
        $felhasznalo_id = (int)$felhasznalo_id;

        $q = $conn->query("
            SELECT MAX(premium_ig) AS premium_ig
            FROM premium_users
            WHERE user_id = $felhasznalo_id
        ");

        if ($q && $q->num_rows > 0) {
            $r = $q->fetch_assoc();
            return $r['premium_ig'] ?? "";
        }
        return "";
    }