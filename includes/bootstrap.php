<?php
declare(strict_types=1);

$allowedLangs = ['en', 'ar'];

$requestedLang = $_GET['lang'] ?? null;
if (is_string($requestedLang) && in_array($requestedLang, $allowedLangs, true)) {
    setcookie('neon_lang', $requestedLang, [
        'expires' => time() + 60 * 60 * 24 * 365,
        'path' => '/',
        'samesite' => 'Lax',
    ]);
    $lang = $requestedLang;
} else {
    $lang = $_COOKIE['neon_lang'] ?? 'en';
    if (!in_array($lang, $allowedLangs, true)) {
        $lang = 'en';
    }
}

header('Cache-Control: private, no-store, max-age=0');
header('Vary: Cookie');

require __DIR__ . '/content.php';
require __DIR__ . '/store.php';

function current_lang(): string
{
    global $lang;
    return $lang;
}

function is_rtl(): bool
{
    return current_lang() === 'ar';
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function t(string $key): string
{
    return e(phrase($key));
}

function th(string $key): string
{
    return phrase($key);
}

function phrase(string $key): string
{
    global $COPY, $lang;
    $stored = neon_doc()['copy'][$lang][$key] ?? null;
    if (is_string($stored)) {
        return $stored;
    }
    return (string) ($COPY[$lang][$key] ?? $COPY['en'][$key] ?? $key);
}

function lang_href(string $code): string
{
    $uri = strtok((string) ($_SERVER['REQUEST_URI'] ?? '/'), '?');
    $path = ($uri !== false && $uri !== '') ? $uri : '/';
    $join = str_contains($path, '?') ? '&' : '?';
    return $path . $join . 'lang=' . $code;
}
