<?php
    require_once 'assets/php/db.php';
    require_once 'assets/php/functions.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: search.php');
        exit;
    }

    require_login();

    $uid    = (int)auth_user_id();
    $action = $_POST['action'] ?? '';
    $back   = $_POST['back'] ?? 'search.php';

    if (!preg_match('/^[a-zA-Z0-9_\-\.?=&%]+$/', $back)) {
        $back = 'search.php';
    }

    if ($action === 'save') {
        $defaults = [
            'q' => '',
            'scope' => 'all',
            'type' => 'all',
            'level' => 'all',
            'tag' => '',
            'mode' => 'all',
            'sort' => 'newest',
        ];

        $params = [];
        foreach (array_keys($defaults) as $key) {
            $val = trim((string)($_POST[$key] ?? ''));
            if ($val !== $defaults[$key]) {
                $params[$key] = $val;
            }
        }

        if (!empty($params)) {
            $json = json_encode($params, JSON_UNESCAPED_UNICODE);

            $dup = db_query($conn,
                "SELECT id FROM saved_searches WHERE user_id = ? AND params_json = ? LIMIT 1",
                "is", [$uid, $json]
            );

            if ($dup && $dup->num_rows > 0) {
                $dupRow = $dup->fetch_assoc();
                db_exec($conn, "UPDATE saved_searches SET last_seen_at = NOW() WHERE id = ?", "i", [(int)$dupRow['id']]);
                $sep = strpos($back, '?') !== false ? '&' : '?';
                header('Location: ' . $back . $sep . 'ss=dup');
                exit;
            }

            $cntRes = db_query($conn, "SELECT COUNT(*) AS c FROM saved_searches WHERE user_id = ?", "i", [$uid]);
            $cnt = ($cntRes && $cntRes->num_rows) ? (int)($cntRes->fetch_assoc()['c'] ?? 0) : 0;

            if ($cnt >= 20) {
                $oldest = db_query($conn, "SELECT id FROM saved_searches WHERE user_id = ? ORDER BY created_at ASC LIMIT 1", "i", [$uid]);
                if ($oldest && $oldest->num_rows > 0) {
                    $oldId = (int)$oldest->fetch_assoc()['id'];
                    db_exec($conn, "DELETE FROM saved_searches WHERE id = ? LIMIT 1", "i", [$oldId]);
                }
            }

            db_exec($conn, "INSERT INTO saved_searches (user_id, params_json, created_at) VALUES (?, ?, NOW())", "is", [$uid, $json]);
            $sep = strpos($back, '?') !== false ? '&' : '?';
            header('Location: ' . $back . $sep . 'ss=ok');
            exit;
        }

        $sep = strpos($back, '?') !== false ? '&' : '?';
        header('Location: ' . $back . $sep . 'ss=empty');
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            db_exec($conn, "DELETE FROM saved_searches WHERE id = ? AND user_id = ? LIMIT 1", "ii", [$id, $uid]);
        }
        header('Location: ' . $back);
        exit;
    }

    header('Location: search.php');
    exit;