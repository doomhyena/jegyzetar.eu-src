<?php

    if (!function_exists('db_log_error')) {
        function db_log_error(mysqli $conn, string $message, ?string $sql = null, array $params = []): void
        {
            $logLine = sprintf("[%s] %s | SQL: %s | PARAMS: %s | MYSQL: (%d) %s\n", date('Y-m-d H:i:s'), $message, $sql ?? '-', $params ? json_encode($params, JSON_UNESCAPED_UNICODE) : '[]', $conn->errno, $conn->error);

            error_log($logLine);

            $logFile = __DIR__ . '/../logs/db_errors.log';
            $dir = dirname($logFile);
            if (@is_dir($dir) || @mkdir($dir, 0775, true)) {
                @file_put_contents($logFile, $logLine, FILE_APPEND);
            }
        }
    }

    if (!function_exists('db_stmt')) {
        function db_stmt(mysqli $conn, string $sql, string $types = '', array $params = []): mysqli_stmt {
            if ($types !== '' && strlen($types) !== count($params)) {
                db_log_error(
                    $conn,
                    "db_stmt: types és param szám eltérés",
                    $sql,
                    ['types' => $types, 'params' => $params]
                );
                throw new InvalidArgumentException('A types hossza nem egyezik a paraméterek számával.');
            }

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                db_log_error($conn, 'Előkészítési hiba (prepare)', $sql, $params);
                throw new RuntimeException('Előkészítési hiba (prepare): ' . $conn->error);
            }

            if ($types !== '' && $params) {
                if (!$stmt->bind_param($types, ...$params)) {
                    db_log_error($conn, 'Paraméterek bind-elése sikertelen', $sql, $params);
                    throw new RuntimeException('Paraméterek bind-elése sikertelen: ' . $stmt->error);
                }
            }

            if (!$stmt->execute()) {
                db_log_error($conn, 'Végrehajtási hiba (execute)', $sql, $params);
                throw new RuntimeException('Végrehajtási hiba (execute): ' . $stmt->error);
            }

            return $stmt;
        }
    }

    if (!function_exists('db_query')) {

        function db_query(mysqli $conn, string $sql, string $types = '', array $params = []): mysqli_result
        {
            $stmt = db_stmt($conn, $sql, $types, $params);
            $result = $stmt->get_result();

            if ($result === false) {
                db_log_error($conn, 'db_query: get_result() false-t adott vissza', $sql, $params);
                throw new RuntimeException('db_query: get_result() nem adott vissza eredményt.');
            }

            return $result;
        }
    }

    if (!function_exists('db_exec')) {
        function db_exec(mysqli $conn, string $sql, string $types = '', array $params = []): bool
        {
            $stmt = db_stmt($conn, $sql, $types, $params);
            $stmt->close();
            return true;
        }
    }


    if (!function_exists('Message')) {
        function Message($text){
            echo "<script>alert('$text')</script>";
        }
    }

    if (!function_exists('CodeGenerator')) {
        function CodeGenerator(){
        
            $characters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
            $code = "";
        
            for($i=0; $i<5; $i++){
        
                $code .= $characters[rand(0, count($characters)-1)];
        
            }
        
            return $code;
        }
    }

    if (!function_exists('t')) {
        function t(string $key, string $fallback = ''): string {
            global $translations;
            if (!empty($translations) && array_key_exists($key, $translations)) {
                return (string)$translations[$key];
            }
            return $fallback !== '' ? $fallback : $key;
        }
    }

    if (!function_exists('log_file_event')) {
            function log_file_event($conn, int $fileId, ?int $userId, string $type, ?int $rating=null) {
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

                $cooldownSeconds = 600;
                $keyUserId = $userId ?? 0;

                if ($userId) {
                    $recent = db_query($conn, "SELECT id FROM file_events WHERE file_id=? AND user_id=? AND event_type=? AND created_at > (NOW() - INTERVAL ? SECOND) LIMIT 1",  "iisi", [$fileId, $userId, $type, $cooldownSeconds]);
                    if ($recent && $recent->num_rows > 0) return;
                } else if ($ip) {
                    $recent = db_query($conn, "SELECT id FROM file_events WHERE file_id=? AND user_id IS NULL AND event_type=? AND ip=INET6_ATON(?) AND created_at > (NOW() - INTERVAL ? SECOND) LIMIT 1",  "issi", [$fileId, $type, $ip, $cooldownSeconds]);
                    if ($recent && $recent->num_rows > 0) return;
                }
                db_exec($conn, "INSERT INTO file_events (file_id, user_id, event_type, rating, ip, user_agent)VALUES (?, ?, ?, ?, ".($ip ? "INET6_ATON(?)" : "NULL").", ?)",  $ip ? "iisis" : "iiiis", $ip ? [$fileId, $userId, $type, $rating, $ip, $ua] : [$fileId, $userId, $type, $rating, $ua]);
            }
        }