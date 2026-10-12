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
        $action = (string) ($_POST['action'] ?? 'save');
        if ($action === 'delete') {
            $index = (int) ($_POST['index'] ?? -1);
            if (isset($doc['clients'][$index])) {
                array_splice($doc['clients'], $index, 1);
            }
            $saved = neon_commit($doc);
            admin_flash($saved ? 'ok' : 'error', $saved ? 'تم حذف الشعار.' : 'تعذر الحذف.');
        } elseif ($action === 'add') {
            $uploaded = neon_upload($_FILES['logo'] ?? []);
            if ($uploaded === null) {
                admin_flash('error', 'ارفع صورة الشعار بصيغة JPG أو PNG أو WebP.');
            } else {
                $doc['clients'][] = [
                    'src' => $uploaded,
                    'alt_en' => neon_plain((string) ($_POST['alt_en'] ?? '')),
                    'alt_ar' => neon_plain((string) ($_POST['alt_ar'] ?? '')),
                    'w' => 160,
                    'h' => 80,
                ];
                $saved = neon_commit($doc);
                admin_flash($saved ? 'ok' : 'error', $saved ? 'تمت إضافة الشعار.' : 'تعذر الحفظ.');
            }
        } else {
            $posted = $_POST['client'] ?? [];
            foreach ($doc['clients'] as $i => $client) {
                $doc['clients'][$i]['alt_en'] = neon_plain((string) ($posted[$i]['alt_en'] ?? ''));
                $doc['clients'][$i]['alt_ar'] = neon_plain((string) ($posted[$i]['alt_ar'] ?? ''));
                $uploaded = neon_upload(neon_uploaded_file('replace', $i));
                if (is_string($uploaded)) {
                    $doc['clients'][$i]['src'] = $uploaded;
                }
            }
            $saved = neon_commit($doc);
            admin_flash($saved ? 'ok' : 'error', $saved ? 'تم حفظ العملاء.' : 'تعذر الحفظ.');
        }
    }
    header('Location: clients.php');
    exit;
}

$doc = neon_doc();
$token = admin_csrf_token();
admin_header('العملاء');
?>
<form class="block" method="post" enctype="multipart/form-data">
  <input type="hidden" name="_token" value="<?= e($token) ?>">
  <input type="hidden" name="action" value="add">
  <h2>إضافة شعار</h2>
  <div class="pair">
    <div class="field"><label>الاسم بالعربي</label><input name="alt_ar" required></div>
    <div class="field"><label>الاسم بالإنجليزي</label><input name="alt_en" required></div>
  </div>
  <div class="field"><label>الصورة</label><input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/gif" required></div>
  <button class="btn" type="submit">إضافة</button>
</form>
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="_token" value="<?= e($token) ?>">
  <input type="hidden" name="action" value="save">
  <?php foreach ($doc['clients'] as $i => $client): ?>
    <div class="block">
      <?php admin_image_field('replace[' . $i . ']', (string) ($client['src'] ?? '')); ?>
      <div class="pair">
        <div class="field"><label>عربي</label><input name="client[<?= $i ?>][alt_ar]" value="<?= e((string) ($client['alt_ar'] ?? '')) ?>"></div>
        <div class="field"><label>English</label><input name="client[<?= $i ?>][alt_en]" value="<?= e((string) ($client['alt_en'] ?? '')) ?>"></div>
      </div>
      <button class="btn ghost small" type="submit" form="delete-<?= $i ?>">حذف</button>
    </div>
  <?php endforeach; ?>
  <button class="btn" type="submit">حفظ العملاء</button>
</form>
<?php foreach ($doc['clients'] as $i => $client): ?>
  <form id="delete-<?= $i ?>" method="post">
    <input type="hidden" name="_token" value="<?= e($token) ?>">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="index" value="<?= $i ?>">
  </form>
<?php endforeach; ?>
<?php admin_footer();