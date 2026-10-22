<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=UTF-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}

function brief_line(string $value, int $limit): string
{
    $value = trim(str_replace(["\r", "\n"], ' ', strip_tags($value)));
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $limit);
    }
    return substr($value, 0, $limit);
}

$name = brief_line((string) ($_POST['name'] ?? ''), 120);
$email = brief_line((string) ($_POST['email'] ?? ''), 180);
$service = brief_line((string) ($_POST['service'] ?? ''), 160);
$message = trim(strip_tags((string) ($_POST['message'] ?? '')));
if (function_exists('mb_substr')) {
    $message = mb_substr($message, 0, 4000);
} else {
    $message = substr($message, 0, 4000);
}

if ($name === '' || $message === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    http_response_code(422);
    echo json_encode(['ok' => false]);
    exit;
}

$to = 'info@neon-eg.com';
$subject = '=?UTF-8?B?' . base64_encode('Neon project brief — ' . $name) . '?=';
$body = "Name: {$name}\nEmail: {$email}\nService: {$service}\n\n{$message}\n";
$headers = implode("\r\n", [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
    'From: Neon Website <info@neon-eg.com>',
    'Reply-To: ' . $email,
]);

$sent = PHP_OS_FAMILY === 'Windows'
    ? mail($to, $subject, $body, $headers)
    : mail($to, $subject, $body, $headers, '-f info@neon-eg.com');

if (!$sent) {
    http_response_code(500);
    echo json_encode(['ok' => false]);
    exit;
}

echo json_encode(['ok' => true]);
