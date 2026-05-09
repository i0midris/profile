<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use App\Models\CompanySetting;
use App\Models\HeroSection;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->clearDemoContent();

        $this->seedCompanySettings();
        $this->seedHeroSection();
        $this->seedAboutSection();
        $this->seedServices();

        // Missing from docs source and intentionally skipped:
        // - Team members
        // - Portfolio projects/images
        // - Client testimonials
    }

    private function clearDemoContent(): void
    {
        Service::query()->delete();
        TeamMember::query()->delete();
        Portfolio::query()->delete();
        Testimonial::query()->delete();

        AboutSection::query()->delete();
        HeroSection::query()->delete();
        CompanySetting::query()->delete();
    }

    private function seedCompanySettings(): void
    {
        $settings = CompanySetting::query()->firstOrNew();
        $settings->fill([
            'company_name' => 'Albaseet Soft Company for Systems & Applications',
            'company_name_en' => 'Albaseet Soft Company for Systems & Applications',
            'company_name_ar' => 'شركة البسيط سوفت للأنظمة والتطبيقات',
            'tagline' => 'Integrated ERP and business software solutions for Yemeni companies.',
            'tagline_en' => 'Integrated ERP and business software solutions for Yemeni companies.',
            'tagline_ar' => 'حلول ERP وبرمجيات أعمال متكاملة للشركات اليمنية.',
            'description' => 'A specialized company in administrative systems, business applications, and digital solutions that help organizations run more efficiently and achieve sustainable growth.',
            'description_en' => 'A specialized company in administrative systems, business applications, and digital solutions that help organizations run more efficiently and achieve sustainable growth.',
            'description_ar' => 'شركة متخصصة في تطوير الأنظمة الإدارية وتطبيقات الأعمال والحلول الرقمية التي تساعد الشركات والمؤسسات على إدارة أعمالها بكفاءة أعلى وتحقيق نمو مستدام.',
            'email' => 'info@albaseetsoft-ye.com',
            'phone' => '00967-777335118',
            'address' => 'Yemen - Sana\'a',
            'address_en' => 'Yemen - Sana\'a',
            'address_ar' => 'اليمن - صنعاء',
            'facebook' => 'https://www.facebook.com/albaseetsoft.ye',
            'twitter' => 'https://x.com/albaseetsoft',
            'instagram' => 'https://instagram.com/albaseet_soft',
            'linkedin' => 'https://www.linkedin.com/company/albaseetsoft/',
            'youtube' => 'https://www.youtube.com/@AlbaseetSoft',
            'whatsapp' => '00967-777335118',
            'show_about_page' => true,
            'show_services_page' => true,
            'show_team_page' => false,
            'show_portfolio_page' => false,
            'show_testimonials_page' => false,
            'show_contact_page' => true,
        ]);
        $settings->save();
    }

    private function seedHeroSection(): void
    {
        $hero = HeroSection::query()->firstOrNew();
        $hero->fill([
            'title' => 'Smart ERP Systems That Run Your Business Efficiently',
            'title_en' => 'Smart ERP Systems That Run Your Business Efficiently',
            'title_ar' => 'أنظمة ERP ذكية تُدير أعمالك بكفاءة',
            'subtitle' => 'Leading Company in Yemen',
            'subtitle_en' => 'Leading Company in Yemen',
            'subtitle_ar' => 'الشركة الرائدة في اليمن',
            'description' => 'Integrated software solutions tailored for Yemeni businesses, from accounting and inventory to POS and HR management.',
            'description_en' => 'Integrated software solutions tailored for Yemeni businesses, from accounting and inventory to POS and HR management.',
            'description_ar' => 'حلول برمجية متكاملة مصممة خصيصاً للشركات اليمنية — من المحاسبة والمستودعات إلى نقاط البيع وإدارة الموارد البشرية.',
            'button_text' => 'Start Free Now',
            'button_text_en' => 'Start Free Now',
            'button_text_ar' => 'ابدأ الآن مجاناً',
            'button_link' => '/contact',
            'button_text_secondary' => 'Browse Systems',
            'button_text_secondary_en' => 'Browse Systems',
            'button_text_secondary_ar' => 'استعرض الأنظمة',
            'button_link_secondary' => '/services',
            'is_active' => true,
        ]);
        $hero->save();
    }

    private function seedAboutSection(): void
    {
        $about = AboutSection::query()->firstOrNew();
        $about->fill([
            'title' => 'Building the Future of Digital Business in Yemen',
            'title_en' => 'Building the Future of Digital Business in Yemen',
            'title_ar' => 'نبني مستقبل الأعمال الرقمية في اليمن',
            'content' => 'Albaseet Soft Company for Systems & Applications specializes in administrative systems, business applications, and digital solutions that help companies and institutions run more efficiently and achieve sustainable growth. We deliver advanced technology solutions built on modern software development, ERP systems, and smart applications to support digital transformation and streamline operations.',
            'content_en' => 'Albaseet Soft Company for Systems & Applications specializes in administrative systems, business applications, and digital solutions that help companies and institutions run more efficiently and achieve sustainable growth. We deliver advanced technology solutions built on modern software development, ERP systems, and smart applications to support digital transformation and streamline operations.',
            'content_ar' => 'شركة البسيط سوفت للأنظمة والتطبيقات شركة متخصصة في تطوير الأنظمة الإدارية وتطبيقات الأعمال والحلول الرقمية التي تساعد الشركات والمؤسسات على إدارة أعمالها بكفاءة أعلى وتحقيق نمو مستدام. نقدم حلولاً تقنية متطورة تعتمد على أحدث التقنيات في مجال تطوير البرمجيات وأنظمة ERP والتطبيقات الذكية، بهدف دعم الشركات في التحول الرقمي وتنظيم العمليات التشغيلية.',
            'mission_title' => 'Our Mission',
            'mission_title_en' => 'Our Mission',
            'mission_title_ar' => 'رسالتنا',
            'mission_content' => 'Provide integrated and reliable technology solutions that help businesses run efficiently and achieve top performance through smart, user-friendly systems.',
            'mission_content_en' => 'Provide integrated and reliable technology solutions that help businesses run efficiently and achieve top performance through smart, user-friendly systems.',
            'mission_content_ar' => 'توفير حلول تقنية متكاملة وموثوقة تساعد الشركات على إدارة أعمالها بكفاءة، وتحقيق أعلى مستويات الأداء من خلال أنظمة ذكية وسهلة الاستخدام.',
            'vision_title' => 'Our Vision',
            'vision_title_en' => 'Our Vision',
            'vision_title_ar' => 'رؤيتنا',
            'vision_content' => 'To be a leading company in developing administrative systems and digital applications in the region, delivering innovative solutions that improve business outcomes and institutional efficiency.',
            'vision_content_en' => 'To be a leading company in developing administrative systems and digital applications in the region, delivering innovative solutions that improve business outcomes and institutional efficiency.',
            'vision_content_ar' => 'أن نكون من الشركات الرائدة في مجال تطوير الأنظمة الإدارية والتطبيقات الرقمية في المنطقة، وأن نقدم حلولاً تقنية مبتكرة تساهم في تطوير الأعمال ورفع كفاءة المؤسسات.',
            'years_experience' => 10,
            'happy_clients' => 200,
            'is_active' => true,
        ]);
        $about->save();
    }

    private function seedServices(): void
    {
        $services = [
            [
                'title' => 'ERP System Development',
                'title_en' => 'ERP System Development',
                'title_ar' => 'تطوير أنظمة ERP',
                'description' => 'Enterprise resource planning solutions to manage all business operations in one integrated and easy-to-use system.',
                'description_en' => 'Enterprise resource planning solutions to manage all business operations in one integrated and easy-to-use system.',
                'description_ar' => 'أنظمة تخطيط موارد المؤسسات التي تدير جميع عملياتك في نظام واحد متكامل وسهل الاستخدام.',
                'features' => ['Accounting Management', 'Sales and Purchasing', 'Inventory and Warehouses', 'POS Integration'],
                'features_en' => ['Accounting Management', 'Sales and Purchasing', 'Inventory and Warehouses', 'POS Integration'],
                'features_ar' => ['إدارة الحسابات', 'إدارة المبيعات والمشتريات', 'إدارة المخازن والمستودعات', 'تكامل نقاط البيع POS'],
                'icon' => 'code',
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Application Development',
                'title_en' => 'Application Development',
                'title_ar' => 'تطوير التطبيقات',
                'description' => 'Mobile applications that help your company deliver better and faster services to customers.',
                'description_en' => 'Mobile applications that help your company deliver better and faster services to customers.',
                'description_ar' => 'تطبيقات هواتف ذكية تساعد شركتك على تقديم خدماتها بشكل أفضل وأسرع لعملائها.',
                'features' => ['Android and iOS Apps', 'Online and Offline Mode', 'Automatic Data Sync', 'Field Sales Enablement'],
                'features_en' => ['Android and iOS Apps', 'Online and Offline Mode', 'Automatic Data Sync', 'Field Sales Enablement'],
                'features_ar' => ['تطبيقات أندرويد و iOS', 'العمل Online و Offline', 'مزامنة البيانات تلقائياً', 'دعم أعمال المندوبين ميدانياً'],
                'icon' => 'smartphone',
                'sort_order' => 2,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'E-commerce Solutions',
                'title_en' => 'E-commerce Solutions',
                'title_ar' => 'المتاجر الإلكترونية',
                'description' => 'Integrated online stores connected to administrative systems to manage orders and sales efficiently.',
                'description_en' => 'Integrated online stores connected to administrative systems to manage orders and sales efficiently.',
                'description_ar' => 'متاجر إلكترونية متكاملة مرتبطة بالأنظمة الإدارية لإدارة الطلبات والمبيعات بسهولة.',
                'features' => ['Order Management', 'Sales Tracking', 'Inventory Sync', 'Integrated Payment Workflows'],
                'features_en' => ['Order Management', 'Sales Tracking', 'Inventory Sync', 'Integrated Payment Workflows'],
                'features_ar' => ['إدارة الطلبات', 'متابعة المبيعات', 'مزامنة المخزون', 'تكامل عمليات الدفع'],
                'icon' => 'palette',
                'sort_order' => 3,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'POS Systems',
                'title_en' => 'POS Systems',
                'title_ar' => 'أنظمة نقاط البيع POS',
                'description' => 'Advanced POS solutions for stores, restaurants, and commercial businesses with user-friendly interfaces.',
                'description_en' => 'Advanced POS solutions for stores, restaurants, and commercial businesses with user-friendly interfaces.',
                'description_ar' => 'حلول نقاط بيع متطورة للمتاجر والمطاعم والشركات التجارية بواجهة سهلة الاستخدام.',
                'features' => ['Retail and Restaurant Support', 'Barcode and Receipt Workflows', 'Branch-level Operations', 'Real-time Sales Reports'],
                'features_en' => ['Retail and Restaurant Support', 'Barcode and Receipt Workflows', 'Branch-level Operations', 'Real-time Sales Reports'],
                'features_ar' => ['دعم المتاجر والمطاعم', 'عمليات الباركود والفواتير', 'إدارة العمليات على مستوى الفروع', 'تقارير مبيعات فورية'],
                'icon' => 'trending-up',
                'sort_order' => 4,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Business Management Solutions',
                'title_en' => 'Business Management Solutions',
                'title_ar' => 'إدارة الأعمال',
                'description' => 'Integrated solutions for accounting, inventory, sales, HR, and customer relationship management.',
                'description_en' => 'Integrated solutions for accounting, inventory, sales, HR, and customer relationship management.',
                'description_ar' => 'حلول متكاملة لإدارة الحسابات، المخازن، المبيعات، الموارد البشرية، وعلاقات العملاء.',
                'features' => ['Accounting and Finance', 'Inventory and Warehouses', 'HR and Payroll', 'CRM'],
                'features_en' => ['Accounting and Finance', 'Inventory and Warehouses', 'HR and Payroll', 'CRM'],
                'features_ar' => ['إدارة الحسابات والمالية', 'إدارة المخازن والمستودعات', 'الموارد البشرية والرواتب', 'إدارة علاقات العملاء CRM'],
                'icon' => 'cloud',
                'sort_order' => 5,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Cybersecurity Services',
                'title_en' => 'Cybersecurity Services',
                'title_ar' => 'الأمن السيبراني',
                'description' => 'Comprehensive protection for your data and systems against cyber threats with high security standards.',
                'description_en' => 'Comprehensive protection for your data and systems against cyber threats with high security standards.',
                'description_ar' => 'حماية شاملة لبياناتك وأنظمتك من التهديدات الإلكترونية مع الامتثال لأعلى معايير الأمن.',
                'features' => ['Threat Protection', 'Data Security Hardening', 'Access Control Policies', 'Security Monitoring'],
                'features_en' => ['Threat Protection', 'Data Security Hardening', 'Access Control Policies', 'Security Monitoring'],
                'features_ar' => ['حماية من التهديدات', 'تعزيز أمن البيانات', 'سياسات التحكم في الوصول', 'مراقبة أمنية مستمرة'],
                'icon' => 'shield',
                'sort_order' => 6,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::query()->updateOrCreate(
                ['title_ar' => $service['title_ar']],
                $service
            );
        }
    }
}
