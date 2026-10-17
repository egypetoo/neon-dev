<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';

$otherLang = is_rtl() ? 'en' : 'ar';
$doc = neon_doc();
$expertise = $doc['expertise'][$lang] ?? $EXPERTISE[$lang];
$projects = neon_projects_for($lang);
$slides = $doc['slides'][$lang] ?? $SLIDES[$lang];
$works = $doc['works'];
$clients = $doc['clients'];
$stats = $doc['stats'];
$logo = neon_public_path(neon_setting('logo')) ?: 'assets/neon-logo.png';
$heroEvent = neon_public_path(neon_setting('hero_event')) ?: 'assets/contact-entrance-hd.png';
$heroMarketing = neon_public_path(neon_setting('hero_marketing')) ?: 'assets/marketing-hero-full-hd.png';
$aboutImage = neon_public_path(neon_setting('about_image')) ?: 'assets/brand-world.png';
$ctaImage = neon_public_path(neon_setting('cta_image')) ?: 'assets/creative-04-hd.png';
$portfolioUrl = neon_safe_url(neon_setting('portfolio_url'));
$statKeys = ['stat1', 'stat2', 'stat3', 'stat4'];
?>
<!doctype html>
<html lang="<?= e($lang) ?>" dir="<?= is_rtl() ? 'rtl' : 'ltr' ?>">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#080808"><meta name="description" content="<?= t('description') ?>">
  <title><?= t('title') ?></title>
  <link rel="icon" href="<?= e($logo) ?>"><link rel="stylesheet" href="assets/bootstrap-grid.min.css"><link rel="stylesheet" href="assets/cabin.css"><link rel="stylesheet" href="assets/arabic.css"><link rel="stylesheet" href="style.css"><link rel="stylesheet" href="assets/rtl.css">
  <link rel="preload" href="assets/chillax-600.woff2" as="font" type="font/woff2" crossorigin><link rel="preload" href="<?= e($heroEvent) ?>" as="image">
</head>
<body>
<div class="scroll-progress" aria-hidden="true"></div>
<a class="skip-link" href="#main"><?= t('skip') ?></a>
<header class="site-header"><div class="container header-inner">
  <a class="logo" href="#home" aria-label="<?= t('logo_home') ?>"><img src="<?= e($logo) ?>" alt="neon" width="1248" height="360"></a>
  <nav class="desktop-nav" aria-label="<?= t('nav_label') ?>"><a href="#about"><?= t('nav_about') ?></a><a href="#events"><?= t('nav_events') ?></a><a href="#services"><?= t('nav_services') ?></a><a href="#work"><?= t('nav_work') ?></a><a href="#contact"><?= t('nav_contact') ?></a></nav>
  <div class="header-tools">
    <a class="lang-switch" href="<?= e(lang_href($otherLang)) ?>" hreflang="<?= e($otherLang) ?>" lang="<?= e($otherLang) ?>" data-set-lang="<?= e($otherLang) ?>"><?= t('lang_code') ?></a>
    <a class="button button-outline header-cta" href="<?= e($portfolioUrl) ?>" target="_blank" rel="noopener noreferrer"><?= t('download_portfolio') ?></a>
  </div>
  <button class="menu-toggle" aria-label="<?= t('open_nav') ?>" aria-expanded="false" aria-controls="mobile-nav"><span></span><span></span></button>
