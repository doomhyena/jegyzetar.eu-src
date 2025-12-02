<?php

    if (!function_exists('db_prepared')) {
        function db_prepared(mysqli $conn, string $sql, string $types, array $params): mysqli_stmt {
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new RuntimeException('Előkészítési hiba (prepare): ' . $conn->error);
            }

            if (!$stmt->bind_param($types, ...$params)) {
                throw new RuntimeException('Paraméterek bind-elése sikertelen: ' . $stmt->error);
            }

            if (!$stmt->execute()) {
                throw new RuntimeException('Végrehajtási hiba (execute): ' . $stmt->error);
            }

            return $stmt;
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