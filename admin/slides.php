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
        $posted = $_POST['slides'] ?? [];
        foreach (['en', 'ar'] as $code) {
            for ($i = 0; $i < 2; $i++) {
                $row = $posted[$code][$i] ?? [];
                if (!is_array($row) || !isset($doc['slides'][$code][$i])) {
                    continue;
                }
                $doc['slides'][$code][$i]['title'] = neon_html((string) ($row['title'] ?? ''));
                $doc['slides'][$code][$i]['description'] = neon_plain((string) ($row['description'] ?? ''));
                $doc['slides'][$code][$i]['cta'] = neon_plain((string) ($row['cta'] ?? ''));
            }
        }
        $saved = neon_commit($doc);
        admin_flash($saved ? 'ok' : 'error', $saved ? 'تم حفظ السلايدر.' : 'تعذر الحفظ.');
    }
    header('Location: slides.php');
    exit;
}

$doc = neon_doc();
$token = admin_csrf_token();
admin_header('السلايدر');
?>
<p class="hint">العنوان يقبل &lt;br&gt; و &lt;em&gt;.</p>
<form method="post">
  <input type="hidden" name="_token" value="<?= e($token) ?>">
  <?php for ($i = 0; $i < 2; $i++): ?>
    <div class="block">
      <h2>الشريحة <?= $i + 1 ?></h2>
      <div class="pair">
        <?php foreach (['ar' => 'عربي', 'en' => 'English'] as $code => $name): $slide = $doc['slides'][$code][$i]; ?>
          <div>
            <div class="field"><label>العنوان — <?= e($name) ?></label><textarea name="slides[<?= e($code) ?>][<?= $i ?>][title]"><?= e((string) $slide['title']) ?></textarea></div>
            <div class="field"><label>الوصف — <?= e($name) ?></label><textarea name="slides[<?= e($code) ?>][<?= $i ?>][description]"><?= e((string) $slide['description']) ?></textarea></div>
            <div class="field"><label>الزر — <?= e($name) ?></label><input name="slides[<?= e($code) ?>][<?= $i ?>][cta]" value="<?= e((string) $slide['cta']) ?>"></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endfor; ?>
  <button class="btn" type="submit">حفظ السلايدر</button>
</form>
<?php admin_footer();