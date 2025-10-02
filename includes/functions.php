<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$config = require dirname(__DIR__) . '/config.php';

function e(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf_or_die(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(400);
            exit('Invalid CSRF token');
        }
    }
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}

function ensure_upload_dir(): string {
    $dir = $GLOBALS['config']['site']['upload_dir'] ?? (require dirname(__DIR__) . '/config.php')['site']['upload_dir'];
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return $dir;
}

function sanitize_filename(string $name): string {
    $name = preg_replace('/[^A-Za-z0-9_\.-]/', '_', $name);
    return trim($name, '_');
}

function handle_upload(array $file, array $allowedTypes, int $maxBytes): array {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['ok' => false, 'error' => 'no_file'];
    }
    if (!is_uploaded_file($file['tmp_name'])) {
        return ['ok' => false, 'error' => 'invalid_upload'];
    }
    if ($file['size'] > $maxBytes) {
        return ['ok' => false, 'error' => 'too_large'];
    }
    
    // Check MIME type using mime_content_type (fallback if finfo not available)
    if (function_exists('mime_content_type')) {
        $mime = mime_content_type($file['tmp_name']);
    } elseif (class_exists('finfo')) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
    } else {
        // Fallback: check extension only
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
        if (!in_array($ext, $allowedExts)) {
            return ['ok' => false, 'error' => 'bad_type'];
        }
        $mime = 'image/' . ($ext === 'jpg' ? 'jpeg' : $ext);
    }
    
    if ($mime && !in_array($mime, $allowedTypes, true)) {
        return ['ok' => false, 'error' => 'bad_type'];
    }
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safe = sanitize_filename(pathinfo($file['name'], PATHINFO_FILENAME));
    $targetName = $safe . '-' . bin2hex(random_bytes(5)) . ($ext ? ".{$ext}" : '');
    $uploadDir = ensure_upload_dir();
    $targetPath = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $targetName;
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['ok' => false, 'error' => 'move_failed'];
    }
    return ['ok' => true, 'filename' => $targetName, 'path' => $targetPath];
}

function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
    $text = trim($text, '-');
    return $text ?: bin2hex(random_bytes(4));
}

function get_content(string $key, string $default = ''): string {
    static $cache = [];
    if (!isset($cache[$key])) {
        try {
            $stmt = db()->prepare('SELECT content_value FROM content_sections WHERE section_key = ? LIMIT 1');
            $stmt->execute([$key]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $cache[$key] = $row ? (string)$row['content_value'] : $default;
        } catch (Exception $e) {
            $cache[$key] = $default;
        }
    }
    return $cache[$key];
}

function send_email(string $to, string $subject, string $message, string $from = null): bool {
    global $config;
    
    $from = $from ?: $config['email']['from_email'];
    $fromName = $config['email']['from_name'];
    
    $headers = [
        'From: ' . $fromName . ' <' . $from . '>',
        'Reply-To: ' . $from,
        'X-Mailer: PHP/' . phpversion(),
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8'
    ];
    
    return @mail($to, $subject, $message, implode("\r\n", $headers));
}
