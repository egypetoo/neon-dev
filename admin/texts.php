<?php
declare(strict_types=1);
require dirname(__DIR__) . '/includes/bootstrap.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/layout.php';
require __DIR__ . '/fields.php';
admin_require_login();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!admin_posted()) {
        admin_flash('error', 'انتهت صلاحية الجلسة.');
    } else {
        $doc = neon_doc();
        $htmlKeys = admin_html_keys();
        $posted = $_POST['copy'] ?? [];
        foreach (['en', 'ar'] as $code) {
            if (!is_array($posted[$code] ?? null)) {
                continue;
            }
            foreach ($posted[$code] as $key => $value) {
                $key = (string) $key;
                if (!isset($doc['copy'][$code][$key]) || !is_string($value)) {
                    continue;
                }
                $doc['copy'][$code][$key] = in_array($key, $htmlKeys, true) ? neon_html($value) : neon_plain($value);
            }
        }
        $saved = neon_commit($doc);
        admin_flash($saved ? 'ok' : 'error', $saved ? 'تم حفظ النصوص.' : 'تعذر الحفظ.');
    }
    header('Location: texts.php');
    exit;
}

$doc = neon_doc();
$token = admin_csrf_token();
admin_header('النصوص');
?>
<p class="hint">الحقول التي فيها سطر جديد مكتوبة بـ &lt;br&gt; والكلمة المميزة بـ &lt;em&gt;.</p>
<form method="post">
  <input type="hidden" name="_token" value="<?= e($token) ?>">
  <?php foreach (admin_text_sections() as $section => $fields): ?>
    <h2><?= e($section) ?></h2>
    <?php foreach ($fields as $key => $label): ?>
      <div class="pair">
        <?php foreach (['ar' => 'عربي', 'en' => 'English'] as $code => $name): ?>
          <div class="field">
            <label><?= e($label) ?> — <?= e($name) ?></label>
            <?php $value = (string) ($doc['copy'][$code][$key] ?? ''); $long = strlen($value) > 80 || str_contains($value, '<br>'); ?>
            <?php if ($long): ?>
              <textarea name="copy[<?= e($code) ?>][<?= e($key) ?>]"><?= e($value) ?></textarea>
            <?php else: ?>
              <input name="copy[<?= e($code) ?>][<?= e($key) ?>]" value="<?= e($value) ?>">
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  <?php endforeach; ?>
  <button class="btn" type="submit">حفظ النصوص</button>
</form>
<?php admin_footer();