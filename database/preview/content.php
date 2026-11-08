<?php

/*
|--------------------------------------------------------------------------
| Preview content — used only by `php artisan preview:content` on a developer machine
|--------------------------------------------------------------------------
| Shows how the site looks once every section has content. Product texts are built
| from the company's own service descriptions; no client names or invented figures
| are used (client tiles show sectors, the team shows departments). Replace with real
| content from the dashboard before publishing.
*/

return [
    'settings' => [
        'working_hours_ar' => "السبت – الخميس: 8:00 صباحاً – 5:00 مساءً\nالجمعة: إجازة",
        'working_hours_en' => "Sat – Thu: 8:00 AM – 5:00 PM\nFriday: closed",
        'map_embed_url' => 'https://www.google.com/maps?q=Sanaa,Yemen&z=12&output=embed',
        'show_team_page' => true,
        'show_portfolio_page' => true,
        'show_testimonials_page' => true,
    ],

    'products' => [
        [
            'slug' => 'pos-system', 'image' => 'pos',
            'name_ar' => 'نظام نقاط البيع POS', 'name_en' => 'POS System',
            'tagline_ar' => 'بيع أسرع وفواتير دقيقة للمتاجر والمطاعم', 'tagline_en' => 'Faster checkout and accurate invoices for shops and restaurants',
            'description_ar' => "حلول نقاط بيع متطورة للمتاجر والمطاعم والشركات التجارية بواجهة سهلة الاستخدام.\nيعمل النظام مع الباركود والطابعات الحرارية، ويربط كل الفروع بتقارير مبيعات فورية، ويتكامل مع نظام الحسابات والمخازن.",
            'description_en' => 'Advanced point-of-sale for shops, restaurants and trading companies with an easy interface, barcode and receipt printing, multi-branch reporting and integration with accounting and inventory.',
            'features_ar' => ['دعم المتاجر والمطاعم', 'عمليات الباركود والفواتير', 'إدارة العمليات على مستوى الفروع', 'تقارير مبيعات فورية', 'التكامل مع الحسابات والمخازن', 'صلاحيات للكاشير والمشرف'],
            'features_en' => ['Shops and restaurants', 'Barcode and invoices', 'Multi-branch operations', 'Instant sales reports', 'Accounting & inventory integration', 'Cashier and supervisor permissions'],
            'sectors_ar' => ['المطاعم والكافيهات', 'السوبرماركت', 'الصيدليات', 'محلات التجزئة'],
            'sectors_en' => ['Restaurants & cafés', 'Supermarkets', 'Pharmacies', 'Retail shops'],
        ],
        [
            'slug' => 'erp-accounting', 'image' => 'erp',
            'name_ar' => 'نظام ERP والمحاسبة', 'name_en' => 'ERP & Accounting',
            'tagline_ar' => 'الحسابات والمبيعات والمشتريات والمخازن في نظام واحد', 'tagline_en' => 'Accounts, sales, purchasing and inventory in one system',
            'description_ar' => "أنظمة تخطيط موارد المؤسسات التي تدير جميع عملياتك في نظام واحد متكامل وسهل الاستخدام.\nمن القيود اليومية والميزانيات إلى أوامر الشراء وحركة المستودعات، مع تقارير مالية جاهزة لمتخذي القرار.",
            'description_en' => 'Enterprise resource planning that runs all your operations in one integrated, easy system — from journal entries and budgets to purchase orders and warehouse movements, with ready financial reports.',
            'features_ar' => ['إدارة الحسابات والقيود', 'إدارة المبيعات والمشتريات', 'إدارة المخازن والمستودعات', 'تكامل نقاط البيع POS', 'تقارير مالية وميزانيات', 'تعدد العملات والفروع'],
            'features_en' => ['Accounts and journal entries', 'Sales and purchasing', 'Warehouses and inventory', 'POS integration', 'Financial reports and budgets', 'Multi-currency and branches'],
            'sectors_ar' => ['الشركات التجارية', 'شركات التوزيع', 'المصانع', 'المؤسسات الخدمية'],
            'sectors_en' => ['Trading companies', 'Distributors', 'Factories', 'Service companies'],
        ],
        [
            'slug' => 'hr-payroll', 'image' => 'hr',
            'name_ar' => 'نظام الموارد البشرية والرواتب', 'name_en' => 'HR & Payroll',
            'tagline_ar' => 'الحضور والإجازات والرواتب بدقة ومن مكان واحد', 'tagline_en' => 'Attendance, leave and payroll in one place',
            'description_ar' => "إدارة ملفات الموظفين والحضور والانصراف والإجازات والسلف، وإعداد مسير الرواتب تلقائياً وربطه بالحسابات.",
            'description_en' => 'Employee files, attendance, leave and advances, with automatic payroll linked to accounting.',
            'features_ar' => ['ملفات الموظفين', 'الحضور والانصراف', 'الإجازات والسلف', 'مسير الرواتب التلقائي', 'الربط مع الحسابات', 'تقارير شهرية'],
            'features_en' => ['Employee files', 'Attendance', 'Leave and advances', 'Automatic payroll', 'Accounting integration', 'Monthly reports'],
            'sectors_ar' => ['الشركات', 'المدارس', 'المستشفيات', 'المؤسسات'],
            'sectors_en' => ['Companies', 'Schools', 'Hospitals', 'Organisations'],
        ],
        [
            'slug' => 'e-commerce', 'image' => 'store',
            'name_ar' => 'المتاجر الإلكترونية', 'name_en' => 'E-commerce Stores',
            'tagline_ar' => 'متجر مرتبط بالمخزون والحسابات', 'tagline_en' => 'An online store linked to inventory and accounting',
            'description_ar' => "متاجر إلكترونية متكاملة مرتبطة بالأنظمة الإدارية لإدارة الطلبات والمبيعات بسهولة، مع مزامنة المخزون تلقائياً.",
            'description_en' => 'Complete online stores connected to your management systems to handle orders and sales, with automatic stock sync.',
            'features_ar' => ['إدارة الطلبات', 'متابعة المبيعات', 'مزامنة المخزون', 'تكامل عمليات الدفع', 'إدارة الشحن والتوصيل', 'لوحة تحكم للمتجر'],
            'features_en' => ['Order management', 'Sales tracking', 'Stock sync', 'Payment integration', 'Shipping and delivery', 'Store dashboard'],
            'sectors_ar' => ['محلات الملابس', 'الإلكترونيات', 'المواد الغذائية', 'مستحضرات التجميل'],
            'sectors_en' => ['Fashion', 'Electronics', 'Groceries', 'Cosmetics'],
        ],
        [
            'slug' => 'sales-rep-app', 'image' => 'sales-rep',
            'name_ar' => 'تطبيق المندوبين', 'name_en' => 'Sales Rep App',
            'tagline_ar' => 'طلبات وتحصيل في الميدان، حتى بدون إنترنت', 'tagline_en' => 'Field orders and collections, even offline',
            'description_ar' => "تطبيقات هواتف ذكية تساعد شركتك على تقديم خدماتها بشكل أفضل وأسرع لعملائها.\nيسجّل المندوب الزيارات والطلبات والتحصيل من الميدان، ويعمل التطبيق Online و Offline مع مزامنة البيانات تلقائياً.",
            'description_en' => 'Mobile apps that help your company serve customers faster. Reps record visits, orders and collections in the field, online or offline, with automatic sync.',
            'features_ar' => ['تطبيقات أندرويد و iOS', 'العمل Online و Offline', 'مزامنة البيانات تلقائياً', 'دعم أعمال المندوبين ميدانياً', 'الطلبات والتحصيل', 'تقارير الزيارات'],
            'features_en' => ['Android and iOS', 'Online and offline', 'Automatic sync', 'Field sales support', 'Orders and collections', 'Visit reports'],
            'sectors_ar' => ['شركات التوزيع', 'المواد الغذائية', 'الأدوية', 'مواد البناء'],
            'sectors_en' => ['Distributors', 'Food', 'Pharmaceuticals', 'Building materials'],
        ],
    ],

    'client_logos' => [
        ['name' => 'المطاعم', 'image' => 'sector-1.png'],
        ['name' => 'الصيدليات', 'image' => 'sector-2.png'],
        ['name' => 'السوبرماركت', 'image' => 'sector-3.png'],
        ['name' => 'المستودعات', 'image' => 'sector-4.png'],
        ['name' => 'المدارس', 'image' => 'sector-5.png'],
        ['name' => 'شركات التوزيع', 'image' => 'sector-6.png'],
    ],

    'case_studies' => [
        [
            'image' => 'case-restaurants.webp',
            'title' => 'Restaurant chain POS', 'title_ar' => 'نقاط بيع موحّدة لسلسلة مطاعم', 'title_en' => 'Unified POS for a restaurant chain',
            'category_ar' => 'نقاط البيع', 'category_en' => 'POS',
            'client_name_ar' => 'سلسلة مطاعم', 'client_name_en' => 'Restaurant chain',
            'description_ar' => 'ربط فروع سلسلة مطاعم بنظام نقاط بيع واحد مع المخزون والحسابات.',
            'description_en' => 'Connecting restaurant branches to one POS with inventory and accounting.',
            'challenge_ar' => 'كل فرع يعمل بنظام منفصل، فلا تظهر المبيعات والمخزون للإدارة إلا في نهاية اليوم.',
            'solution_ar' => 'نظام نقاط بيع موحّد في كل الفروع، مرتبط بالمخزون والحسابات، مع صلاحيات للكاشير والمشرف.',
            'results_ar' => 'صارت مبيعات كل فرع ومخزونه ظاهرة للإدارة لحظة بلحظة، وتُغلق الوردية بتقرير جاهز.',
        ],
        [
            'image' => 'case-distribution.webp',
            'title' => 'Distribution company ERP', 'title_ar' => 'ERP وتطبيق مندوبين لشركة توزيع', 'title_en' => 'ERP and sales-rep app for a distributor',
            'category_ar' => 'ERP', 'category_en' => 'ERP',
            'client_name_ar' => 'شركة توزيع', 'client_name_en' => 'Distribution company',
            'description_ar' => 'نظام ERP للمخازن والحسابات، وتطبيق للمندوبين يعمل في الميدان.',
            'description_en' => 'ERP for warehouses and accounting, plus a field app for sales reps.',
            'challenge_ar' => 'طلبات المندوبين تُكتب ورقياً وتُدخل لاحقاً، فتتأخر الفواتير وتختلف أرصدة المخزون.',
            'solution_ar' => 'تطبيق للمندوبين يسجّل الطلبات والتحصيل ويعمل بدون إنترنت، ويتزامن مع نظام ERP.',
            'results_ar' => 'تصل الطلبات إلى المستودع مباشرة، وتُصدر الفواتير في نفس اليوم، وتطابقت أرصدة المخزون.',
        ],
        [
            'image' => 'case-pharmacies.webp',
            'title' => 'Pharmacy group inventory', 'title_ar' => 'مخزون بتواريخ الصلاحية لمجموعة صيدليات', 'title_en' => 'Expiry-aware inventory for pharmacies',
            'category_ar' => 'المخازن', 'category_en' => 'Inventory',
            'client_name_ar' => 'مجموعة صيدليات', 'client_name_en' => 'Pharmacy group',
            'description_ar' => 'متابعة الأصناف بتواريخ الصلاحية وتنبيهات قبل انتهائها.',
            'description_en' => 'Tracking items by expiry date with alerts before they expire.',
            'challenge_ar' => 'صعوبة متابعة تواريخ الصلاحية لآلاف الأصناف في أكثر من فرع.',
            'solution_ar' => 'نظام مخازن يتابع الدفعات وتواريخ الصلاحية، مع تنبيهات وتقارير يومية لكل فرع.',
            'results_ar' => 'تُكتشف الأصناف القريبة من الانتهاء مبكراً، وتُنقل بين الفروع قبل أن تتلف.',
        ],
    ],

    'testimonials' => [
        ['client_name_ar' => 'مالك مطعم', 'client_name_en' => 'Restaurant owner', 'client_position_ar' => 'عميل نظام نقاط البيع', 'client_position_en' => 'POS customer',
            'content_ar' => 'صار إغلاق الوردية يأخذ دقائق بدل ساعة، والتقارير واضحة لكل فرع.', 'content_en' => 'Closing a shift now takes minutes instead of an hour, with clear reports per branch.'],
        ['client_name_ar' => 'مدير مالي', 'client_name_en' => 'Finance manager', 'client_position_ar' => 'عميل نظام ERP', 'client_position_en' => 'ERP customer',
            'content_ar' => 'الحسابات والمخازن في نظام واحد، والدعم الفني يرد بسرعة عند أي استفسار.', 'content_en' => 'Accounts and inventory in one system, and support replies quickly.'],
        ['client_name_ar' => 'مدير مبيعات', 'client_name_en' => 'Sales manager', 'client_position_ar' => 'عميل تطبيق المندوبين', 'client_position_en' => 'Sales-rep app customer',
            'content_ar' => 'المندوبون يرسلون الطلبات من الميدان حتى بدون إنترنت، وتصلنا فور الاتصال.', 'content_en' => 'Reps send orders from the field even offline, and they sync as soon as they connect.'],
    ],

    'team' => [
        ['name_ar' => 'فريق التطوير', 'name_en' => 'Development', 'position_ar' => 'تطوير الأنظمة والتطبيقات', 'position_en' => 'Systems & apps development',
            'bio_ar' => 'يبني الأنظمة والتطبيقات ويطوّرها حسب احتياج كل عميل.', 'bio_en' => 'Builds and customises systems and apps for each client.'],
        ['name_ar' => 'فريق التركيب والتدريب', 'name_en' => 'Setup & Training', 'position_ar' => 'التركيب وتدريب المستخدمين', 'position_en' => 'Installation and user training',
            'bio_ar' => 'يجهّز النظام في موقع العميل ويدرّب الموظفين على استخدامه.', 'bio_en' => 'Sets up the system on site and trains staff.'],
        ['name_ar' => 'فريق الدعم الفني', 'name_en' => 'Technical Support', 'position_ar' => 'الدعم والمتابعة', 'position_en' => 'Support and follow-up',
            'bio_ar' => 'يتابع العملاء بعد التشغيل ويحل أي مشكلة بسرعة.', 'bio_en' => 'Follows up after go-live and resolves issues quickly.'],
        ['name_ar' => 'فريق المبيعات', 'name_en' => 'Sales', 'position_ar' => 'الاستشارات والعروض', 'position_en' => 'Consulting and demos',
            'bio_ar' => 'يدرس احتياج العميل ويقترح النظام المناسب ويقدّم العروض التجريبية.', 'bio_en' => 'Studies needs, recommends the right system and runs demos.'],
    ],

    'faqs' => [
        ['question_ar' => 'هل يعمل النظام بدون إنترنت؟', 'question_en' => 'Does the system work offline?',
            'answer_ar' => 'نعم، نقاط البيع وتطبيق المندوبين يعملان بدون إنترنت، وتتم مزامنة البيانات تلقائياً عند عودة الاتصال.', 'answer_en' => 'Yes. POS and the sales-rep app work offline and sync automatically when the connection returns.'],
        ['question_ar' => 'هل يمكن ربط أكثر من فرع؟', 'question_en' => 'Can several branches be connected?',
            'answer_ar' => 'نعم، يمكن إدارة كل الفروع من نظام واحد مع تقارير لكل فرع وتقارير مجمعة للإدارة.', 'answer_en' => 'Yes, all branches can be managed from one system with per-branch and combined reports.'],
        ['question_ar' => 'هل تقدمون التدريب على النظام؟', 'question_en' => 'Do you provide training?',
            'answer_ar' => 'نعم، يقوم فريق التركيب بتدريب الموظفين عند التشغيل، ويتابع فريق الدعم بعد ذلك.', 'answer_en' => 'Yes, our setup team trains your staff at go-live and support follows up afterwards.'],
        ['question_ar' => 'هل يمكن تعديل النظام حسب طبيعة عملي؟', 'question_en' => 'Can the system be customised?',
            'answer_ar' => 'نعم، نطوّر الأنظمة بأنفسنا، لذلك يمكن تخصيص الشاشات والتقارير حسب احتياجك.', 'answer_en' => 'Yes. We build our own systems, so screens and reports can be tailored to your needs.'],
        ['question_ar' => 'كيف أطلب عرضاً تجريبياً؟', 'question_en' => 'How do I request a demo?',
            'answer_ar' => 'من صفحة «اطلب عرضاً تجريبياً» أو عبر واتساب، وسنتواصل معك لتحديد موعد مناسب.', 'answer_en' => 'Use the "Request a demo" page or WhatsApp and we will contact you to set a time.'],
        ['question_ar' => 'هل بياناتي آمنة؟', 'question_en' => 'Is my data safe?',
            'answer_ar' => 'نعتمد صلاحيات للمستخدمين ونسخاً احتياطياً منتظماً، ويمكن تشغيل النظام على خوادمك أو على السحابة.', 'answer_en' => 'We use user permissions and regular backups, and the system can run on your servers or in the cloud.'],
    ],

    'posts' => [
        [
            'slug' => 'how-to-choose-accounting-system', 'image' => 'blog-accounting.webp', 'days_ago' => 3,
            'title_ar' => 'كيف تختار نظام محاسبة مناسباً لشركتك في اليمن', 'title_en' => 'How to choose an accounting system for your company in Yemen',
            'excerpt_ar' => 'خمسة أسئلة تساعدك على اختيار نظام محاسبة يناسب حجم شركتك وطريقة عملها.',
            'body_ar' => "اختيار نظام المحاسبة قرار يؤثر على عمل الشركة لسنوات. هذه أسئلة عملية تساعدك قبل أن تقرر.\n\n## 1. هل يدعم تعدد العملات؟\nكثير من الشركات في اليمن تتعامل بأكثر من عملة، لذلك تأكد أن النظام يسجّل العملات وأسعار الصرف بوضوح.\n\n## 2. هل يرتبط بالمخازن والمبيعات؟\nالنظام المنفصل يعني إدخال البيانات مرتين. الأفضل أن تتحول الفاتورة تلقائياً إلى قيد محاسبي وحركة مخزون.\n\n## 3. هل يعمل مع أكثر من فرع؟\nإذا كان لديك فروع، تحتاج تقارير لكل فرع وتقريراً مجمعاً للإدارة.\n\n## 4. ماذا عن الدعم الفني؟\nاسأل: من سيدرّب الموظفين؟ ومن سيرد عند حدوث مشكلة؟\n\n## 5. هل يمكن تخصيصه؟\nكل نشاط له طبيعته، والنظام الجيد يسمح بتعديل التقارير والشاشات.\n\n> نصيحة: اطلب عرضاً تجريبياً على بياناتك الحقيقية قبل أن تقرر.",
        ],
        [
            'slug' => 'pos-for-restaurants', 'image' => 'blog-restaurant-pos.webp', 'days_ago' => 10,
            'title_ar' => 'نظام نقاط البيع للمطاعم: ما الذي تحتاجه فعلاً؟', 'title_en' => 'POS for restaurants: what do you really need?',
            'excerpt_ar' => 'من الطلبات والمطبخ إلى إغلاق الوردية: المزايا الأهم في نظام نقاط بيع لمطعم.',
            'body_ar' => "المطعم يختلف عن المتجر، ونظام نقاط البيع يجب أن يناسب سرعة العمل فيه.\n\n## سرعة إدخال الطلب\nشاشة لمس واضحة، وأصناف مقسمة حسب الفئات، وإضافات على الطلب بسهولة.\n\n## الربط مع المطبخ\nطباعة الطلب في المطبخ تلقائياً تقلل الأخطاء والتأخير.\n\n## إغلاق الوردية\nتقرير جاهز بالمبيعات والنقدية لكل كاشير في نهاية الوردية.\n\n## المخزون والمواد الخام\nربط الأصناف بالمواد الخام يساعدك على معرفة الاستهلاك الحقيقي.\n\n- طلبات الصالة والسفري والتوصيل\n- صلاحيات للكاشير والمشرف\n- تقارير الأصناف الأكثر مبيعاً",
        ],
        [
            'slug' => 'working-offline', 'image' => 'blog-offline.webp', 'days_ago' => 18,
            'title_ar' => 'العمل بدون إنترنت: كيف تستمر مبيعاتك عند انقطاع الاتصال', 'title_en' => 'Working offline: keep selling when the connection drops',
            'excerpt_ar' => 'انقطاع الإنترنت لا يجب أن يوقف البيع. هكذا تعمل المزامنة التلقائية.',
            'body_ar' => "انقطاع الإنترنت أو الكهرباء أمر وارد، والنظام الجيد يجب ألا يوقف البيع بسببه.\n\n## كيف يعمل النظام بدون اتصال؟\nتُحفظ الفواتير والطلبات على الجهاز نفسه، ويستمر العمل بشكل طبيعي.\n\n## ماذا يحدث عند عودة الاتصال؟\nتُرسل البيانات تلقائياً إلى الخادم الرئيسي وتُحدَّث التقارير والمخزون دون تدخل.\n\n## من يستفيد أكثر؟\n- المطاعم والمحلات في الأوقات المزدحمة\n- المندوبون في الميدان\n- الفروع في المناطق ضعيفة التغطية",
        ],
    ],
];