</div><nav id="mobile-nav" class="mobile-nav" aria-label="<?= t('mobile_nav') ?>" hidden><a href="#about"><?= t('nav_about') ?></a><a href="#events"><?= t('nav_events') ?></a><a href="#services"><?= t('nav_services') ?></a><a href="#work"><?= t('nav_work') ?></a><a href="#contact"><?= t('nav_contact') ?></a><a href="<?= e(lang_href($otherLang)) ?>" hreflang="<?= e($otherLang) ?>" lang="<?= e($otherLang) ?>" data-set-lang="<?= e($otherLang) ?>"><?= t('lang_switch') ?></a></nav></header>
<main id="main">
<section class="hero" id="home" aria-roledescription="carousel" aria-label="<?= t('hero_label') ?>">
  <div class="hero-art event-art is-active" style="background-image:url('<?= e($heroEvent) ?>')" aria-hidden="true"></div><div class="hero-art marketing-art" style="background-image:url('<?= e($heroMarketing) ?>')" aria-hidden="true"></div><div class="hero-shade" aria-hidden="true"></div>
  <div class="container hero-inner">
    <div class="hero-copy" aria-live="polite">
      <p class="eyebrow hero-eyebrow"><?= t('hero_eyebrow') ?> <span></span></p>
      <h1 id="hero-title"><?= th('hero_title') ?></h1>
      <p class="hero-description" id="hero-description"><?= t('hero_description') ?></p>
      <button class="button button-gradient hero-cta" data-brief id="hero-cta"><?= t('hero_cta') ?></button>
    </div>
    <div class="hero-bottom"><a class="scroll-cue" href="#events"><span class="mouse"><i></i></span><span><?= th('scroll') ?></span></a><div class="slide-controls"><span class="slide-number">01 <small>/ 02</small></span><button class="slide-dot active" aria-label="<?= t('slide_event') ?>" aria-pressed="true" data-slide="0"><span></span></button><button class="slide-dot" aria-label="<?= t('slide_marketing') ?>" aria-pressed="false" data-slide="1"><span></span></button><button class="pause-slide" aria-label="<?= t('pause') ?>">Ⅱ</button></div></div>
  </div>
</section>
<section class="portfolio-cta" id="portfolio"><div class="container"><div class="portfolio-card reveal">
  <div><p class="eyebrow"><span class="pink-dot"></span> <?= t('portfolio_eyebrow') ?></p><h2><?= th('portfolio_title') ?></h2><p><?= t('portfolio_text') ?></p></div>
  <a class="button button-gradient" href="<?= e($portfolioUrl) ?>" target="_blank" rel="noopener noreferrer"><?= t('download_portfolio') ?></a>
</div></div></section>
<section class="intro section" id="events"><div class="container">
  <div class="section-top reveal"><div><p class="eyebrow"><span class="pink-dot"></span> <?= t('events_eyebrow') ?></p><h2><?= th('events_title') ?></h2></div><p class="section-description"><?= t('events_text') ?></p><span class="section-index"><?= t('events_index') ?></span></div>
  <div id="services" class="service-tabs reveal" role="tablist" aria-label="<?= t('tabs_label') ?>"><button id="events-tab" class="active" role="tab" aria-selected="true" aria-controls="expertise-panel" tabindex="0" data-category="events"><?= t('tab_events') ?> <span><?= e(str_pad((string) count($expertise['events'] ?? []), 2, '0', STR_PAD_LEFT)) ?></span></button><button id="marketing-tab" role="tab" aria-selected="false" aria-controls="expertise-panel" tabindex="-1" data-category="marketing"><?= t('tab_marketing') ?> <span><?= e(str_pad((string) count($expertise['marketing'] ?? []), 2, '0', STR_PAD_LEFT)) ?></span></button></div>
  <div class="row g-4 expertise-grid" id="expertise-panel" role="tabpanel" aria-labelledby="events-tab"></div>
