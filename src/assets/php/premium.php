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

    function premium_badge_add($conn, $felhasznalo_id) {
        $felhasznalo_id = (int)$felhasznalo_id;

        $badgeRes = $conn->query("SELECT id FROM badges WHERE slug = 'premium' LIMIT 1");
        if (!$badgeRes || $badgeRes->num_rows == 0) return;

        $badgeRow = $badgeRes->fetch_assoc();
        $badge_id = (int)($badgeRow['id'] ?? 0);
        if ($badge_id <= 0) return;

        $exists = $conn->query("SELECT id FROM user_badges WHERE user_id = $felhasznalo_id AND badge_id = $badge_id LIMIT 1");
        if ($exists && $exists->num_rows > 0) return;

        $conn->query("INSERT INTO user_badges (user_id, badge_id, granted_by) VALUES ($felhasznalo_id, $badge_id, NULL)");
    }

    function premium_aktivalas_30nap($conn, $felhasznalo_id) {
        $felhasznalo_id = (int)$felhasznalo_id;

        $van = $conn->query("SELECT id FROM premium_users WHERE user_id = $felhasznalo_id LIMIT 1");

        if ($van && $van->num_rows > 0) {
            $ok = $conn->query("
                UPDATE premium_users
                SET premium_ig = DATE_ADD(NOW(), INTERVAL 30 DAY)
                WHERE user_id = $felhasznalo_id
            ");
        } else {
            $ok = $conn->query("
                INSERT INTO premium_users (user_id, premium_ig, created_at)
                VALUES ($felhasznalo_id, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW())
            ");
        }

        if (!$ok) return false;

        premium_badge_add($conn, $felhasznalo_id);

        return true;
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