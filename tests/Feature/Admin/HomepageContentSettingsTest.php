<?php

namespace Tests\Feature\Admin;

use App\Models\SiteSetting;
use App\Models\User;
use App\Support\HomepageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomepageContentSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_homepage_content_settings_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.settings.homepage.edit'));

        $response->assertOk();
        $response->assertSee('Homepage Content');
    }

    public function test_non_admin_cannot_access_homepage_content_settings_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.settings.homepage.edit'));

        $response->assertForbidden();
    }

    public function test_admin_can_update_homepage_content_and_changes_are_visible_on_homepage(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $payload = HomepageContent::defaults();
        unset($payload['hero_image_path']);
        $payload['hero_title'] = 'Sell smarter, declutter faster.';
        $payload['hero_description'] = 'Custom homepage copy controlled from the admin settings page.';
        $payload['search_button_label'] = 'Find Deals';

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.settings.homepage.update'), $payload);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.settings.homepage.edit'));

        $this->assertDatabaseHas('site_settings', [
            'key' => 'homepage.hero_title',
            'value' => 'Sell smarter, declutter faster.',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Sell smarter, declutter faster.')
            ->assertSee('Custom homepage copy controlled from the admin settings page.')
            ->assertSee('Find Deals');
    }

    public function test_admin_can_upload_hero_image_for_homepage_content(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $payload = HomepageContent::defaults();
        unset($payload['hero_image_path']);
        $payload['hero_image'] = UploadedFile::fake()->create('hero.jpg', 250, 'image/jpeg');

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.settings.homepage.update'), $payload);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.settings.homepage.edit'));

        $heroImagePath = SiteSetting::query()
            ->where('key', 'homepage.hero_image_path')
            ->value('value');

        $this->assertNotNull($heroImagePath);
        Storage::disk('public')->assertExists($heroImagePath);
    }
}
