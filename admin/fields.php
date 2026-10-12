<?php
declare(strict_types=1);

function admin_text_sections(): array
{
    return [
        'عام' => [
            'title' => 'عنوان الصفحة',
            'description' => 'وصف الصفحة',
        ],
        'القائمة' => [
            'nav_about' => 'من نحن',
            'nav_events' => 'الفعاليات',
            'nav_services' => 'الخدمات',
            'nav_work' => 'الأعمال',
            'nav_contact' => 'تواصل',
            'download_portfolio' => 'زر تحميل الأعمال',
        ],
        'الهيرو' => [
            'hero_eyebrow' => 'السطر الصغير',
            'hero_title' => 'العنوان',
            'hero_description' => 'الوصف',
            'hero_cta' => 'زر الهيرو',
            'scroll' => 'نص التمرير',
        ],
        'الأعمال للتحميل' => [
            'portfolio_eyebrow' => 'السطر الصغير',
            'portfolio_title' => 'العنوان',
            'portfolio_text' => 'النص',
        ],
        'ماذا نفعل' => [
            'events_eyebrow' => 'السطر الصغير',
            'events_title' => 'العنوان',
            'events_text' => 'النص',
            'tab_events' => 'تبويب الفعاليات',
            'tab_marketing' => 'تبويب التسويق',
        ],
        'الشريط المتحرك' => [
            'marquee_a' => 'الجملة الأولى',
            'marquee_b' => 'الجملة الثانية',
        ],
        'قسم الأعمال' => [
            'work_eyebrow' => 'السطر الصغير',
            'work_title' => 'العنوان',
            'work_text' => 'النص',
            'filter_all' => 'كل التجارب',
            'filter_events' => 'فعاليات',
            'filter_marketing' => 'العلامة والرقمي',
            'view' => 'زر استكشاف',
        ],
        'من نحن' => [
            'about_eyebrow' => 'السطر الصغير',
            'about_title' => 'العنوان',
            'about_lead' => 'السطر البارز',
            'about_body' => 'النص',
            'about_link' => 'الرابط',
            'about_note' => 'ملاحظة الصورة',
            'about_image_alt' => 'وصف الصورة',
        ],
        'الرحلة' => [
            'process_eyebrow' => 'السطر الصغير',
            'process_title' => 'العنوان',
            'process_text' => 'النص',
            'step1_title' => 'الخطوة 1',
            'step1_text' => 'وصف الخطوة 1',
            'step2_title' => 'الخطوة 2',
            'step2_text' => 'وصف الخطوة 2',
            'step3_title' => 'الخطوة 3',
            'step3_text' => 'وصف الخطوة 3',
            'step4_title' => 'الخطوة 4',
            'step4_text' => 'وصف الخطوة 4',
            'step5_title' => 'الخطوة 5',
            'step5_text' => 'وصف الخطوة 5',
        ],
        'الشريط' => [
            'cap1_title' => 'الاستراتيجية',
            'cap1_text' => 'وصف الاستراتيجية',
            'cap2_title' => 'الإبداع',
            'cap2_text' => 'وصف الإبداع',
            'cap3_title' => 'الإنتاج',
            'cap3_text' => 'وصف الإنتاج',
            'cap4_title' => 'التواصل',
            'cap4_text' => 'وصف التواصل',
        ],
        'الأرقام والعملاء' => [
            'stats_title' => 'عنوان الأرقام',
            'stat1' => 'الرقم 1',
            'stat2' => 'الرقم 2',
            'stat3' => 'الرقم 3',
            'stat4' => 'الرقم 4',
            'clients_title' => 'عنوان العملاء',
        ],
        'الدعوة الأخيرة' => [
            'cta_eyebrow' => 'السطر الصغير',
            'cta_title' => 'العنوان',
            'cta_text' => 'النص',
            'cta_button' => 'الزر',
        ],
        'التذييل والنموذج' => [
            'footer_tagline' => 'سطر التذييل',
            'footer_note' => 'ملاحظة التذييل',
            'copyright' => 'اسم الحقوق',
            'brief_eyebrow' => 'مقدمة النموذج',
            'brief_title' => 'عنوان النموذج',
            'brief_intro' => 'شرح النموذج',
            'brief_name' => 'حقل الاسم',
            'brief_email' => 'حقل البريد',
            'brief_plan' => 'حقل الخدمة',
            'brief_opt1' => 'اختيار 1',
            'brief_opt2' => 'اختيار 2',
            'brief_opt3' => 'اختيار 3',
            'brief_opt4' => 'اختيار 4',
            'brief_opt5' => 'اختيار 5',
            'brief_more' => 'حقل التفاصيل',
            'brief_submit' => 'زر التحميل',
            'brief_status' => 'رسالة النجاح',
            'contact_intro' => 'رسالة التواصل',
        ],
    ];
}

function admin_html_keys(): array
{
    return ['hero_title', 'scroll', 'portfolio_title', 'events_title', 'work_title', 'about_title', 'about_lead', 'process_title', 'cta_title', 'footer_tagline', 'brief_title', 'contact_title'];
}
