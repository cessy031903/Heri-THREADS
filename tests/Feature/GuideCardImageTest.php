<?php

namespace Tests\Feature;

use App\Livewire\Admin\Guides\ManageGuides;
use App\Livewire\ExploreAttires;
use App\Models\InteractiveGuide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class GuideCardImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
    }

    public function test_admin_can_upload_and_replace_card_image(): void
    {
        $img1 = UploadedFile::fake()->create('card.jpg', 100, 'image/jpeg');

        Livewire::test(ManageGuides::class)
            ->set('municipality', 'Asipulo')
            ->set('title', 'Asipulo Guide')
            ->set('cardImage', $img1)
            ->call('save');

        $guide = InteractiveGuide::where('municipality', 'Asipulo')->firstOrFail();
        Storage::disk('public')->assertExists($guide->card_image_path);
        $oldPath = $guide->card_image_path;

        $img2 = UploadedFile::fake()->create('card2.jpg', 100, 'image/jpeg');
        Livewire::test(ManageGuides::class)
            ->call('openEdit', $guide->id)
            ->set('cardImage', $img2)
            ->call('save');

        $guide->refresh();
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($guide->card_image_path);
    }

    public function test_deleting_guide_removes_card_image_file(): void
    {
        $img = UploadedFile::fake()->create('card.jpg', 100, 'image/jpeg');

        Livewire::test(ManageGuides::class)
            ->set('municipality', 'Kiangan')
            ->set('title', 'Kiangan Guide')
            ->set('cardImage', $img)
            ->call('save');

        $guide = InteractiveGuide::where('municipality', 'Kiangan')->firstOrFail();
        $path = $guide->card_image_path;

        Livewire::test(ManageGuides::class)->call('delete', $guide->id);

        Storage::disk('public')->assertMissing($path);
    }

    public function test_explore_attires_exposes_card_image_url_for_municipality(): void
    {
        InteractiveGuide::create([
            'municipality'    => 'Banaue',
            'title'           => 'Banaue Guide',
            'card_image_path' => 'guides/banaue-card.jpg',
        ]);

        $urls = Livewire::test(ExploreAttires::class)
            ->instance()
            ->municipalityCardImages;

        $this->assertArrayHasKey('Banaue', $urls);
        $this->assertStringContainsString('banaue-card.jpg', $urls['Banaue']);
        $this->assertArrayNotHasKey('Asipulo', $urls);
    }
}
