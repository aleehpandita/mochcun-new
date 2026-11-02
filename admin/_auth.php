<?php
declare(strict_types=1);

if (!defined('ROOT')) {
    require dirname(__DIR__) . '/app/bootstrap.php';
}

function admin_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $https = (($_SERVER['HTTPS'] ?? '') !== '' && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_name('mochcun_admin');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/** Usuario con sesión iniciada, o null. Solo abre sesión si ya existe la cookie. */
function admin_user(): ?array
{
    static $user = false;
    if ($user !== false) {
        return $user;
    }
    if (!isset($_COOKIE['mochcun_admin']) && session_status() !== PHP_SESSION_ACTIVE) {
        return $user = null;
    }
    admin_session();
    $id = (int) ($_SESSION['uid'] ?? 0);
    if (!$id || ($_SESSION['exp'] ?? 0) < time()) {
        return $user = null;
    }
    $_SESSION['exp'] = time() + 60 * 60 * 8;
    $st = db()->prepare('SELECT id, name, email FROM users WHERE id = ?');
    $st->execute([$id]);
    return $user = ($st->fetch() ?: null);
}

function require_login(): array
{
    header('X-Robots-Tag: noindex, nofollow');
    header('Cache-Control: no-store');
    $u = admin_user();
    if (!$u) {
        redirect('/admin/');
    }
    return $u;
}

function csrf_token(): string
{
    admin_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    admin_session();
    $sent = (string) ($_POST['csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $sent)) {
        http_response_code(419);
        exit('La sesión expiró. Regresa, recarga la página e intenta de nuevo.');
    }
}

function login_blocked(string $ip): bool
{
    db()->prepare('DELETE FROM login_attempts WHERE attempted_at < ?')->execute([time() - 900]);
    $st = db()->prepare('SELECT COUNT(*) FROM login_attempts WHERE ip = ?');
    $st->execute([$ip]);
    return (int) $st->fetchColumn() >= 8;
}

function attempt_login(string $email, string $password): bool
{
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    if (login_blocked($ip)) {
        return false;
    }
    $st = db()->prepare('SELECT * FROM users WHERE email = ?');
    $st->execute([mb_strtolower(trim($email))]);
    $u = $st->fetch();
    if (!$u || !password_verify($password, $u['password_hash'])) {
        db()->prepare('INSERT INTO login_attempts (ip, attempted_at) VALUES (?, ?)')->execute([$ip, time()]);
        usleep(400000);
        return false;
    }
    if (password_needs_rehash($u['password_hash'], PASSWORD_DEFAULT)) {
        db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([password_hash($password, PASSWORD_DEFAULT), $u['id']]);
    }
    db()->prepare('DELETE FROM login_attempts WHERE ip = ?')->execute([$ip]);
    db()->prepare('UPDATE users SET last_login_at = ? WHERE id = ?')->execute([now(), $u['id']]);
    admin_session();
    session_regenerate_id(true);
    $_SESSION['uid'] = (int) $u['id'];
    $_SESSION['exp'] = time() + 60 * 60 * 8;
    return true;
}

function flash(?string $msg = null, string $type = 'ok'): ?array
{
    admin_session();
    if ($msg !== null) {
        $_SESSION['flash'] = [$type, $msg];
        return null;
    }
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}
