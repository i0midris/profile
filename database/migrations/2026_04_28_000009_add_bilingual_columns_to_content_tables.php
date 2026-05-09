<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('company_name_en')->nullable();
            $table->string('company_name_ar')->nullable();
            $table->string('tagline_en')->nullable();
            $table->string('tagline_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('address_en')->nullable();
            $table->text('address_ar')->nullable();
        });

        DB::table('company_settings')->update([
            'company_name_en' => DB::raw('company_name'),
            'company_name_ar' => DB::raw('company_name'),
            'tagline_en' => DB::raw('tagline'),
            'tagline_ar' => DB::raw('tagline'),
            'description_en' => DB::raw('description'),
            'description_ar' => DB::raw('description'),
            'address_en' => DB::raw('address'),
            'address_ar' => DB::raw('address'),
        ]);

        Schema::table('hero_sections', function (Blueprint $table) {
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->string('subtitle_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('button_text_en')->nullable();
            $table->string('button_text_ar')->nullable();
            $table->string('button_text_secondary_en')->nullable();
            $table->string('button_text_secondary_ar')->nullable();
        });

        DB::table('hero_sections')->update([
            'title_en' => DB::raw('title'),
            'title_ar' => DB::raw('title'),
            'subtitle_en' => DB::raw('subtitle'),
            'subtitle_ar' => DB::raw('subtitle'),
            'description_en' => DB::raw('description'),
            'description_ar' => DB::raw('description'),
            'button_text_en' => DB::raw('button_text'),
            'button_text_ar' => DB::raw('button_text'),
            'button_text_secondary_en' => DB::raw('button_text_secondary'),
            'button_text_secondary_ar' => DB::raw('button_text_secondary'),
        ]);

        Schema::table('about_sections', function (Blueprint $table) {
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->text('content_en')->nullable();
            $table->text('content_ar')->nullable();
            $table->string('mission_title_en')->nullable();
            $table->string('mission_title_ar')->nullable();
            $table->text('mission_content_en')->nullable();
            $table->text('mission_content_ar')->nullable();
            $table->string('vision_title_en')->nullable();
            $table->string('vision_title_ar')->nullable();
            $table->text('vision_content_en')->nullable();
            $table->text('vision_content_ar')->nullable();
        });

        DB::table('about_sections')->update([
            'title_en' => DB::raw('title'),
            'title_ar' => DB::raw('title'),
            'content_en' => DB::raw('content'),
            'content_ar' => DB::raw('content'),
            'mission_title_en' => DB::raw('mission_title'),
            'mission_title_ar' => DB::raw('mission_title'),
            'mission_content_en' => DB::raw('mission_content'),
            'mission_content_ar' => DB::raw('mission_content'),
            'vision_title_en' => DB::raw('vision_title'),
            'vision_title_ar' => DB::raw('vision_title'),
            'vision_content_en' => DB::raw('vision_content'),
            'vision_content_ar' => DB::raw('vision_content'),
        ]);

        Schema::table('services', function (Blueprint $table) {
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('features_en')->nullable();
            $table->text('features_ar')->nullable();
        });

        DB::table('services')->update([
            'title_en' => DB::raw('title'),
            'title_ar' => DB::raw('title'),
            'description_en' => DB::raw('description'),
            'description_ar' => DB::raw('description'),
            'features_en' => DB::raw('features'),
            'features_ar' => DB::raw('features'),
        ]);

        Schema::table('team_members', function (Blueprint $table) {
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('position_en')->nullable();
            $table->string('position_ar')->nullable();
            $table->text('bio_en')->nullable();
            $table->text('bio_ar')->nullable();
        });

        DB::table('team_members')->update([
            'name_en' => DB::raw('name'),
            'name_ar' => DB::raw('name'),
            'position_en' => DB::raw('position'),
            'position_ar' => DB::raw('position'),
            'bio_en' => DB::raw('bio'),
            'bio_ar' => DB::raw('bio'),
        ]);

        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('client_name_en')->nullable();
            $table->string('client_name_ar')->nullable();
            $table->string('category_en')->nullable();
            $table->string('category_ar')->nullable();
        });

        DB::table('portfolios')->update([
            'title_en' => DB::raw('title'),
            'title_ar' => DB::raw('title'),
            'description_en' => DB::raw('description'),
            'description_ar' => DB::raw('description'),
            'client_name_en' => DB::raw('client_name'),
            'client_name_ar' => DB::raw('client_name'),
            'category_en' => DB::raw('category'),
            'category_ar' => DB::raw('category'),
        ]);

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('client_name_en')->nullable();
            $table->string('client_name_ar')->nullable();
            $table->string('client_position_en')->nullable();
            $table->string('client_position_ar')->nullable();
            $table->string('client_company_en')->nullable();
            $table->string('client_company_ar')->nullable();
            $table->text('content_en')->nullable();
            $table->text('content_ar')->nullable();
        });

        DB::table('testimonials')->update([
            'client_name_en' => DB::raw('client_name'),
            'client_name_ar' => DB::raw('client_name'),
            'client_position_en' => DB::raw('client_position'),
            'client_position_ar' => DB::raw('client_position'),
            'client_company_en' => DB::raw('client_company'),
            'client_company_ar' => DB::raw('client_company'),
            'content_en' => DB::raw('content'),
            'content_ar' => DB::raw('content'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn([
                'client_name_en',
                'client_name_ar',
                'client_position_en',
                'client_position_ar',
                'client_company_en',
                'client_company_ar',
                'content_en',
                'content_ar',
            ]);
        });

        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn([
                'title_en',
                'title_ar',
                'description_en',
                'description_ar',
                'client_name_en',
                'client_name_ar',
                'category_en',
                'category_ar',
            ]);
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn([
                'name_en',
                'name_ar',
                'position_en',
                'position_ar',
                'bio_en',
                'bio_ar',
            ]);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'title_en',
                'title_ar',
                'description_en',
                'description_ar',
                'features_en',
                'features_ar',
            ]);
        });

        Schema::table('about_sections', function (Blueprint $table) {
            $table->dropColumn([
                'title_en',
                'title_ar',
                'content_en',
                'content_ar',
                'mission_title_en',
                'mission_title_ar',
                'mission_content_en',
                'mission_content_ar',
                'vision_title_en',
                'vision_title_ar',
                'vision_content_en',
                'vision_content_ar',
            ]);
        });

        Schema::table('hero_sections', function (Blueprint $table) {
            $table->dropColumn([
                'title_en',
                'title_ar',
                'subtitle_en',
                'subtitle_ar',
                'description_en',
                'description_ar',
                'button_text_en',
                'button_text_ar',
                'button_text_secondary_en',
                'button_text_secondary_ar',
            ]);
        });

        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn([
                'company_name_en',
                'company_name_ar',
                'tagline_en',
                'tagline_ar',
                'description_en',
                'description_ar',
                'address_en',
                'address_ar',
            ]);
        });
    }
};
