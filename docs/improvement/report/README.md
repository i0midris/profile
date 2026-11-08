# مصدر تقرير الإنجاز

التقرير مكتوب بـ HTML بنفس تصميم تقرير الفحص، ويُحوَّل إلى PDF بمتصفح Chrome:

```bash
chrome --headless=new --no-pdf-header-footer --print-to-pdf="report.pdf" "file:///<المسار>/report.html"
```

بعد النشر وقياس المؤشرات (البند 4.1) تُحدَّث خانات «بعد التنفيذ» في `report.html` ثم يُعاد توليد الملف.
