"""Generates the illustrative images used by `php artisan preview:content`.

Run once (already committed output lives in database/preview/images):
    python database/preview/make_images.py

Every image is clearly marked "صورة توضيحية" (illustration). They exist only to
preview the layout until the company uploads real screenshots and photos.
"""
import os, random
from PIL import Image, ImageDraw, ImageFont, ImageFilter
import arabic_reshaper
from bidi.algorithm import get_display

OUT = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'images')
os.makedirs(OUT, exist_ok=True)
FONT_DIR = 'C:/Windows/Fonts/'
NAVY, NAVY_D, RED = (26, 46, 109), (12, 22, 56), (228, 25, 31)
BLUE = (59, 111, 212)
random.seed(7)


def F(size, bold=False):
    return ImageFont.truetype(FONT_DIR + ('segoeuib.ttf' if bold else 'segoeui.ttf'), size)


def ar(t):
    return get_display(arabic_reshaper.reshape(t))


def rtext(d, x_right, y, text, font, fill):
    t = ar(text)
    d.text((x_right - d.textlength(t, font=font), y), t, font=font, fill=fill)


def watermark(img):
    d = ImageDraw.Draw(img)
    f = F(max(14, img.width // 70))
    t = ar('صورة توضيحية')
    w = d.textlength(t, font=f)
    pad = 10
    d.rounded_rectangle((16, img.height - f.size - 2 * pad - 16, 16 + w + 2 * pad, img.height - 16), radius=10, fill=(0, 0, 0, 140))
    d.text((16 + pad, img.height - f.size - pad - 20), t, font=f, fill=(255, 255, 255))
    return img


def gradient(w, h, top=NAVY, bottom=NAVY_D):
    img = Image.new('RGB', (w, h), top)
    d = ImageDraw.Draw(img)
    for y in range(h):
        t = y / h
        d.line([(0, y), (w, y)], fill=tuple(int(top[i] * (1 - t) + bottom[i] * t) for i in range(3)))
    return img


def glow(img, spots):
    layer = Image.new('RGBA', img.size, (0, 0, 0, 0))
    g = ImageDraw.Draw(layer)
    for (x, y, r, col) in spots:
        g.ellipse((x - r, y - r, x + r, y + r), fill=col)
    layer = layer.filter(ImageFilter.GaussianBlur(80))
    return Image.alpha_composite(img.convert('RGBA'), layer)


# ---------------------------------------------------------------- app screenshots
def app_screen(title, menu, kpis, table_head, rows, chart='bars', accent=RED, w=1280, h=800, mark=True):
    img = Image.new('RGB', (w, h), (244, 246, 251))
    d = ImageDraw.Draw(img)
    side = 230
    # sidebar (right side, RTL)
    d.rectangle((w - side, 0, w, h), fill=NAVY)
    d.rounded_rectangle((w - 62, 28, w - 24, 66), radius=10, fill=accent)
    rtext(d, w - 76, 32, 'البسيط سوفت', F(20, True), (255, 255, 255))
    for i, item in enumerate(menu):
        y = 110 + i * 52
        if i == 0:
            d.rounded_rectangle((w - side + 14, y - 8, w - 14, y + 34), radius=10, fill=(255, 255, 255, 30) if False else (44, 70, 140))
        d.ellipse((w - 46, y + 6, w - 32, y + 20), fill=(255, 255, 255) if i == 0 else (150, 166, 205))
        rtext(d, w - 58, y, item, F(17, i == 0), (255, 255, 255) if i == 0 else (196, 206, 230))
    # top bar
    main_r = w - side - 36
    d.rectangle((0, 0, w - side, 78), fill=(255, 255, 255))
    rtext(d, main_r, 20, title, F(26, True), NAVY)
    d.rounded_rectangle((36, 20, 300, 58), radius=12, fill=(240, 242, 248))
    rtext(d, 286, 26, 'بحث…', F(17), (140, 148, 168))
    # KPI cards
    cw = (main_r - 36 - 3 * 20) / 4
    for i, (label, value, col) in enumerate(kpis):
        x2 = main_r - i * (cw + 20)
        x1 = x2 - cw
        d.rounded_rectangle((x1, 104, x2, 214), radius=16, fill=(255, 255, 255))
        d.rounded_rectangle((x2 - 54, 124, x2 - 18, 160), radius=10, fill=col)
        rtext(d, x2 - 70, 124, label, F(16), (110, 118, 140))
        rtext(d, x2 - 70, 152, value, F(30, True), NAVY)
    # chart card
    cx1, cx2 = main_r - 470, main_r
    d.rounded_rectangle((cx1, 236, cx2, 560), radius=16, fill=(255, 255, 255))
    rtext(d, cx2 - 24, 252, 'الأداء خلال الأسبوع', F(18, True), NAVY)
    base = 520
    if chart == 'bars':
        vals = [random.randint(60, 220) for _ in range(7)]
        bw = (cx2 - cx1 - 80) / 7
        for i, v in enumerate(vals):
            bx = cx1 + 40 + i * bw
            d.rounded_rectangle((bx + 10, base - v, bx + bw - 10, base), radius=8, fill=accent if i == 5 else (190, 202, 232))
    else:
        pts = [(cx1 + 40 + i * (cx2 - cx1 - 80) / 9, base - random.randint(50, 220)) for i in range(10)]
        d.line(pts, fill=accent, width=5, joint='curve')
        for p in pts:
            d.ellipse((p[0] - 6, p[1] - 6, p[0] + 6, p[1] + 6), fill=(255, 255, 255), outline=accent, width=3)
    # side list card
    lx1, lx2 = 36, cx1 - 20
    d.rounded_rectangle((lx1, 236, lx2, 560), radius=16, fill=(255, 255, 255))
    rtext(d, lx2 - 24, 252, 'آخر العمليات', F(18, True), NAVY)
    for i in range(5):
        y = 300 + i * 50
        d.ellipse((lx2 - 60, y, lx2 - 26, y + 34), fill=(232, 237, 249))
        d.rounded_rectangle((lx2 - 260, y + 4, lx2 - 76, y + 14), radius=5, fill=(212, 219, 236))
        d.rounded_rectangle((lx2 - 200, y + 22, lx2 - 76, y + 30), radius=4, fill=(232, 236, 245))
        d.rounded_rectangle((lx1 + 20, y + 8, lx1 + 90, y + 28), radius=10, fill=(229, 245, 236) if i % 3 else (253, 238, 239))
    # table
    d.rounded_rectangle((36, 584, main_r, h - 20), radius=16, fill=(255, 255, 255))
    colw = (main_r - 36 - 40) / len(table_head)
    for j, head in enumerate(table_head):
        rtext(d, main_r - 20 - j * colw, 600, head, F(16, True), (110, 118, 140))
    for i, row in enumerate(rows):
        y = 636 + i * 34
        d.line((56, y - 8, main_r - 20, y - 8), fill=(236, 239, 245), width=1)
        for j, cell in enumerate(row):
            rtext(d, main_r - 20 - j * colw, y, cell, F(16), (60, 66, 82))
    img = img.convert('RGBA')
    return (watermark(img) if mark else img).convert('RGB')


def cover(title, subtitle, screen):
    w, h = 1200, 675
    img = gradient(w, h)
    img = glow(img, [(200, 120, 260, (59, 111, 212, 120)), (1050, 600, 240, (228, 25, 31, 90))])
    d = ImageDraw.Draw(img)
    # device mock with the screenshot
    shot = screen.resize((640, 400), Image.LANCZOS)
    frame = Image.new('RGBA', (664, 446), (0, 0, 0, 0))
    fd = ImageDraw.Draw(frame)
    fd.rounded_rectangle((0, 0, 664, 446), radius=22, fill=(18, 26, 52))
    frame.paste(shot, (12, 34))
    for i, c in enumerate([(255, 95, 86), (255, 189, 46), (39, 201, 63)]):
        fd.ellipse((18 + i * 20, 12, 30 + i * 20, 24), fill=c)
    shadow = Image.new('RGBA', (w, h), (0, 0, 0, 0))
    ImageDraw.Draw(shadow).rounded_rectangle((60, 170, 724, 616), radius=22, fill=(0, 0, 0, 120))
    img = Image.alpha_composite(img, shadow.filter(ImageFilter.GaussianBlur(24)))
    img.alpha_composite(frame, (52, 150))
    d = ImageDraw.Draw(img)
    d.rectangle((0, 0, w * 0.58, 8), fill=(31, 78, 140))
    d.rectangle((w * 0.58, 0, w, 8), fill=RED)
    rtext(d, w - 60, 210, title, F(54, True), (255, 255, 255))
    d.rectangle((w - 160, 300, w - 60, 306), fill=RED)
    y = 330
    for line in subtitle:
        rtext(d, w - 60, y, line, F(26), (210, 220, 240))
        y += 44
    return watermark(img).convert('RGB')


def save(img, name, q=80):
    img.save(os.path.join(OUT, name), 'WEBP', quality=q, method=6)


products = {
    'pos': dict(
        title='نظام نقاط البيع', sub=['بيع أسرع وفواتير دقيقة', 'للمتاجر والمطاعم والصيدليات'],
        menu=['لوحة البيع', 'الفواتير', 'الأصناف', 'العملاء', 'التقارير', 'الفروع'],
        kpis=[('مبيعات اليوم', '128,400'), ('عدد الفواتير', '214'), ('متوسط الفاتورة', '600'), ('الأصناف', '1,350')],
        head=['رقم الفاتورة', 'العميل', 'الكاشير', 'الإجمالي'], rows=[['10245', 'نقدي', 'الوردية الأولى', '4,500'], ['10244', 'عميل آجل', 'الوردية الأولى', '12,800'], ['10243', 'نقدي', 'الوردية الأولى', '2,250'], ['10242', 'نقدي', 'الوردية الأولى', '7,900']],
        title2='الفواتير', chart2='line'),
    'erp': dict(
        title='نظام ERP والمحاسبة', sub=['الحسابات والمخازن والمبيعات', 'في نظام واحد متكامل'],
        menu=['لوحة التحكم', 'الحسابات', 'المبيعات', 'المشتريات', 'المخازن', 'التقارير'],
        kpis=[('الإيرادات', '4.2M'), ('المصروفات', '2.9M'), ('الذمم المدينة', '640K'), ('قيمة المخزون', '1.8M')],
        head=['القيد', 'الحساب', 'مدين', 'دائن'], rows=[['JV-881', 'الصندوق', '250,000', '—'], ['JV-880', 'المبيعات', '—', '250,000'], ['JV-879', 'المشتريات', '90,000', '—'], ['JV-878', 'الموردون', '—', '90,000']],
        title2='القيود اليومية', chart2='line'),
    'hr': dict(
        title='الموارد البشرية والرواتب', sub=['الحضور والإجازات والرواتب', 'بدقة ومن مكان واحد'],
        menu=['الموظفون', 'الحضور', 'الإجازات', 'الرواتب', 'السلف', 'التقارير'],
        kpis=[('الموظفون', '86'), ('حاضرون اليوم', '79'), ('إجازات', '4'), ('مسير الشهر', 'جاهز')],
        head=['الموظف', 'القسم', 'الحضور', 'الحالة'], rows=[['موظف 1', 'المبيعات', '08:02', 'حاضر'], ['موظف 2', 'المخازن', '08:15', 'حاضر'], ['موظف 3', 'الحسابات', '—', 'إجازة'], ['موظف 4', 'الدعم الفني', '07:58', 'حاضر']],
        title2='مسير الرواتب', chart2='bars'),
    'store': dict(
        title='المتاجر الإلكترونية', sub=['متجر مرتبط بالمخزون والحسابات', 'وإدارة الطلبات بسهولة'],
        menu=['الطلبات', 'المنتجات', 'العملاء', 'الشحن', 'الدفع', 'التقارير'],
        kpis=[('طلبات جديدة', '37'), ('قيد التوصيل', '12'), ('مكتملة', '420'), ('المنتجات', '640')],
        head=['الطلب', 'العميل', 'المدينة', 'الحالة'], rows=[['#5521', 'عميل', 'صنعاء', 'جديد'], ['#5520', 'عميل', 'عدن', 'قيد التوصيل'], ['#5519', 'عميل', 'تعز', 'مكتمل'], ['#5518', 'عميل', 'صنعاء', 'مكتمل']],
        title2='المنتجات', chart2='bars'),
    'sales-rep': dict(
        title='تطبيق المندوبين', sub=['طلبات وتحصيل في الميدان', 'يعمل حتى بدون إنترنت'],
        menu=['الزيارات', 'الطلبات', 'التحصيل', 'العملاء', 'المسارات', 'المزامنة'],
        kpis=[('زيارات اليوم', '18'), ('طلبات', '11'), ('تحصيل', '96,000'), ('بانتظار المزامنة', '0')],
        head=['العميل', 'المنطقة', 'الطلب', 'التحصيل'], rows=[['بقالة', 'الحي الأول', '22,000', '15,000'], ['سوبرماركت', 'الحي الثاني', '48,500', '30,000'], ['صيدلية', 'الحي الثالث', '9,800', '9,800'], ['مخبز', 'الحي الرابع', '6,400', '—']],
        title2='المسار اليومي', chart2='line'),
}
accents = [RED, BLUE, (31, 150, 100), (200, 120, 20), (130, 80, 200)]
for (slug, p), acc in zip(products.items(), accents):
    kp = [(l, v, c) for (l, v), c in zip(p['kpis'], [acc, BLUE, (31, 150, 100), (200, 120, 20)])]
    s1 = app_screen(p['title'], p['menu'], kp, p['head'], p['rows'], 'bars', acc)
    menu2 = p['menu'][1:] + p['menu'][:1]
    s2 = app_screen(p['title2'], menu2, kp[::-1], p['head'], p['rows'][::-1], p['chart2'], acc)
    save(s1, f'product-{slug}-1.webp')
    save(s2, f'product-{slug}-2.webp')
    clean = app_screen(p['title'], p['menu'], kp, p['head'], p['rows'], 'bars', acc, mark=False)
    save(cover(p['title'], p['sub'], clean), f'product-{slug}-cover.webp')

# ---------------------------------------------------------------- portfolio / blog covers
def banner(title, lines, name, w=1200, h=800, spots=None, accent=RED):
    img = gradient(w, h)
    img = glow(img, spots or [(w * 0.8, h * 0.25, 260, (59, 111, 212, 110)), (w * 0.15, h * 0.85, 240, (228, 25, 31, 80))])
    d = ImageDraw.Draw(img)
    # decorative cards
    for i in range(3):
        x, y = 70 + i * 60, 120 + i * 70
        d.rounded_rectangle((x, y, x + 360, y + 220), radius=24, fill=(255, 255, 255, 18) if False else (40, 62, 128), outline=(80, 106, 180), width=2)
        d.rounded_rectangle((x + 24, y + 24, x + 84, y + 84), radius=14, fill=accent if i == 2 else (70, 96, 168))
        d.rounded_rectangle((x + 104, y + 34, x + 320, y + 50), radius=8, fill=(90, 116, 186))
        d.rounded_rectangle((x + 104, y + 62, x + 260, y + 74), radius=6, fill=(70, 96, 168))
    rtext(d, w - 70, h * 0.36, title, F(56 if w > 1100 else 50, True), (255, 255, 255))
    d.rectangle((w - 170, h * 0.36 + 92, w - 70, h * 0.36 + 98), fill=accent)
    y = h * 0.36 + 120
    for line in lines:
        rtext(d, w - 70, y, line, F(28), (205, 216, 240))
        y += 46
    save(watermark(img).convert('RGB'), name)

banner('سلسلة مطاعم', ['نقاط بيع موحّدة لكل الفروع'], 'case-restaurants.webp')
banner('شركة توزيع', ['ERP ومخازن وتطبيق مندوبين'], 'case-distribution.webp', accent=(31, 150, 100))
banner('مجموعة صيدليات', ['مخزون بالتواريخ وتقارير يومية'], 'case-pharmacies.webp', accent=BLUE)
banner('كيف تختار نظام محاسبة', ['دليل عملي للشركات في اليمن'], 'blog-accounting.webp', 1200, 630)
banner('نقاط البيع للمطاعم', ['ما الذي تحتاجه فعلاً؟'], 'blog-restaurant-pos.webp', 1200, 630, accent=(200, 120, 20))
banner('العمل بدون إنترنت', ['كيف تستمر مبيعاتك عند انقطاع الاتصال'], 'blog-offline.webp', 1200, 630, accent=BLUE)

# ---------------------------------------------------------------- sector tiles (client logos strip)
sectors = [('مطاعم', RED), ('صيدليات', BLUE), ('سوبرماركت', (31, 150, 100)), ('مستودعات', (200, 120, 20)), ('مدارس', (130, 80, 200)), ('شركات توزيع', NAVY)]
for i, (name, col) in enumerate(sectors, 1):
    img = Image.new('RGBA', (400, 200), (255, 255, 255, 0))
    d = ImageDraw.Draw(img)
    d.rounded_rectangle((300, 64, 372, 136), radius=18, fill=col)
    d.ellipse((322, 86, 350, 114), fill=(255, 255, 255))
    rtext(d, 284, 70, name, F(44, True), (30, 40, 70))
    img.save(os.path.join(OUT, f'sector-{i}.png'), optimize=True)

print('images:', len(os.listdir(OUT)))
