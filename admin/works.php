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
        $doc = neon_doc();
        $posted = $_POST['work'] ?? [];
        foreach ($doc['works'] as $i => $work) {
            if (!is_array($work)) {
                continue;
            }
            $row = $posted[$i] ?? [];
            $image = (string) ($work['image'] ?? '');
            $uploaded = neon_upload(neon_uploaded_file('image', $i));
            if (is_string($uploaded)) {
                $image = $uploaded;
            }
            $type = (($row['type'] ?? '') === 'marketing') ? 'marketing' : 'events';
            $doc['works'][$i]['type'] = $type;
            $doc['works'][$i]['image'] = $image;
            foreach (['title', 'meta', 'alt', 'label', 'category', 'description', 'tags'] as $field) {
                foreach (['en', 'ar'] as $code) {
                    $doc['works'][$i][$field][$code] = neon_plain((string) ($row[$field][$code] ?? ''));
                }
            }
        }
        $saved = neon_commit($doc);
        admin_flash($saved ? 'ok' : 'error', $saved ? 'تم حفظ الأعمال.' : 'تعذر الحفظ.');
    }
    header('Location: works.php');
    exit;
}

$doc = neon_doc();
$token = admin_csrf_token();
admin_header('الأعمال');
?>
<p class="hint">وسوم النافذة تُفصل بعلامة |.</p>
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="_token" value="<?= e($token) ?>">
  <?php foreach ($doc['works'] as $i => $work): ?>
    <div class="block">
      <h2>عمل <?= $i + 1 ?></h2>
      <?php admin_image_field('image[' . $i . ']', (string) ($work['image'] ?? '')); ?>
      <div class="field"><label>التصنيف</label>
        <select name="work[<?= $i ?>][type]">
          <option value="events" <?= ($work['type'] ?? '') === 'events' ? 'selected' : '' ?>>فعاليات</option>
          <option value="marketing" <?= ($work['type'] ?? '') === 'marketing' ? 'selected' : '' ?>>علامة ورقمي</option>
        </select>
      </div>
      <div class="pair">
        <?php foreach (['ar' => 'عربي', 'en' => 'English'] as $code => $name): ?>
          <div>
            <div class="field"><label>العنوان — <?= e($name) ?></label><input name="work[<?= $i ?>][title][<?= e($code) ?>]" value="<?= e((string) ($work['title'][$code] ?? '')) ?>"></div>
            <div class="field"><label>السطر التحتي — <?= e($name) ?></label><input name="work[<?= $i ?>][meta][<?= e($code) ?>]" value="<?= e((string) ($work['meta'][$code] ?? '')) ?>"></div>
            <div class="field"><label>شارة الصورة — <?= e($name) ?></label><input name="work[<?= $i ?>][label][<?= e($code) ?>]" value="<?= e((string) ($work['label'][$code] ?? '')) ?>"></div>
            <div class="field"><label>تصنيف النافذة — <?= e($name) ?></label><input name="work[<?= $i ?>][category][<?= e($code) ?>]" value="<?= e((string) ($work['category'][$code] ?? '')) ?>"></div>
            <div class="field"><label>الوصف — <?= e($name) ?></label><textarea name="work[<?= $i ?>][description][<?= e($code) ?>]"><?= e((string) ($work['description'][$code] ?? '')) ?></textarea></div>
            <div class="field"><label>الوسوم — <?= e($name) ?></label><input name="work[<?= $i ?>][tags][<?= e($code) ?>]" value="<?= e((string) ($work['tags'][$code] ?? '')) ?>"></div>
            <div class="field"><label>وصف الصورة — <?= e($name) ?></label><input name="work[<?= $i ?>][alt][<?= e($code) ?>]" value="<?= e((string) ($work['alt'][$code] ?? '')) ?>"></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
  <button class="btn" type="submit">حفظ الأعمال</button>
</form>
<?php admin_footer();