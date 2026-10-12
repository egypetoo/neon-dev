<?php
declare(strict_types=1);
require dirname(__DIR__) . '/includes/bootstrap.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/layout.php';
admin_require_login();

$imageKeys = [
    'logo' => 'الشعار',
    'hero_event' => 'صورة الهيرو — الفعاليات',
    'hero_marketing' => 'صورة الهيرو — التسويق',
    'about_image' => 'صورة من نحن',
    'cta_image' => 'صورة الدعوة الأخيرة',
];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!admin_posted()) {
        admin_flash('error', 'انتهت صلاحية الجلسة.');
    } else {
        $doc = neon_doc();
        $doc['settings']['portfolio_url'] = neon_plain((string) ($_POST['portfolio_url'] ?? ''));
        $doc['settings']['call_number'] = neon_plain((string) ($_POST['call_number'] ?? ''));
        $doc['settings']['whatsapp_number'] = neon_plain((string) ($_POST['whatsapp_number'] ?? ''));
        foreach ($imageKeys as $key => $label) {
            $uploaded = neon_upload($_FILES[$key] ?? []);
            if (is_string($uploaded)) {
                $doc['settings'][$key] = $uploaded;
            }
        }
        $counts = $_POST['count'] ?? [];
        $suffixes = $_POST['suffix'] ?? [];
        for ($i = 0; $i < 4; $i++) {
            $doc['stats'][$i] = [
                'count' => max(0, (int) ($counts[$i] ?? 0)),
                'suffix' => neon_plain((string) ($suffixes[$i] ?? '')),
            ];
        }
        $saved = neon_commit($doc);
        admin_flash($saved ? 'ok' : 'error', $saved ? 'تم حفظ الإعدادات.' : 'تعذر الحفظ.');
    }
    header('Location: settings.php');
    exit;
}

$doc = neon_doc();
$token = admin_csrf_token();
admin_header('الصور والإعدادات');
?>
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="_token" value="<?= e($token) ?>">
  <div class="field"><label>رابط تحميل الأعمال</label><input type="url" name="portfolio_url" value="<?= e((string) $doc['settings']['portfolio_url']) ?>"></div>
  <div class="pair">
    <div class="field"><label>رقم الاتصال الدولي</label><input name="call_number" dir="ltr" placeholder="+2010..." value="<?= e((string) $doc['settings']['call_number']) ?>"></div>
    <div class="field"><label>رقم الواتساب الدولي</label><input name="whatsapp_number" dir="ltr" placeholder="+2010..." value="<?= e((string) $doc['settings']['whatsapp_number']) ?>"></div>
  </div>
  <h2>الأرقام</h2>
  <div class="pair">
    <?php foreach ($doc['stats'] as $i => $stat): ?>
      <div class="field"><label>الرقم <?= $i + 1 ?></label>
        <div class="pair">
          <input type="number" name="count[<?= $i ?>]" value="<?= (int) ($stat['count'] ?? 0) ?>">
          <input name="suffix[<?= $i ?>]" value="<?= e((string) ($stat['suffix'] ?? '')) ?>" placeholder="+">
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <h2>الصور</h2>
  <?php foreach ($imageKeys as $key => $label): ?>
    <div class="field"><label><?= e($label) ?></label><?php admin_image_field($key, (string) ($doc['settings'][$key] ?? '')); ?></div>
  <?php endforeach; ?>
  <button class="btn" type="submit">حفظ</button>
</form>
<?php admin_footer();