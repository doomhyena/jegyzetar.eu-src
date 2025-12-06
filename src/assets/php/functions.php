<?php

    if (!function_exists('db_log_error')) {
        function db_log_error(mysqli $conn, string $message, ?string $sql = null, array $params = []): void
        {
            $logLine = sprintf(
                "[%s] %s | SQL: %s | PARAMS: %s | MYSQL: (%d) %s\n",
                date('Y-m-d H:i:s'),
                $message,
                $sql ?? '-',
                $params ? json_encode($params, JSON_UNESCAPED_UNICODE) : '[]',
                $conn->errno,
                $conn->error
            );

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