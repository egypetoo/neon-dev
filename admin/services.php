<?php
declare(strict_types=1);
require dirname(__DIR__) . '/includes/bootstrap.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/layout.php';
admin_require_login();

$groups = ['events' => 'إدارة الفعاليات', 'marketing' => 'حلول التسويق'];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!admin_posted()) {
        admin_flash('error', 'انتهت صلاحية الجلسة.');
    } else {
        $doc = neon_doc();
        $posted = $_POST['item'] ?? [];
        foreach ($groups as $group => $label) {
            $count = count($doc['expertise']['en'][$group] ?? []);
            for ($i = 0; $i < $count; $i++) {
                $image = (string) ($doc['expertise']['en'][$group][$i][2] ?? '');
                $uploaded = neon_upload(neon_uploaded_file('image', $group, $i));
                if (is_string($uploaded)) {
                    $image = $uploaded;
                }
                foreach (['en', 'ar'] as $code) {
                    $row = $posted[$code][$group][$i] ?? [];
                    $doc['expertise'][$code][$group][$i] = [
                        neon_plain((string) ($row['title'] ?? '')),
                        neon_plain((string) ($row['text'] ?? '')),
                        $image,
                        neon_plain((string) ($row['tags'] ?? '')),
                    ];
                }
            }
        }
        $saved = neon_commit($doc);
        admin_flash($saved ? 'ok' : 'error', $saved ? 'تم حفظ الخدمات.' : 'تعذر الحفظ.');
    }
    header('Location: services.php');
    exit;
}

$doc = neon_doc();
$token = admin_csrf_token();
admin_header('الخدمات');
?>
<p class="hint">الوسوم تُفصل بعلامة | والصورة واحدة للغتين.</p>
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="_token" value="<?= e($token) ?>">
  <?php foreach ($groups as $group => $label): ?>
    <h2><?= e($label) ?></h2>
    <?php foreach ($doc['expertise']['en'][$group] as $i => $item): ?>
      <div class="block">
        <strong>خدمة <?= $i + 1 ?></strong>
        <?php admin_image_field('image[' . $group . '][' . $i . ']', (string) ($item[2] ?? '')); ?>
        <div class="pair">
          <?php foreach (['ar' => 'عربي', 'en' => 'English'] as $code => $name): $row = $doc['expertise'][$code][$group][$i]; ?>
            <div>
              <div class="field"><label>العنوان — <?= e($name) ?></label><input name="item[<?= e($code) ?>][<?= e($group) ?>][<?= $i ?>][title]" value="<?= e((string) ($row[0] ?? '')) ?>"></div>
              <div class="field"><label>النص — <?= e($name) ?></label><textarea name="item[<?= e($code) ?>][<?= e($group) ?>][<?= $i ?>][text]"><?= e((string) ($row[1] ?? '')) ?></textarea></div>
              <div class="field"><label>الوسوم — <?= e($name) ?></label><input name="item[<?= e($code) ?>][<?= e($group) ?>][<?= $i ?>][tags]" value="<?= e((string) ($row[3] ?? '')) ?>"></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endforeach; ?>
  <button class="btn" type="submit">حفظ الخدمات</button>
</form>
<?php admin_footer();