<?php
declare(strict_types=1);

function neon_root(): string
{
    return dirname(__DIR__);
}

function neon_data_dir(): string
{
    return neon_root() . '/data';
}

function neon_content_file(): string
{
    return neon_data_dir() . '/content.json';
}

function neon_admin_file(): string
{
    return neon_data_dir() . '/admin.json';
}

function neon_ensure_data_dir(): void
{
    $dir = neon_data_dir();
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $htaccess = $dir . '/.htaccess';
    if (!is_file($htaccess)) {
        file_put_contents($htaccess, "Require all denied\nDeny from all\n");
    }
    $uploads = neon_root() . '/assets/uploads';
    if (!is_dir($uploads)) {
        mkdir($uploads, 0755, true);
    }
}

function neon_default_works(): array
{
    global $COPY, $PROJECTS;
    $layout = [
        ['type' => 'events', 'col' => 'col-md-7', 'image' => 'assets/creative-01-hd.png', 'label' => 'label_event', 'alt' => 'work1_alt', 'title' => 'work1_title', 'meta' => 'work1_meta'],
        ['type' => 'marketing', 'col' => 'col-md-5', 'image' => 'assets/creative-02-hd.png', 'label' => 'label_brand', 'alt' => 'work2_alt', 'title' => 'work2_title', 'meta' => 'work2_meta'],
        ['type' => 'marketing', 'col' => 'col-md-5', 'image' => 'assets/creative-03-hd.png', 'label' => 'label_digital', 'alt' => 'work3_alt', 'title' => 'work3_title', 'meta' => 'work3_meta'],
        ['type' => 'events', 'col' => 'col-md-7', 'image' => 'assets/creative-04-hd.png', 'label' => 'label_live', 'alt' => 'work4_alt', 'title' => 'work4_title', 'meta' => 'work4_meta'],
    ];
    $works = [];
    foreach ($layout as $i => $item) {
        $works[] = [
            'type' => $item['type'],
            'col' => $item['col'],
            'image' => $item['image'],
            'title' => ['en' => $COPY['en'][$item['title']], 'ar' => $COPY['ar'][$item['title']]],
            'meta' => ['en' => $COPY['en'][$item['meta']], 'ar' => $COPY['ar'][$item['meta']]],
            'alt' => ['en' => $COPY['en'][$item['alt']], 'ar' => $COPY['ar'][$item['alt']]],
            'label' => ['en' => $COPY['en'][$item['label']], 'ar' => $COPY['ar'][$item['label']]],
            'category' => ['en' => $PROJECTS['en'][$i]['category'], 'ar' => $PROJECTS['ar'][$i]['category']],
            'description' => ['en' => $PROJECTS['en'][$i]['description'], 'ar' => $PROJECTS['ar'][$i]['description']],
            'tags' => [
                'en' => implode('|', $PROJECTS['en'][$i]['tags']),
                'ar' => implode('|', $PROJECTS['ar'][$i]['tags']),
            ],
        ];
    }
    return $works;
}

function neon_defaults(): array
{
    global $COPY, $EXPERTISE, $SLIDES, $CLIENTS;
    return [
        'settings' => [
            'portfolio_url' => 'https://canva.link/neon-portfolio',
            'call_number' => '',
            'whatsapp_number' => '',
            'logo' => 'assets/neon-logo.png',
            'hero_event' => 'assets/contact-entrance-hd.png',
            'hero_marketing' => 'assets/marketing-hero-full-hd.png',
            'about_image' => 'assets/brand-world.png',
            'cta_image' => 'assets/creative-04-hd.png',
        ],
        'stats' => [
            ['count' => 150, 'suffix' => '+'],
            ['count' => 50, 'suffix' => '+'],
            ['count' => 10, 'suffix' => '+'],
            ['count' => 100, 'suffix' => '%'],
        ],
        'copy' => $COPY,
        'slides' => $SLIDES,
        'expertise' => $EXPERTISE,
        'works' => neon_default_works(),
        'clients' => $CLIENTS,
    ];
}

function neon_doc(): array
{
    if (isset($GLOBALS['__neon_doc']) && is_array($GLOBALS['__neon_doc'])) {
        return $GLOBALS['__neon_doc'];
    }
    $defaults = neon_defaults();
    $doc = $defaults;
    if (is_file(neon_content_file())) {
        $raw = file_get_contents(neon_content_file());
        $saved = is_string($raw) ? json_decode($raw, true) : null;
        if (is_array($saved)) {
            foreach (['settings', 'copy', 'slides', 'expertise'] as $key) {
                if (isset($saved[$key]) && is_array($saved[$key])) {
                    $doc[$key] = array_replace_recursive($defaults[$key], $saved[$key]);
                }
            }
            foreach (['stats', 'works', 'clients'] as $key) {
                if (isset($saved[$key]) && is_array($saved[$key]) && $saved[$key] !== []) {
                    $doc[$key] = $saved[$key];
                }
            }
        }
    }
    $GLOBALS['__neon_doc'] = $doc;
    return $doc;
}

