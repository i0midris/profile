<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Plan step 3.4: "Request a demo" form, office location (map) and working hours.
     */
    public function up(): void
    {
        Schema::create('demo_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('phone', 50);
            $table->string('email')->nullable();
            $table->string('product')->nullable();
            $table->string('preferred_contact', 20)->default('whatsapp');
            $table->text('message')->nullable();
            $table->boolean('is_handled')->default(false);
            $table->string('locale', 5)->nullable();
            $table->timestamps();
        });

        Schema::table('company_settings', function (Blueprint $table) {
            $table->text('working_hours_ar')->nullable();
            $table->text('working_hours_en')->nullable();
            $table->string('map_embed_url', 1000)->nullable();
        });

        // The hero button now reads "Request a demo" (step 1.6): send it to the new demo form,
        // but only if it still points at the original default link.
        DB::table('hero_sections')
            ->where('button_link', '/contact')
            ->where(fn ($q) => $q->where('button_text_ar', 'اطلب عرضاً تجريبياً')->orWhere('button_text_en', 'Request a Demo'))
            ->update(['button_link' => '/demo']);
    }

    public function down(): void
    {
        DB::table('hero_sections')->where('button_link', '/demo')->update(['button_link' => '/contact']);

        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['working_hours_ar', 'working_hours_en', 'map_embed_url']);
        });

        Schema::dropIfExists('demo_requests');
    }
};
