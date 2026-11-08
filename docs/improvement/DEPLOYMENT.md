# ملاحظات النشر على الخادم

ما يلزم تنفيذه على الخادم عند دمج فرع `abdalsalam` ونشره.

## بعد كل نشر

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan optimize        # config + route + view + event cache
```

## المرحلة الأولى

| البند | ما يلزم على الخادم |
|---|---|
| 1.5 | migration يجعل البريد اختيارياً في رسائل التواصل (`contact_messages.email`) |
| 1.6 | migration يغيّر نص الزر «ابدأ الآن مجاناً» إلى «اطلب عرضاً تجريبياً»، فقط إذا كان النص ما زال القيمة الأصلية |
| 1.7 | migration يضيف حقل صورة المشاركة (`company_settings.og_image`). الصورة الافتراضية موجودة في `public/images/og-default.png` |
| 1.8 | **robots.txt:** النسخة المنشورة حالياً لا تحتوي سطر `Sitemap:` مع أنه موجود في المستودع، فيجب التأكد من أن النشر يحدّث مجلد `public/` كاملاً. **تحويل www:** صار يتم داخل التطبيق (301)، ويمكن نقله إلى nginx لأداء أفضل: `server { server_name www.albaseetsoft-ye.com; return 301 https://albaseetsoft-ye.com$request_uri; }`. **HSTS:** يرسله nginx أصلاً، فلم يُكرَّر في التطبيق |

## المرحلة الثانية

تُضاف عند تنفيذ البند 2.2.
