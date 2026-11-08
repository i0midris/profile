# ملاحظات النشر على الخادم

ما يلزم تنفيذه على الخادم عند دمج فرع `abdalsalam` ونشره.

## بعد كل نشر

نفّذ السكربت الجاهز من مجلد المشروع:

```bash
bash scripts/deploy.sh
```

يقوم السكربت بما يلي: وضع الصيانة، ثم `composer install --no-dev -o`، ثم `npm ci && npm run build`، ثم `migrate --force`، ثم `php artisan optimize`، ثم إعادة تحميل PHP-FPM، ثم إنهاء وضع الصيانة.

## المرحلة الأولى

| البند | ما يلزم على الخادم |
|---|---|
| 1.5 | migration يجعل البريد اختيارياً في رسائل التواصل (`contact_messages.email`) |
| 1.6 | migration يغيّر نص الزر «ابدأ الآن مجاناً» إلى «اطلب عرضاً تجريبياً»، فقط إذا كان النص ما زال القيمة الأصلية |
| 1.7 | migration يضيف حقل صورة المشاركة (`company_settings.og_image`). الصورة الافتراضية موجودة في `public/images/og-default.png` |
| 1.8 | **robots.txt:** النسخة المنشورة حالياً لا تحتوي سطر `Sitemap:` مع أنه موجود في المستودع، فيجب التأكد من أن النشر يحدّث مجلد `public/` كاملاً. **تحويل www:** صار يتم داخل التطبيق (301)، ويمكن نقله إلى nginx لأداء أفضل: `server { server_name www.albaseetsoft-ye.com; return 301 https://albaseetsoft-ye.com$request_uri; }`. **HSTS:** يرسله nginx أصلاً، فلم يُكرَّر في التطبيق |

## الصور (البند 2.6)

- كل صورة تُرفع من لوحة التحكم تُحفظ تلقائياً بصيغة **WebP**، ويُصغَّر عرضها إن كان أكبر من الحد المناسب (الشعار 600px، صور الفريق 800px، الخدمات 1200px، الخلفية 1920px). يحتاج هذا إضافة **GD** في PHP على الخادم، وبدونها تُحفظ الصورة كما هي دون خطأ.
- الصور المرفوعة سابقاً تُحوَّل بأمر واحد (موجود في `scripts/deploy.sh`):
  ```bash
  php artisan images:optimize --dry-run          # عرض ما سيُحوَّل فقط
  php artisan images:optimize                    # التحويل (تبقى الأصول القديمة)
  php artisan images:optimize --delete-originals # التحويل وحذف الأصول
  ```
- **الشعار بصيغة SVG:** الشعار الحالي صورة JPG بحجم 177px. تحويله إلى SVG يحتاج ملف التصميم الأصلي (AI أو EPS أو SVG) من الشركة، لأن تتبّع صورة صغيرة لا ينتج شعاراً مطابقاً. لوحة التحكم تقبل رفع SVG مباشرة.

## المرحلة الثانية: السرعة (البند 2.2)

التقرير قاس زمن استجابة الخادم (TTFB) بين 2.4 و9 ثوانٍ، والمستهدف أقل من 0.8 ثانية. البند 2.1 أزال عمل قاعدة البيانات وبناء القالب من صفحات العرض. الباقي إعدادات على الخادم نفسه:

### 1) ملف `.env` في الإنتاج

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://albaseetsoft-ye.com
LOG_LEVEL=warning

# أسرع من database: لا يكتب في قاعدة البيانات مع كل زيارة
CACHE_STORE=file          # أو redis إن كان متوفراً
SESSION_DRIVER=file       # أو redis

PAGE_CACHE=true           # تخزين الصفحات الجاهزة (يعمل تلقائياً في الإنتاج)
```

> كان `SESSION_DRIVER=database` و`CACHE_STORE=database`، فكل زيارة تكتب في قاعدة البيانات. هذا من أهم أسباب البطء.

### 2) تفعيل OPcache

بدون OPcache يعيد PHP قراءة وترجمة مئات الملفات في كل طلب. على جهاز التطوير يستغرق تشغيل Laravel وحده (مسار فارغ) نحو 0.5 ثانية بدونه. أضف إلى `php.ini` الخاص بـ PHP-FPM:

```ini
opcache.enable=1
opcache.memory_consumption=192
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0   ; سكربت النشر يعيد تحميل PHP-FPM بعد كل نشر
opcache.jit=tracing
opcache.jit_buffer_size=64M
```

للتحقق: `php -i | grep opcache.enable`

### 3) مراجعة الاستضافة وقاعدة البيانات

- تأكد أن قاعدة البيانات على نفس الخادم أو الشبكة (`DB_HOST=127.0.0.1`) وليست على خادم بعيد.
- راقب استهلاك المعالج والذاكرة. التذبذب بين 2.4 و9 ثوانٍ يشير غالباً إلى خادم مشترك مزدحم أو قاعدة بيانات بعيدة.
- جداول المحتوى صغيرة (بضع عشرات من الصفوف)، فلا حاجة لفهارس إضافية حالياً.

### 4) Cloudflare (CDN مجاني)

1. أضف النطاق في Cloudflare وغيّر خوادم الأسماء (Nameservers) عند المسجّل.
2. **SSL/TLS ← Full (strict)**.
3. **Caching ← Cache Rules:** قاعدة لـ `albaseetsoft-ye.com/build/*` و`/images/*` و`/storage/*` بمدة تخزين شهر، فهي ملفات ثابتة أسماؤها تتغير مع كل بناء.
4. صفحات العرض ترسل `Cache-Control: public, max-age=120` بلا Cookies، فيمكن إضافة قاعدة «Cache Everything» لـ `/ar*` و`/en*`، **مع استثناء** `/ar/contact` و`/en/contact` و`/ar/jobs` و`/en/jobs` و`/admin*`.
5. **Speed ← Optimization:** تفعيل Brotli وHTTP/3.

### 5) القياس بعد التطبيق

```bash
curl -s -o /dev/null -w "TTFB: %{time_starttransfer}s\n" https://albaseetsoft-ye.com/ar
```

ثم PageSpeed Insights للرئيسية على الجوال والحاسوب، وتُسجَّل النتائج في البند 4.1.

## المرحلة الثالثة: المحتوى (البنود 3.1 – 3.7)

- تُنشأ الجداول الجديدة بأمر `php artisan migrate --force` الموجود في سكربت النشر: المنتجات، وشعارات العملاء، وطلبات العرض التجريبي، والأسئلة الشائعة، والمقالات، وحقول دراسة الحالة، وحقول الموقع وساعات العمل وأدوات Google.
- كل الأقسام الجديدة **مخفية تلقائياً** حتى يُنشر فيها محتوى من لوحة التحكم، فلا يظهر أي قسم فارغ بعد النشر.
- زر «اطلب عرضاً تجريبياً» يتحول إلى صفحة `/demo` بـ migration، فقط إذا كان رابطه ما زال القيمة الأصلية `/contact`.
- **إشعارات البريد** لطلبات العرض ورسائل التواصل تحتاج إعدادات SMTP في ملف `.env` (`MAIL_MAILER=smtp`، `MAIL_HOST`، `MAIL_USERNAME`، `MAIL_PASSWORD`). بدونها تُحفظ الطلبات في لوحة التحكم دون إرسال بريد.
- **Google:** بعد إضافة رمز Search Console من لوحة التحكم، أرسل خريطة الموقع `https://albaseetsoft-ye.com/sitemap.xml` من Search Console ← Sitemaps.
