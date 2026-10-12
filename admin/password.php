<?php
declare(strict_types=1);
require dirname(__DIR__) . '/includes/bootstrap.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/layout.php';
admin_require_login();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!admin_posted()) {
        admin_flash('error', 'انتهت صلاحية الجلسة.');
    } else {
        $current = (string) ($_POST['current'] ?? '');
        $next = (string) ($_POST['next'] ?? '');
        $admin = load_admin_user();
        if ($admin === null || !password_verify($current, $admin['password_hash'])) {
            admin_flash('error', 'كلمة المرور الحالية غير صحيحة.');
        } elseif (strlen($next) < 8) {
            admin_flash('error', 'كلمة المرور الجديدة لازم تكون 8 حروف على الأقل.');
        } else {
            $admin['password_hash'] = password_hash($next, PASSWORD_DEFAULT);
            $saved = save_admin_user($admin);
            admin_flash($saved ? 'ok' : 'error', $saved ? 'تم تغيير كلمة المرور.' : 'تعذر الحفظ.');
        }
    }
    header('Location: password.php');
    exit;
}

$token = admin_csrf_token();
admin_header('كلمة المرور');
?>
<form method="post" class="panel" style="max-width:460px">
  <input type="hidden" name="_token" value="<?= e($token) ?>">
  <div class="field"><label>كلمة المرور الحالية</label><input type="password" name="current" required></div>
  <div class="field"><label>كلمة المرور الجديدة</label><input type="password" name="next" required minlength="8"></div>
  <button class="btn" type="submit">تغيير</button>
</form>
<?php admin_footer();