</div></section>
<div class="marquee" aria-hidden="true"><div class="marquee-track"><span><?= t('marquee_a') ?></span><i>✳</i><span><?= t('marquee_b') ?></span><i>✳</i><span><?= t('marquee_a') ?></span><i>✳</i><span><?= t('marquee_b') ?></span><i>✳</i></div></div>
<section class="work section" id="work"><div class="container">
  <div class="section-top reveal"><div><p class="eyebrow"><span class="pink-dot"></span> <?= t('work_eyebrow') ?></p><h2><?= th('work_title') ?></h2></div><p class="section-description"><?= t('work_text') ?></p><span class="section-index"><?= t('work_index') ?></span></div>
  <div class="work-filters reveal" role="group" aria-label="<?= t('filters_label') ?>"><button class="active" aria-pressed="true" data-filter="all"><?= t('filter_all') ?></button><button aria-pressed="false" data-filter="events"><?= t('filter_events') ?></button><button aria-pressed="false" data-filter="marketing"><?= t('filter_marketing') ?></button></div>
  <div class="row g-4 work-grid">
    <?php foreach ($works as $i => $work): if (!is_array($work)) continue;
      $workImage = neon_public_path((string) ($work['image'] ?? '')) ?: 'assets/creative-01-hd.png';
      $col = ($work['col'] ?? '') === 'col-md-5' ? 'col-md-5' : 'col-md-7';
      $type = ($work['type'] ?? '') === 'marketing' ? 'marketing' : 'events';
    ?>
    <div class="<?= e($col) ?> work-item" data-type="<?= e($type) ?>"><button class="work-card reveal" data-project="<?= (int) $i ?>"><div class="work-image"><img src="<?= e($workImage) ?>" alt="<?= e((string) ($work['alt'][$lang] ?? '')) ?>" loading="lazy"><span class="image-label"><?= e((string) ($work['label'][$lang] ?? '')) ?></span><span class="view-label"><?= t('view') ?></span></div><div class="work-caption"><span class="work-number"><?= e(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)) ?></span><div><h3><?= e((string) ($work['title'][$lang] ?? '')) ?></h3><p><?= e((string) ($work['meta'][$lang] ?? '')) ?></p></div><span class="round-plus" aria-hidden="true">+</span></div></button></div>
    <?php endforeach; ?>
  </div>
</div></section>
<section class="about section" id="about"><div class="container"><div class="row g-5 align-items-center">
  <div class="col-lg-6 reveal"><p class="eyebrow"><span class="pink-dot"></span> <?= t('about_eyebrow') ?></p><h2><?= th('about_title') ?></h2><p class="about-lead"><?= th('about_lead') ?></p><p class="about-body"><?= t('about_body') ?></p><a class="text-link" href="#services"><?= t('about_link') ?> <span>+</span></a></div>
  <div class="col-lg-6 reveal"><div class="brand-image"><img src="<?= e($aboutImage) ?>" alt="<?= t('about_image_alt') ?>" loading="lazy"><span class="brand-note"><?= t('about_note') ?></span></div></div>
</div></div></section>
<section class="process section" id="process"><div class="container">
  <div class="section-top reveal"><div><p class="eyebrow"><span class="pink-dot"></span> <?= t('process_eyebrow') ?></p><h2><?= th('process_title') ?></h2></div><p class="section-description"><?= t('process_text') ?></p><span class="section-index"><?= t('process_index') ?></span></div>
  <div class="process-path" aria-hidden="true"><svg viewBox="0 0 1200 100" preserveAspectRatio="none"><defs><linearGradient id="line-gradient"><stop stop-color="#F01992"/><stop offset="1" stop-color="#FC5E2A"/></linearGradient></defs><path d="M0 55 C100 55 100 15 200 15 S300 90 400 65 S500 15 600 40 S700 95 800 55 S900 10 1000 40 S1100 75 1200 45"/></svg><div class="path-light"></div></div>
  <div class="row g-4 process-grid">
    <div class="col-6 col-lg"><article class="process-step reveal"><span>01</span><h3><?= t('step1_title') ?></h3><p><?= t('step1_text') ?></p></article></div>
    <div class="col-6 col-lg"><article class="process-step reveal"><span>02</span><h3><?= t('step2_title') ?></h3><p><?= t('step2_text') ?></p></article></div>
    <div class="col-6 col-lg"><article class="process-step reveal"><span>03</span><h3><?= t('step3_title') ?></h3><p><?= t('step3_text') ?></p></article></div>
    <div class="col-6 col-lg"><article class="process-step reveal"><span>04</span><h3><?= t('step4_title') ?></h3><p><?= t('step4_text') ?></p></article></div>
    <div class="col-6 col-lg"><article class="process-step reveal"><span>05</span><h3><?= t('step5_title') ?></h3><p><?= t('step5_text') ?></p></article></div>
  </div>
