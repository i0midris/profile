<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->boolean('show_about_page')->default(true)->after('whatsapp');
            $table->boolean('show_services_page')->default(true)->after('show_about_page');
            $table->boolean('show_team_page')->default(true)->after('show_services_page');
            $table->boolean('show_portfolio_page')->default(true)->after('show_team_page');
            $table->boolean('show_testimonials_page')->default(true)->after('show_portfolio_page');
            $table->boolean('show_contact_page')->default(true)->after('show_testimonials_page');
        });
    }

    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn([
                'show_about_page',
                'show_services_page',
                'show_team_page',
                'show_portfolio_page',
                'show_testimonials_page',
                'show_contact_page',
            ]);
        });
    }
};
