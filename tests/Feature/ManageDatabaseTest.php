<?php

namespace Tests\Feature;

use App\Livewire\Admin\Settings\ManageDatabase;
use App\Models\Attire;
use App\Models\Dance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ManageDatabaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->actingAs(User::factory()->create());
    }

    public function test_backup_streams_a_json_file_containing_real_data(): void
    {
        Dance::create(['name' => 'Test Dance', 'category' => 'pagaddut', 'description' => 'x']);

        $component = new ManageDatabase();
        $streamedResponse = $component->backup();

        ob_start();
        $streamedResponse->sendContent();
        $output = ob_get_clean();

        $decoded = json_decode($output, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('tables', $decoded);
        $this->assertArrayHasKey('dances', $decoded['tables']);
        $this->assertSame('Test Dance', $decoded['tables']['dances'][0]['name']);
    }

    public function test_restore_replaces_data_and_saves_a_safety_snapshot_first(): void
    {
        // Seed some "old" data that should be gone after restore.
        Dance::create(['name' => 'Old Dance', 'category' => 'pagaddut', 'description' => 'old']);

        $backupPayload = [
            'exported_at' => now()->toIso8601String(),
            'app'         => 'Heri-THREADS',
            'tables'      => [
                'dances'             => [
                    ['id' => 1, 'name' => 'Restored Dance', 'category' => 'dinuy-a', 'municipality' => null,
                     'description' => 'restored', 'region' => null, 'origin' => null, 'cultural_meaning' => null,
                     'historical_background' => null, 'video_url' => null, 'video_path' => null, 'image_path' => null,
                     'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString(), 'deleted_at' => null],
                ],
                'attires'            => [],
                'interactive_guides' => [],
                'guide_hotspots'     => [],
                'showcase_photos'    => [],
                'users'              => [],
                'audit_logs'         => [],
            ],
        ];

        $file = UploadedFile::fake()->createWithContent('backup.json', json_encode($backupPayload));

        Livewire::test(ManageDatabase::class)
            ->set('restoreFile', $file)
            ->call('stageRestore')
            ->assertSet('confirmingRestore', true)
            ->call('confirmRestore');

        $this->assertDatabaseMissing('dances', ['name' => 'Old Dance']);
        $this->assertDatabaseHas('dances', ['name' => 'Restored Dance']);

        // Safety snapshot of the PRE-restore state should exist.
        $files = Storage::disk('local')->files('backups');
        $this->assertNotEmpty($files, 'Expected a pre-restore safety snapshot to be saved.');

        $snapshot = json_decode(Storage::disk('local')->get($files[0]), true);
        $this->assertSame('Old Dance', $snapshot['tables']['dances'][0]['name']);
    }

    public function test_restore_never_deletes_the_currently_signed_in_admin(): void
    {
        $me = auth()->user();

        // Backup payload with an EMPTY users table — simulates a backup
        // taken before this admin's account existed, or one that simply
        // doesn't include them.
        $backupPayload = [
            'exported_at' => now()->toIso8601String(),
            'app'         => 'Heri-THREADS',
            'tables'      => [
                'dances' => [], 'attires' => [], 'interactive_guides' => [],
                'guide_hotspots' => [], 'showcase_photos' => [], 'users' => [], 'audit_logs' => [],
            ],
        ];

        $file = UploadedFile::fake()->createWithContent('backup.json', json_encode($backupPayload));

        Livewire::test(ManageDatabase::class)
            ->set('restoreFile', $file)
            ->call('stageRestore')
            ->call('confirmRestore')
            ->assertHasNoErrors();

        // The signed-in admin must still exist, and the restore action
        // itself must have been logged (proving the FK to users held up).
        $this->assertNotNull(User::find($me->id));
        $this->assertDatabaseHas('audit_logs', ['action' => 'restore', 'resource_type' => 'database']);
    }

    public function test_restore_still_brings_back_other_users_from_the_backup(): void
    {
        $backupPayload = [
            'exported_at' => now()->toIso8601String(),
            'app'         => 'Heri-THREADS',
            'tables'      => [
                'dances' => [], 'attires' => [], 'interactive_guides' => [],
                'guide_hotspots' => [], 'showcase_photos' => [],
                'users' => [
                    ['id' => 999, 'name' => 'Restored Colleague', 'email' => 'colleague@test.com',
                     'password' => bcrypt('whatever'), 'role' => 'admin', 'remember_token' => null,
                     'email_verified_at' => null, 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ],
                'audit_logs' => [],
            ],
        ];

        $file = UploadedFile::fake()->createWithContent('backup.json', json_encode($backupPayload));

        Livewire::test(ManageDatabase::class)
            ->set('restoreFile', $file)
            ->call('stageRestore')
            ->call('confirmRestore');

        $this->assertDatabaseHas('users', ['email' => 'colleague@test.com']);
        $this->assertNotNull(User::find(auth()->id())); // still signed in, both exist
    }

    public function test_restore_rejects_a_file_that_is_not_a_valid_backup(): void
    {
        $file = UploadedFile::fake()->createWithContent('not-a-backup.json', json_encode(['random' => 'data']));

        Livewire::test(ManageDatabase::class)
            ->set('restoreFile', $file)
            ->call('stageRestore')
            ->call('confirmRestore')
            ->assertHasErrors('restoreFile');
    }

    public function test_stage_restore_rejects_non_json_files(): void
    {
        $file = UploadedFile::fake()->create('backup.zip', 10);

        Livewire::test(ManageDatabase::class)
            ->set('restoreFile', $file)
            ->call('stageRestore')
            ->assertHasErrors('restoreFile');
    }
}
