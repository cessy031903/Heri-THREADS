<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAboutPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_about_page_loads(): void
    {
        $this->get(route('admin.about'))
            ->assertOk()
            ->assertSee('Hardware Requirements');
    }

    public function test_help_page_loads(): void
    {
        $this->get(route('admin.help'))
            ->assertOk()
            ->assertSee('User Manual');
    }

    public function test_developer_page_loads(): void
    {
        $this->get(route('admin.developer'))
            ->assertOk()
            ->assertSee('Developer');
    }

    public function test_settings_pages_require_authentication(): void
    {
        auth()->logout();

        $this->get(route('admin.about'))->assertRedirect(route('login'));
        $this->get(route('admin.settings.accounts'))->assertRedirect(route('login'));
        $this->get(route('admin.settings.database'))->assertRedirect(route('login'));
    }
}
