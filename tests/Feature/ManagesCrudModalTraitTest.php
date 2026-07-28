<?php

namespace Tests\Feature;

use App\Livewire\Admin\Attires\ManageAttires;
use App\Livewire\Admin\Dances\ManageDances;
use App\Models\Attire;
use App\Models\Dance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Verifies the ManagesCrudModal trait (openCreate, replaceStoredFile,
 * logAndNotify) preserves exact prior behavior for both consumers.
 */
class ManagesCrudModalTraitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
    }

    public function test_attire_create_edit_delete_replaces_and_cleans_up_files(): void
    {
        $img1 = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');

        Livewire::test(ManageAttires::class)
            ->set('name_general', 'Test Attire')
            ->set('name_dialect', 'Test Dialect')
            ->set('municipality', 'Asipulo')
            ->set('gender', 'women')
            ->set('description', 'A test description')
            ->set('source_info', 'test source')
            ->set('image', $img1)
            ->call('save');

        $attire = Attire::where('name_general', 'Test Attire')->firstOrFail();
        $oldPath = $attire->image_path;
        Storage::disk('public')->assertExists($oldPath);

        $img2 = UploadedFile::fake()->create('test2.jpg', 100, 'image/jpeg');
        Livewire::test(ManageAttires::class)
            ->call('openEdit', $attire->id)
            ->set('name_general', 'Test Attire Updated')
            ->set('image', $img2)
            ->call('save');

        $attire->refresh();
        $this->assertSame('Test Attire Updated', $attire->name_general);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($attire->image_path);

        $newPath = $attire->image_path;
        Livewire::test(ManageAttires::class)->call('delete', $attire->id);

        $this->assertNull(Attire::find($attire->id));
        Storage::disk('public')->assertMissing($newPath);
    }

    public function test_dance_create_edit_delete_replaces_and_cleans_up_files(): void
    {
        $img1 = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');

        Livewire::test(ManageDances::class)
            ->set('name', 'Test Dance')
            ->set('category', 'pagaddut')
            ->set('description', 'A test description')
            ->set('image', $img1)
            ->call('save');

        $dance = Dance::where('name', 'Test Dance')->firstOrFail();
        $oldPath = $dance->image_path;
        Storage::disk('public')->assertExists($oldPath);

        $img2 = UploadedFile::fake()->create('test2.jpg', 100, 'image/jpeg');
        Livewire::test(ManageDances::class)
            ->call('openEdit', $dance->id)
            ->set('name', 'Test Dance Updated')
            ->set('image', $img2)
            ->call('save');

        $dance->refresh();
        $this->assertSame('Test Dance Updated', $dance->name);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($dance->image_path);

        $newPath = $dance->image_path;
        Livewire::test(ManageDances::class)->call('delete', $dance->id);

        $this->assertNull(Dance::find($dance->id));
        Storage::disk('public')->assertMissing($newPath);
    }
}
