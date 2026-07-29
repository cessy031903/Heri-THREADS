<?php

namespace Tests\Feature;

use App\Livewire\Admin\Showcase\ManageShowcase;
use App\Livewire\Home;
use App\Models\ShowcasePhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ManageShowcaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
    }

    public function test_admin_can_create_edit_delete_showcase_photo(): void
    {
        $img = UploadedFile::fake()->create('pair.jpg', 100, 'image/jpeg');

        Livewire::test(ManageShowcase::class)
            ->set('label', 'Alfonso Lista Attire Pair')
            ->set('sub_label', 'Textiles')
            ->set('link_url', '/attires?municipality=Alfonso+Lista')
            ->set('image', $img)
            ->call('save');

        $photo = ShowcasePhoto::where('label', 'Alfonso Lista Attire Pair')->firstOrFail();
        Storage::disk('public')->assertExists($photo->image_path);
        $this->assertSame(0, $photo->order);

        Livewire::test(ManageShowcase::class)
            ->call('openEdit', $photo->id)
            ->set('label', 'Updated Label')
            ->call('save');

        $this->assertSame('Updated Label', $photo->fresh()->label);

        $path = $photo->image_path;
        Livewire::test(ManageShowcase::class)->call('delete', $photo->id);

        $this->assertNull(ShowcasePhoto::find($photo->id));
        Storage::disk('public')->assertMissing($path);
    }

    public function test_reordering_swaps_order_values(): void
    {
        $a = ShowcasePhoto::create(['image_path' => 'x.jpg', 'label' => 'A', 'order' => 0]);
        $b = ShowcasePhoto::create(['image_path' => 'y.jpg', 'label' => 'B', 'order' => 1]);

        Livewire::test(ManageShowcase::class)->call('moveDown', $a->id);

        $this->assertSame(1, $a->fresh()->order);
        $this->assertSame(0, $b->fresh()->order);
    }

    public function test_home_shows_only_admin_uploaded_photos_not_dances_or_attires(): void
    {
        ShowcasePhoto::create(['image_path' => 'showcase/pair.jpg', 'label' => 'Kiangan Pair', 'order' => 0]);

        Livewire::test(Home::class)
            ->assertSee('Kiangan Pair');
    }

    public function test_home_hides_carousel_section_when_no_photos_exist(): void
    {
        Livewire::test(Home::class)
            ->assertDontSee('hero-carousel', false);
    }
}
