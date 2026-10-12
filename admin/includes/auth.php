<?php
declare(strict_types=1);

function admin_boot_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_name('neon_admin');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

function admin_csrf_token(): string
{
    admin_boot_session();
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function admin_verify_csrf(?string $token): bool
{
    admin_boot_session();
    $session = $_SESSION['csrf_token'] ?? '';
    return is_string($session) && is_string($token) && hash_equals($session, $token);
}

function admin_is_logged_in(): bool
{
    admin_boot_session();
    return !empty($_SESSION['admin_logged_in']);
}

function admin_require_login(): void
{
    if (!admin_is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function admin_login(string $username, string $password): bool
{
    $username = trim($username);
    $admin = load_admin_user();
    if (
        $admin === null
        || !hash_equals($admin['username'], $username)
        || !password_verify($password, $admin['password_hash'])
    ) {
        return false;
    }
    admin_boot_session();
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $admin['username'];
    return true;
}

function admin_logout(): void
{
    admin_boot_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', (bool) $params['secure'], (bool) $params['httponly']);
    }
    session_destroy();
}

function admin_flash(string $type, string $message): void
{
    admin_boot_session();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function admin_get_flash(): ?array
{
    admin_boot_session();
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return [
        'type' => (string) ($flash['type'] ?? 'ok'),
        'message' => (string) ($flash['message'] ?? ''),
    ];
}

function admin_posted(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && admin_verify_csrf((string) ($_POST['_token'] ?? ''));
}