function neon_save(array $doc): bool
{
    neon_ensure_data_dir();
    $json = json_encode($doc, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }
    return file_put_contents(neon_content_file(), $json . "\n", LOCK_EX) !== false;
}

function neon_commit(array $doc): bool
{
    $ok = neon_save($doc);
    if ($ok) {
        $GLOBALS['__neon_doc'] = $doc;
    }
    return $ok;
}

function neon_setting(string $key): string
{
    $settings = neon_doc()['settings'] ?? [];
    return (string) ($settings[$key] ?? '');
}

function neon_safe_url(string $url): string
{
    return preg_match('#^https?://#i', $url) === 1 ? $url : '#';
}

function neon_public_path(string $path): string
{
    $path = str_replace('\\', '/', trim($path));
    if ($path === '' || str_contains($path, '..') || str_contains($path, '://')) {
        return '';
    }
    if (!str_starts_with($path, 'assets/')) {
        return '';
    }
    return $path;
}

function neon_uploaded_file(string $key, int|string ...$path): array
{
    if (!isset($_FILES[$key]) || !is_array($_FILES[$key])) {
        return [];
    }
    $file = [];
    foreach (['name', 'type', 'tmp_name', 'error', 'size'] as $field) {
        $value = $_FILES[$key][$field] ?? null;
        foreach ($path as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return [];
            }
            $value = $value[$part];
        }
        $file[$field] = $value;
    }
    return $file;
}

function neon_asset_src(string $path): string
{
    $clean = neon_public_path($path);
    if ($clean !== '') {
        return $clean;
    }
    $path = str_replace('\\', '/', trim($path));
    if ($path !== '' && preg_match('#^[A-Za-z0-9._-]+$#', $path) && is_file(neon_root() . '/assets/' . $path)) {
        return 'assets/' . $path;
    }
    return '';
}

function neon_upload(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return null;
    }
    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        return null;
    }
    if (($file['size'] ?? 0) > 8 * 1024 * 1024) {
        return null;
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp) ?: '';
    $map = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    if (!isset($map[$mime])) {
        return null;
    }
    neon_ensure_data_dir();
    $name = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $map[$mime];
    $dest = neon_root() . '/assets/uploads/' . $name;
    if (!move_uploaded_file($tmp, $dest)) {
        return null;
    }
    return 'assets/uploads/' . $name;
}

function neon_plain(string $value): string
{
    return trim(strip_tags($value));
}

function neon_html(string $value): string
{
    return trim(strip_tags($value, '<br><em>'));
}

function neon_projects_for(string $langCode): array
{
    $out = [];
    foreach (neon_doc()['works'] as $work) {
        if (!is_array($work)) {
            continue;
        }
        $tags = (string) ($work['tags'][$langCode] ?? '');
        $out[] = [
            'title' => (string) ($work['title'][$langCode] ?? ''),
            'category' => (string) ($work['category'][$langCode] ?? ''),
            'image' => (string) ($work['image'] ?? ''),
            'description' => (string) ($work['description'][$langCode] ?? ''),
            'tags' => array_values(array_filter(array_map('trim', explode('|', $tags)), static fn ($tag) => $tag !== '')),
        ];
    }
    return $out;
}

function load_admin_user(): ?array
{
    neon_ensure_data_dir();
    if (!is_file(neon_admin_file())) {
        $created = [
            'username' => 'admin',
            'password_hash' => password_hash('Neon@2026', PASSWORD_DEFAULT),
        ];
        file_put_contents(neon_admin_file(), json_encode($created, JSON_PRETTY_PRINT) . "\n");
        return $created;
    }
    $data = json_decode((string) file_get_contents(neon_admin_file()), true);
    if (!is_array($data) || empty($data['username']) || empty($data['password_hash'])) {
        return null;
    }
    return [
        'username' => (string) $data['username'],
        'password_hash' => (string) $data['password_hash'],
    ];
}

function save_admin_user(array $admin): bool
{
    neon_ensure_data_dir();
    $json = json_encode([
        'username' => $admin['username'],
        'password_hash' => $admin['password_hash'],
    ], JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }
    return file_put_contents(neon_admin_file(), $json . "\n", LOCK_EX) !== false;
}
