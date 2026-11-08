<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * "Start Free Now" promised a free trial that doesn't exist; the button opens
     * the contact form. Replace only the original default text, so any wording the
     * admin already changed from the dashboard is left untouched.
     */
    private const CHANGES = [
        'button_text' => ['Start Free Now', 'Request a Demo'],
        'button_text_en' => ['Start Free Now', 'Request a Demo'],
        'button_text_ar' => ['ابدأ الآن مجاناً', 'اطلب عرضاً تجريبياً'],
    ];

    public function up(): void
    {
        foreach (self::CHANGES as $column => [$old, $new]) {
            DB::table('hero_sections')->where($column, $old)->update([$column => $new]);
        }

        // The base column may hold the Arabic text when Arabic was the editing locale.
        DB::table('hero_sections')->where('button_text', 'ابدأ الآن مجاناً')->update(['button_text' => 'اطلب عرضاً تجريبياً']);
    }

    public function down(): void
    {
        foreach (self::CHANGES as $column => [$old, $new]) {
            DB::table('hero_sections')->where($column, $new)->update([$column => $old]);
        }
    }
};