</div></section>
<section class="capability-strip"><div class="container"><div class="row g-4"><div class="col-6 col-md-3"><strong><?= t('cap1_title') ?></strong><span><?= t('cap1_text') ?></span></div><div class="col-6 col-md-3"><strong><?= t('cap2_title') ?></strong><span><?= t('cap2_text') ?></span></div><div class="col-6 col-md-3"><strong><?= t('cap3_title') ?></strong><span><?= t('cap3_text') ?></span></div><div class="col-6 col-md-3"><strong><?= t('cap4_title') ?></strong><span><?= t('cap4_text') ?></span></div></div></div></section>
<section class="stats" aria-labelledby="stats-title"><div class="container">
  <p class="eyebrow stats-eyebrow" id="stats-title"><?= t('stats_title') ?></p>
  <div class="stats-grid">
    <?php foreach ($statKeys as $i => $statKey):
      $count = (int) ($stats[$i]['count'] ?? 0);
      $suffix = (string) ($stats[$i]['suffix'] ?? '');
    ?>
    <div class="stat reveal"><strong data-count="<?= $count ?>" data-suffix="<?= e($suffix) ?>"><?= e((string) $count . $suffix) ?></strong><span><?= t($statKey) ?></span></div>
    <?php endforeach; ?>
  </div>
</div></section>
<section class="clients" aria-labelledby="clients-title"><div class="container">
  <p class="eyebrow clients-eyebrow" id="clients-title"><?= t('clients_title') ?></p>
  <div class="clients-slider">
    <button type="button" class="clients-arrow" data-clients-scroll="-1" aria-label="<?= t('clients_prev') ?>">←</button>
    <ul class="clients-track">
      <?php foreach ($clients as $client): if (!is_array($client)) continue; $clientSrc = neon_public_path((string) ($client['src'] ?? '')); if ($clientSrc === '') continue; ?>
      <li class="client-logo"><img src="<?= e($client['src']) ?>" alt="<?= e($client[is_rtl() ? 'alt_ar' : 'alt_en']) ?>" width="<?= (int) $client['w'] ?>" height="<?= (int) $client['h'] ?>" loading="lazy"></li>
      <?php endforeach; ?>
    </ul>
    <button type="button" class="clients-arrow" data-clients-scroll="1" aria-label="<?= t('clients_next') ?>">→</button>
  </div>
</div></section>
<section class="idea-cta" id="contact"><div class="idea-cta-art" style="--cta-image:url('<?= e($ctaImage) ?>')" aria-hidden="true"></div><div class="container idea-cta-inner reveal">
  <div class="idea-cta-heading"><p class="eyebrow"><?= t('cta_eyebrow') ?></p><h2><?= th('cta_title') ?></h2></div>
  <div class="idea-cta-action"><p><?= t('cta_text') ?></p><button class="button button-gradient" data-brief><?= t('cta_button') ?> <span class="idea-cta-arrow" aria-hidden="true">→</span></button></div>
