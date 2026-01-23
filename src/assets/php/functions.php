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

    function get_client_ip(): ?string {
        $remote = $_SERVER['REMOTE_ADDR'] ?? null;

        if (!$remote) return null;

        $trustedProxies = ['127.0.0.1', '::1'];
        if (!in_array($remote, $trustedProxies, true)) {
            return $remote;
        }

        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }

        if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        }

        return $remote;
    }

    if (!function_exists('log_file_event')) {
        function log_file_event(mysqli $conn, int $fileId, ?int $userId, string $type, ?int $rating = null): void
        {
            $ip = get_client_ip();
            $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
            $cooldownSeconds = 600;
            $allowed = ['view','download','favorite_add','favorite_remove','rate','comment','report'];

            if (!in_array($type, $allowed, true)) {
                $type = 'view';
            }

            if ($userId !== null) {
                $recent = db_query($conn, "SELECT id FROM file_events WHERE file_id=? AND user_id=? AND event_type=? AND created_at > (NOW() - INTERVAL ? SECOND) LIMIT 1",  "iisi",  [$fileId, $userId, $type, $cooldownSeconds]);
                if ($recent && $recent->num_rows > 0) return;

                db_exec($conn, "INSERT INTO file_events (file_id, user_id, event_type, rating, ip, user_agent) VALUES (?, ?, ?, ?, " . ($ip ? "INET6_ATON(?)" : "NULL") . ", ?)",  $ip ? "iisis" . "s" : "iisis",  $ip ? [$fileId, $userId, $type, $rating, $ip, $ua] : [$fileId, $userId, $type, $rating, $ua]);
                return;
            }

            if ($ip) {
                $recent = db_query($conn, "SELECT id FROM file_events WHERE file_id=? AND user_id IS NULL AND event_type=? AND ip=INET6_ATON(?) AND created_at > (NOW() - INTERVAL ? SECOND) LIMIT 1",  "issi",  [$fileId, $type, $ip, $cooldownSeconds]);
                if ($recent && $recent->num_rows > 0) return;

                db_exec($conn, "INSERT INTO file_events (file_id, user_id, event_type, rating, ip, user_agent) VALUES (?, NULL, ?, ?, " . ($ip ? "INET6_ATON(?)" : "NULL") . ", ?)",  $ip ? "isis" . "s" : "isis",  $ip ? [$fileId, $type, $rating, $ip, $ua] : [$fileId, $type, $rating, $ua]);
                return;
            }
            if ($recent && $recent->num_rows > 0) return;
            db_exec($conn, "INSERT INTO file_events (file_id, user_id, event_type, rating, ip, user_agent) VALUES (?, NULL, ?, ?, " . ($ip ? "INET6_ATON(?)" : "NULL") . ", ?)",  $ip ? "isis" . "s" : "isis",  $ip ? [$fileId, $type, $rating, $ip, $ua] : [$fileId, $type, $rating, $ua]);
        }
    }
    function anonymize_ip(?string $ip): string {
        if (!$ip || $ip === '—') return '—';

        if (strpos($ip, '.') !== false) {
            $parts = explode('.', $ip);
            if (count($parts) === 4) {
                return $parts[0] . '.' . $parts[1] . '.xxx.xxx';
            }
            return $ip;
        }

        if (strpos($ip, ':') !== false) {
            $chunks = explode(':', $ip);
            $chunks = array_values(array_filter($chunks, fn($c)=>$c!=='')); // compressált címeknél
            $head = array_slice($chunks, 0, 3);
            return implode(':', $head) . ':xxxx:xxxx:xxxx:xxxx:xxxx';
        }

        return $ip;
    }

    function fmt_event_label(string $type): array {
        return match($type) {
            'view' => ['👁', 'Megtekintés'],
            'download' => ['⬇', 'Letöltés'],
            'favorite_add' => ['⭐', 'Kedvenc hozzáadva'],
            'favorite_remove' => ['✖', 'Kedvenc levéve'],
            'rate' => ['★', 'Értékelés'],
            'comment' => ['💬', 'Komment'],
            'report' => ['⚑', 'Jelentés'],
            default => ['•', $type]
        };
    }

    function strip_accents(string $s): string {
        $s = mb_strtolower($s, 'UTF-8');
        $t = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
        return $t !== false ? $t : $s;
    }

    function tokenize_query(string $q): array {
        $q = trim($q);
        if ($q === '') return [];
        $q = preg_replace('/\s+/u', ' ', $q) ?? $q;
        $raw = explode(' ', $q);
        $stop = [
            'a','az','és','meg','vagy','hogy','de','ha','is','nem','mint','mert','am','are','is','the','and','or','to','of','in','on','for','with'
        ];
        $out = [];
        foreach ($raw as $tok) {
            $tok = trim($tok);
            if ($tok === '') continue;
            if (mb_strlen($tok, 'UTF-8') < 2) continue;
            $t = strip_accents($tok);
            if (in_array($t, $stop, true)) continue;
            $out[] = $tok;
        }
        $seen = [];
        $uniq = [];
        foreach ($out as $t) {
            $k = strip_accents($t);
            if (isset($seen[$k])) continue;
            $seen[$k] = true;
            $uniq[] = $t;
        }
        return $uniq;
    }

    function build_snippet(string $text, array $needles, int $radius = 90): string {
        $text = trim($text);
        if ($text === '') return '';
        $hay = strip_accents($text);
        $bestPos = null;
        foreach ($needles as $n) {
            $n = trim($n);
            if ($n === '') continue;
            $pos = mb_stripos($hay, strip_accents($n), 0, 'UTF-8');
            if ($pos !== false) { $bestPos = $pos; break; }
        }
        $len = mb_strlen($text, 'UTF-8');
        if ($bestPos === null) {
            $cut = mb_substr($text, 0, min($len, 200), 'UTF-8');
            return ($len > 200) ? ($cut . '…') : $cut;
        }
        $start = max(0, $bestPos - $radius);
        $end = min($len, $bestPos + $radius + 120);
        $snippet = mb_substr($text, $start, $end - $start, 'UTF-8');
        if ($start > 0) $snippet = '…' . $snippet;
        if ($end < $len) $snippet = $snippet . '…';
        return $snippet;
    }

    function highlight_many_html(string $text, array $needles): string {
        $safe = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        if (!$needles) return $safe;
        usort($needles, fn($a,$b)=> mb_strlen($b,'UTF-8') <=> mb_strlen($a,'UTF-8'));
        foreach ($needles as $needle) {
            $needle = trim($needle);
            if ($needle === '') continue;
            $nSafe = htmlspecialchars($needle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $pattern = '/' . preg_quote($nSafe, '/') . '/iu';
            $safe = preg_replace($pattern, '<mark class="bg-pink-400/20 px-1 rounded">$0</mark>', $safe) ?? $safe;
        }
        return $safe;
    }