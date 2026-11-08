<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamSectionTest extends TestCase
{
    use RefreshDatabase;

    private function member(): void
    {
        TeamMember::create(['name' => 'Ahmed', 'name_ar' => 'أحمد علي', 'position' => 'Dev', 'position_ar' => 'مطور',
            'linkedin' => 'https://linkedin.com/in/x', 'is_active' => true]);
    }

    public function test_about_page_does_not_link_to_hidden_team_page(): void
    {
        CompanySetting::create(['company_name' => 'Test', 'show_team_page' => false]);
        $this->member();

        $this->get('/ar/about')->assertOk()
            ->assertSee('أحمد علي')
            ->assertDontSee('/ar/team"', false)
            ->assertSee('>أ</span>', false)                 // whole Arabic letter, not a broken byte
            ->assertSee('aria-label="LinkedIn — أحمد علي"', false);
    }

    public function test_about_page_links_to_visible_team_page(): void
    {
        CompanySetting::create(['company_name' => 'Test', 'show_team_page' => true]);
        $this->member();

        $this->get('/ar/about')->assertSee('/ar/team"', false);
        $this->get('/ar/team')->assertOk()->assertSee('أحمد علي');
    }
}
