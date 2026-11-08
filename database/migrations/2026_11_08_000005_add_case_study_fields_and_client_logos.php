<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Plan step 3.2: portfolio items can be written up as case studies,
     * and client logos get their own list.
     */
    public function up(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            foreach (['challenge', 'solution', 'results'] as $field) {
                $table->text("{$field}_ar")->nullable();
                $table->text("{$field}_en")->nullable();
            }
        });

        Schema::create('client_logos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo');
            $table->string('website_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_logos');

        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['challenge_ar', 'challenge_en', 'solution_ar', 'solution_en', 'results_ar', 'results_en']);
        });
    }
};
