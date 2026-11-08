<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Plan step 3.7: Google Search Console verification and Google Analytics (GA4).
     */
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('google_site_verification', 200)->nullable();
            $table->string('ga_measurement_id', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['google_site_verification', 'ga_measurement_id']);
        });
    }
};
