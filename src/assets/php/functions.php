<?php

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    require_once __DIR__ . '/../vendor/autoload.php';

    if (!function_exists('auth_user_id')) {
        function auth_user_id(): ?int {
            if (!isset($_SESSION['id']) || !ctype_digit((string)$_SESSION['id'])) {
                return null;
            }
            return (int)$_SESSION['id'];
        }
    }

    if (!function_exists('auth_is_logged_in')) {
        function auth_is_logged_in(): bool {
            return auth_user_id() !== null;
        }
    }

    if (!function_exists('auth_user')) {
        function auth_user(mysqli $conn): ?array {
            $uid = auth_user_id();
            if (!$uid) {
                return null;
            }
            $res = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$uid]);
            if (!$res || $res->num_rows === 0) {
                return null;
            }
            return $res->fetch_assoc();
        }
    }

    if (!function_exists('require_login')) {
        function require_login(?string $redirectUrl = 'reglog.php'): void {
            if (!auth_is_logged_in()) {
                header('Location: ' . $redirectUrl);
                exit();
            }
        }
    }

    if (!function_exists('require_admin')) {
        function require_admin(mysqli $conn): void {
            $user = auth_user($conn);
            if (!$user || !isset($user['admin']) || (int)$user['admin'] !== 1) {
                http_response_code(403);
                exit('Hozzáférés megtagadva. Nincs admin jogosultság.');
            }
        }
    }

    if (!function_exists('auth_login_user')) {
        function auth_login_user(array $user): void {
            session_regenerate_id(true);
            $_SESSION['id'] = (int)$user['id'];
            $_SESSION['email'] = $user['email'] ?? '';
            $_SESSION['is_admin'] = isset($user['admin']) ? (int)$user['admin'] : 0;
            $_SESSION['is_teacher'] = isset($user['teacher']) ? (int)$user['teacher'] : 0;
        }
    }

    if (!function_exists('auth_logout')) {
        function auth_logout(): void {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params['path'], $params['domain'], $params['secure'], $params['httponly']
                );
            }
            session_destroy();
            setcookie('id', '', time() - 3600, '/');
        }
    }

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
        
            for($i = 0; $i < 5; $i++){
        
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
    
    if (!function_exists('get_client_ip')) {
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

    if (!function_exists('anonymize_ip')) {
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
                $chunks = array_values(array_filter($chunks, fn($c)=>$c!=='')); 
                $head = array_slice($chunks, 0, 3);
                return implode(':', $head) . ':xxxx:xxxx:xxxx:xxxx:xxxx';
            }

            return $ip;
        }
    }

    if (!function_exists('fmt_event_label')) {
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
    }
    if(!function_exists('strip_accents')) { 
        function strip_accents(string $s): string {
            $s = mb_strtolower($s, 'UTF-8');
            $t = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
            return $t !== false ? $t : $s;
        }
    }

    if (!function_exists('tokenize_query')) {
        function tokenize_query(string $q): array {
            $q = trim($q);
            if ($q === '') return [];
            $q = preg_replace('/\s+/u', ' ', $q) ?? $q;
            $raw = explode(' ', $q);

            $stop = [
                'a','az','és','meg','vagy','hogy','de','ha','is','nem','mint','mert',
                'am','are','is','the','and','or','to','of','in','on','for','with'
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
    }

    if (!function_exists('build_snippet')) {
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
            if ($end < $len) $snippet .= '…';

            return $snippet;
        }
    }

    if (!function_exists('highlight_many_html')) {
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
    }

    if (!function_exists('format_bytes')) {
        function format_bytes(int $bytes): string {
            if ($bytes < 1024) return $bytes . " B";
            $units = ['KB','MB','GB','TB'];
            $v = $bytes / 1024.0;
            $i = 0;
            while ($v >= 1024 && $i < count($units)-1) { $v /= 1024.0; $i++; }
            return rtrim(rtrim(number_format($v, 2, '.', ''), '0'), '.') . " " . $units[$i];
        }
    }


    if (!function_exists('fav_star_row')) {
        function fav_star_row(float $avg): string {
            $avg = max(0.0, min(5.0, $avg));
            $full = (int)floor($avg + 1e-9);
            $half = ($avg - $full) >= 0.5 ? 1 : 0;
            $empty = 5 - $full - $half;

            $out = '<span class="fav-stars" aria-label="Értékelés">';
            for ($i=0; $i<$full; $i++) $out .= '★';
            if ($half) $out .= '⯪';
            for ($i=0; $i<$empty; $i++) $out .= '☆';
            $out .= '</span>';
            return $out;
        }
    }

    if (!function_exists('fav_file_icon_svg')) {
        function fav_file_icon_svg(string $ext): string {
            $ext = strtolower(trim($ext));
        $icons = [
            'pdf'  => '<path d="M7 3h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 1v4h4"/>'
                . '<path d="M8 14h8M8 17h6"/>',
            'doc'  => '<path d="M7 3h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 1v4h4"/>'
                . '<path d="M8 13h8M8 16h8M8 19h6"/>',
            'docx' => '<path d="M7 3h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 1v4h4"/>'
                . '<path d="M8 13h8M8 16h8M8 19h6"/>',
            'ppt'  => '<path d="M7 3h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 1v4h4"/>'
                . '<path d="M9 14h6M9 17h4"/>'
                . '<path d="M12 12v8"/>',
            'pptx' => '<path d="M7 3h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 1v4h4"/>'
                . '<path d="M9 14h6M9 17h4"/>'
                . '<path d="M12 12v8"/>',
            'xls'  => '<path d="M7 3h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 1v4h4"/>'
                . '<path d="M8 12h8M8 15h8M8 18h8"/>'
                . '<path d="M10 12v8M14 12v8"/>',
            'xlsx' => '<path d="M7 3h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 1v4h4"/>'
                . '<path d="M8 12h8M8 15h8M8 18h8"/>'
                . '<path d="M10 12v8M14 12v8"/>',
            'mp4'  => '<path d="M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"/>'
                . '<path d="M11 10l4 2-4 2v-4z"/>',
            'zip'  => '<path d="M7 3h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 1v4h4"/>'
                . '<path d="M12 10v10"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>',
            'rar'  => '<path d="M7 3h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 1v4h4"/>'
                . '<path d="M12 10v10"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>',
            'png'  => '<path d="M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"/>'
                . '<path d="M8 15l3-3 3 3 2-2 3 3"/>'
                . '<path d="M9 10h.01"/>',
            'jpg'  => '<path d="M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"/>'
                . '<path d="M8 15l3-3 3 3 2-2 3 3"/>'
                . '<path d="M9 10h.01"/>',
            'jpeg' => '<path d="M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"/>'
                . '<path d="M8 15l3-3 3 3 2-2 3 3"/>'
                . '<path d="M9 10h.01"/>',
            'file' => '<path d="M7 3h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 1v4h4"/>'
                . '<path d="M8 14h8"/>'
        ];

        $path = $icons[$ext] ?? $icons['file'];
        return '<svg class="fav-file-ic" viewBox="0 0 24 24" aria-hidden="true">'
            . $path
            . '</svg>';
        }
    }

    if (!function_exists('rl_hit')) {
        function rl_hit(string $key): void {
            if (!isset($_SESSION[$key])) {
                $_SESSION[$key] = ['count' => 0, 'start' => time()];
            }
            $_SESSION[$key]['count'] = (int)$_SESSION[$key]['count'] + 1;
        }
    }


    if (!function_exists('rl_clear')) {
        function rl_clear(string $key): void {
            unset($_SESSION[$key]);
        }
    }

    if (!function_exists('valid_username')) {
        function valid_username(string $u): bool {
            return (bool)preg_match('/^[a-zA-Z0-9._-]{3,20}$/', $u);
        }
    }

    if (!function_exists('password_policy_ok')) {
        function password_policy_ok(string $p): bool {
            if (strlen($p) < 8) return false;
            if (!preg_match('/[a-z]/', $p)) return false;
            if (!preg_match('/[A-Z]/', $p)) return false;
            if (!preg_match('/[0-9]/', $p)) return false;
            return true;
        }
    }

    if (!function_exists('age_at_least_13')) {
        function age_at_least_13(string $birthdate): bool {
            $birth = DateTime::createFromFormat('Y-m-d', $birthdate);
            if (!$birth) return false;

            $errs = DateTime::getLastErrors();
            if (!empty($errs['warning_count']) || !empty($errs['error_count'])) return false;

            $today = new DateTime('today');
            if ($birth > $today) return false;

            return ($birth->diff($today)->y >= 13);
        }
    }

    if (!function_exists('rl_key')) {
        function rl_key(string $action, string $extra = ''): string {
            $ip = get_client_ip() ?? '0.0.0.0';
            return "rl:" . $action . ":" . $ip . ($extra !== '' ? ":" . $extra : '');
        }
    }

    if (!function_exists('rl_allow')) {
        function rl_allow(string $key, int $maxAttempts, int $windowSeconds): array {
            $now = time();
            if (!isset($_SESSION[$key])) {
                $_SESSION[$key] = ['count' => 0, 'start' => $now];
            }

            $bucket = $_SESSION[$key];
            $elapsed = $now - (int)$bucket['start'];

            if ($elapsed >= $windowSeconds) {
                $_SESSION[$key] = ['count' => 0, 'start' => $now];
                $bucket = $_SESSION[$key];
                $elapsed = 0;
            }

            if ((int)$bucket['count'] >= $maxAttempts) {
                return ['ok' => false, 'retry_after' => max(1, $windowSeconds - $elapsed)];
            }

            return ['ok' => true, 'retry_after' => 0];
        }
    }

    if (!function_exists('go')) {
        function go($url) {
            header("Location: " . $url);
            exit;
        }
    }

    if (!function_exists('is_https')) {
        function is_https(): bool {
            return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        }
    }

    if (!function_exists('client_ip')) {
        function client_ip(): string {
            return get_client_ip() ?? '0.0.0.0';
        }
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if (!function_exists('csrf_check')) {
        function csrf_check(): bool {
            return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
                && hash_equals($_SESSION['csrf_token'], (string)$_POST['csrf_token']);
        }
    }

    if (!function_exists('flash_set')) {
        function flash_set(string $type, string $text): void {
            $_SESSION['flash'] = ['type' => $type, 'text' => $text];
        }
    }

    if (!function_exists('flash_get')) {
        function flash_get(): ?array {
            if (empty($_SESSION['flash'])) return null;
            $f = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $f;
        }
    }

    if (!function_exists('flash_has')) {
        function flash_has(string $key): bool {
            return isset($_SESSION['flash'][$key]);
        }
    }

    if (!function_exists('base_url')) {
        function base_url(string $path = ''): string {
            $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); 
            $base = preg_replace('#/assets/php$#', '', $base);
            return $base . '/' . ltrim($path, '/');
        }
    }

    if (!function_exists('safe_nl2br')) {
        function safe_nl2br(string $text): string {
            return nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
        }
    }

    function render_markdown(string $md): string {
        $env = new \League\CommonMark\Environment\Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $env->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());

        $converter = new \League\CommonMark\CommonMarkConverter([], $env);
        return (string)$converter->convert($md);
    }

    if (!function_exists('safe_nl2br')) {
        function safe_nl2br(string $text): string {
            return nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
        }
    }

    if (!function_exists('mask_email')) {
        function mask_email(string $email): string {
            if (!str_contains($email, '@')) {
                return $email;
            }

            [$user, $domain] = explode('@', $email, 2);

            $maskedUser = str_repeat('*', mb_strlen($user, 'UTF-8'));

            return $maskedUser . '@' . $domain;
        }
    }

    if (!function_exists('youtube_embed_url')) {
        function youtube_embed_url($url) {
            if (preg_match('~youtu\.be/([A-Za-z0-9_-]{6,})~', $url, $m)) {
                return 'https://www.youtube-nocookie.com/embed/' . $m[1] . '?rel=0';
            } elseif (preg_match('~[?&]v=([A-Za-z0-9_-]{6,})~', $url, $m)) {
                return 'https://www.youtube-nocookie.com/embed/' . $m[1] . '?rel=0';
            } elseif (preg_match('~youtube\.com/shorts/([A-Za-z0-9_-]{6,})~', $url, $m)) {
                return 'https://www.youtube-nocookie.com/embed/' . $m[1] . '?rel=0';
            } elseif (preg_match('~youtube\.com/embed/([A-Za-z0-9_-]{6,})~', $url, $m)) {
                return 'https://www.youtube-nocookie.com/embed/' . $m[1] . '?rel=0';
            }
            return false;
        }
    }


    if (!function_exists('youtube_embed_url')) {
        function youtube_embed_url(string $url): ?string {
            $url = trim($url);
            if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) return null;

            $host = parse_url($url, PHP_URL_HOST) ?? '';
            $host = strtolower(preg_replace('/^www\./', '', $host));

            if ($host === 'youtu.be') {
                $id = trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
                return $id ? "https://www.youtube.com/embed/" . rawurlencode($id) : null;
            }

            if ($host === 'youtube.com' || $host === 'm.youtube.com' || $host === 'music.youtube.com') {
                parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $q);
                if (!empty($q['v'])) return "https://www.youtube.com/embed/" . rawurlencode($q['v']);

                $path = parse_url($url, PHP_URL_PATH) ?? '';
                if (preg_match('~^/embed/([^/?#]+)~', $path, $m)) {
                    return "https://www.youtube.com/embed/" . rawurlencode($m[1]);
                }
            }

            return null;
        }
    }

    if (!function_exists('generate_backup_codes')) {
        function generate_backup_codes(mysqli $conn, int $user_id, int $count = 10): array {
            $backup_codes = [];
            
            // Régi kódok törlése
            db_exec($conn, "DELETE FROM 2fa_backup_codes WHERE userid = ?", "i", [$user_id]);
            
            for ($i = 0; $i < $count; $i++) {
                // Generálunk egy 8 karakteres kódot (formátum: XXXX-XXXX)
                $code = strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4)));
                $code_hash = password_hash($code, PASSWORD_BCRYPT);
                
                db_exec($conn, "INSERT INTO 2fa_backup_codes (userid, code_hash) VALUES (?, ?)", "is", [$user_id, $code_hash]);
                $backup_codes[] = $code;
            }
            
            return $backup_codes;
        }
    }

    if (!function_exists('verify_backup_code')) {
        function verify_backup_code(mysqli $conn, int $user_id, string $code): bool {
            $code = trim(strtoupper($code));
            
            $result = db_query($conn, "SELECT id, code_hash FROM 2fa_backup_codes WHERE userid = ? AND used = 0", "i", [$user_id]);
            
            if (!$result || $result->num_rows === 0) {
                return false;
            }
            
            while ($row = $result->fetch_assoc()) {
                if (password_verify($code, $row['code_hash'])) {
                    db_exec($conn, "UPDATE 2fa_backup_codes SET used = 1, used_at = NOW() WHERE id = ?", "i", [$row['id']]);
                    return true;
                }
            }
            
            return false;
        }
    }

    if (!function_exists('get_unused_backup_codes_count')) {
        function get_unused_backup_codes_count(mysqli $conn, int $user_id): int {
            $result = db_query($conn, "SELECT COUNT(*) as cnt FROM 2fa_backup_codes WHERE userid = ? AND used = 0", "i", [$user_id]);
            
            if ($result && $result->num_rows > 0) {
                return (int)($result->fetch_assoc()['cnt'] ?? 0);
            }
            
            return 0;
        }
    }