</div></section>
</main>
<footer><div class="container"><div class="footer-main"><a class="logo" href="#home" aria-label="<?= t('logo_home') ?>"><img src="<?= e($logo) ?>" alt="neon" width="1248" height="360"></a><p><?= th('footer_tagline') ?></p><nav aria-label="<?= t('footer_nav') ?>"><a href="#events"><?= t('nav_events') ?></a><a href="#work"><?= t('nav_work') ?></a><a href="#about"><?= t('nav_about') ?></a><a href="#contact"><?= t('nav_contact') ?></a></nav><a class="back-top" href="#home"><?= t('back_top') ?></a></div><div class="footer-bottom"><span>© <span id="year">2026</span> <?= t('copyright') ?></span><span><?= t('footer_note') ?></span></div></div></footer>
<dialog id="brief-dialog" class="brief-dialog"><button class="dialog-close" aria-label="<?= t('brief_close') ?>">×</button><p class="eyebrow"><?= t('brief_eyebrow') ?></p><h2><?= th('brief_title') ?></h2><p class="dialog-intro"><?= t('brief_intro') ?></p><form id="brief-form"><label><?= t('brief_name') ?><input name="name" required autocomplete="name" placeholder="<?= t('brief_name_ph') ?>"></label><label><?= t('brief_email') ?><input type="email" name="email" required autocomplete="email" placeholder="<?= t('brief_email_ph') ?>" dir="ltr"></label><label><?= t('brief_plan') ?><select name="service"><option><?= t('brief_opt1') ?></option><option><?= t('brief_opt2') ?></option><option><?= t('brief_opt3') ?></option><option><?= t('brief_opt4') ?></option><option><?= t('brief_opt5') ?></option></select></label><label><?= t('brief_more') ?><textarea name="message" rows="3" required placeholder="<?= t('brief_more_ph') ?>"></textarea></label><button class="button button-gradient" type="submit"><?= t('brief_submit') ?></button><p class="form-status" role="status"></p></form></dialog>
<dialog id="detail-dialog" class="detail-dialog"><button class="dialog-close" aria-label="<?= t('detail_close') ?>">×</button><img id="detail-image" alt=""><div class="detail-content"><p class="eyebrow" id="detail-category"></p><h2 id="detail-title"></h2><p id="detail-description"></p><div id="detail-tags" class="detail-tags"></div><button class="button button-gradient" data-brief><?= t('detail_cta') ?></button></div></dialog>
<aside class="floating-contact" aria-label="<?= t('float_label') ?>">
  <button type="button" class="floating-contact-button" data-contact-action="call" aria-label="<?= t('call_label') ?>" aria-disabled="true" title="<?= t('call_pending') ?>"><img src="assets/telephone-fill.svg" alt="" width="23" height="23"><span class="floating-contact-label"><?= t('call') ?></span></button>
  <button type="button" class="floating-contact-button" data-contact-action="whatsapp" aria-label="<?= t('wa_label') ?>" aria-disabled="true" title="<?= t('wa_pending') ?>"><img src="assets/whatsapp.svg" alt="" width="25" height="25"><span class="floating-contact-label"><?= t('whatsapp') ?></span></button>
</aside>
<dialog id="contact-details-dialog" class="contact-details-dialog"><button class="dialog-close" aria-label="<?= t('contact_close') ?>">×</button><p class="eyebrow"><?= t('contact_eyebrow') ?></p><h2><?= th('contact_title') ?></h2><p class="dialog-intro"><?= t('contact_intro') ?></p><button type="button" class="button button-gradient" data-brief><?= t('contact_brief') ?></button></dialog>
<script>
window.NEON = <?= json_encode([
    'expertise' => $expertise,
    'projects' => $projects,
    'slides' => $slides,
    'ui' => [
        'eventsCategory' => phrase('events_category'),
        'marketingCategory' => phrase('marketing_category'),
        'cardAltSuffix' => phrase('card_alt_suffix'),
        'openNav' => phrase('open_nav'),
        'closeNav' => phrase('close_nav'),
        'pause' => phrase('pause'),
        'resume' => phrase('resume'),
        'briefStatus' => phrase('brief_status'),
        'briefFileTitle' => phrase('brief_file_title'),
        'briefFileName' => phrase('brief_file_name'),
        'briefFileEmail' => phrase('brief_file_email'),
        'briefFileService' => phrase('brief_file_service'),
        'briefFileIdea' => phrase('brief_file_idea'),
        'briefFilePrepared' => phrase('brief_file_prepared'),
        'callReady' => phrase('call_ready'),
        'waReady' => phrase('wa_ready'),
        'callPending' => phrase('call_pending'),
        'waPending' => phrase('wa_pending'),
        'locale' => $lang === 'ar' ? 'ar-EG' : 'en-GB',
    ],
], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
</script>
<script>window.NEON_CONTACT = <?= json_encode(['callNumber' => neon_setting('call_number'), 'whatsappNumber' => neon_setting('whatsapp_number')], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;</script>
<script src="contact-config.js"></script>
<script src="script.js" defer></script>
</body>
</html>
