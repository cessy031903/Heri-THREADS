<?php

namespace Tests\Feature;

use App\Livewire\Admin\Dances\ManageDances;
use App\Livewire\ExploreDances;
use App\Models\Dance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class DanceVideoUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_attach_uploaded_video_and_it_is_stored(): void
    {
        Storage::fake('public');

        $dance = Dance::factory()->create(['video_path' => null]);
        $video = UploadedFile::fake()->create('clip.mp4', 5000, 'video/mp4');

        Livewire::test(ManageDances::class)
            ->call('openEdit', $dance->id)
            ->set('video', $video)
            ->call('save')
            ->assertHasNoErrors();

        $fresh = $dance->fresh();
        $this->assertNotNull($fresh->video_path);
        Storage::disk('public')->assertExists($fresh->video_path);
    }

    public function test_video_field_rejects_non_video_file_types(): void
    {
        Storage::fake('public');

        $dance = Dance::factory()->create();
        $badFile = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        Livewire::test(ManageDances::class)
            ->call('openEdit', $dance->id)
            ->set('video', $badFile)
            ->call('save')
            ->assertHasErrors(['video']);
    }

    public function test_uploading_new_video_deletes_old_one_from_disk(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('dances-videos/old.mp4', 'old-bytes');
        $dance = Dance::factory()->create(['video_path' => 'dances-videos/old.mp4']);
        $newVideo = UploadedFile::fake()->create('new.mp4', 5000, 'video/mp4');

        Livewire::test(ManageDances::class)
            ->call('openEdit', $dance->id)
            ->set('video', $newVideo)
            ->call('save')
            ->assertHasNoErrors();

        Storage::disk('public')->assertMissing('dances-videos/old.mp4');
        Storage::disk('public')->assertExists($dance->fresh()->video_path);
    }

    public function test_public_page_shows_uploaded_video_over_youtube_embed_when_both_present(): void
    {
        $dance = Dance::factory()->create([
            'video_url'  => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'video_path' => 'dances-videos/clip.mp4',
        ]);

        Livewire::test(ExploreDances::class)
            ->call('selectDance', $dance->id)
            ->assertSee('<video', false)
            ->assertDontSee('<iframe', false);
    }

    public function test_public_page_falls_back_to_youtube_embed_when_no_uploaded_video(): void
    {
        $dance = Dance::factory()->create([
            'video_url'  => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'video_path' => null,
        ]);

        Livewire::test(ExploreDances::class)
            ->call('selectDance', $dance->id)
            ->assertDontSee('<video', false)
            ->assertSee('<iframe', false);
    }
}
