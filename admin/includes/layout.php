<?php
declare(strict_types=1);

function admin_nav(): array
{
    return [
        'index.php' => 'الرئيسية',
        'texts.php' => 'النصوص',
        'slides.php' => 'السلايدر',
        'services.php' => 'الخدمات',
        'works.php' => 'الأعمال',
        'clients.php' => 'العملاء',
        'settings.php' => 'الصور والإعدادات',
        'password.php' => 'كلمة المرور',
    ];
}

function admin_header(string $title): void
{
    $page = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $flash = admin_get_flash();
    ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title><?= e($title) ?> | نيون</title>
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<div class="shell">
  <aside class="side">
    <a class="brand" href="index.php">Neon</a>
    <nav>
      <?php foreach (admin_nav() as $href => $label): ?>
        <a class="<?= $page === $href ? 'active' : '' ?>" href="<?= e($href) ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>
    <a class="quiet" href="../index.php" target="_blank" rel="noopener">عرض الموقع</a>
    <a class="quiet" href="logout.php">خروج</a>
  </aside>
  <main class="main">
    <h1><?= e($title) ?></h1>
    <?php if ($flash): ?>
      <div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>
    <?php
}

function admin_footer(): void
{
    ?>
  </main>
</div>
</body>
</html>
    <?php
}

function admin_image_field(string $name, string $current): void
{
    $src = neon_asset_src($current);
    ?>
    <div class="image-field">
      <?php if ($src !== ''): ?><img src="../<?= e($src) ?>" alt=""><?php endif; ?>
      <input type="file" name="<?= e($name) ?>" accept="image/jpeg,image/png,image/webp,image/gif">
    </div>
    <?php
}
