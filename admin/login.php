<?php
declare(strict_types=1);
require dirname(__DIR__) . '/includes/bootstrap.php';
require __DIR__ . '/includes/auth.php';

admin_boot_session();
if (admin_is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!admin_verify_csrf((string) ($_POST['_token'] ?? ''))) {
        $error = 'انتهت صلاحية الجلسة. حاول مرة أخرى.';
    } else {
        usleep(250000);
        if (admin_login((string) ($_POST['username'] ?? ''), (string) ($_POST['password'] ?? ''))) {
            header('Location: index.php');
            exit;
        }
        $error = 'اسم المستخدم أو كلمة المرور غير صحيحة.';
    }
}
$token = admin_csrf_token();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>دخول لوحة نيون</title>
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
  <div class="login-wrap">
    <form class="login-card" method="post" autocomplete="off">
      <h1>لوحة نيون</h1>
      <p class="hint">تحكم في نصوص الموقع وصوره بالعربي والإنجليزي.</p>
      <?php if ($error !== ''): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
      <input type="hidden" name="_token" value="<?= e($token) ?>">
      <div class="field"><label for="username">اسم المستخدم</label><input id="username" name="username" required autofocus></div>
      <div class="field"><label for="password">كلمة المرور</label><input id="password" name="password" type="password" required></div>
      <button class="btn" type="submit">دخول</button>
    </form>
  </div>
</body>
</html>
