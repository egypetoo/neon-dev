<?php
declare(strict_types=1);
require dirname(__DIR__) . '/includes/bootstrap.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/layout.php';
admin_require_login();
$doc = neon_doc();
admin_header('لوحة التحكم');
?>
<div class="cards">
  <div class="card"><strong><?= count($doc['works']) ?></strong><span>أعمال</span></div>
  <div class="card"><strong><?= count($doc['clients']) ?></strong><span>شعارات عملاء</span></div>
  <div class="card"><strong><?= count($doc['expertise']['en']['events'] ?? []) + count($doc['expertise']['en']['marketing'] ?? []) ?></strong><span>خدمات</span></div>
  <div class="card"><strong>2</strong><span>لغات</span></div>
</div>
<div class="panel" style="margin-top:18px">
  <p>من هنا تقدر تغيّر النصوص بالعربي والإنجليزي، وصور الهيرو والأعمال والعملاء، وأرقام التواصل.</p>
  <p class="hint">أي حفظ يظهر على الموقع فورًا. التصميم نفسه ثابت.</p>
</div>
<?php admin_footer();