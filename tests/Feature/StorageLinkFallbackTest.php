<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The "public" disk's serve=true fallback (config/filesystems.php,
 * STORAGE_LINK_FALLBACK env var) lets Laravel serve uploaded files
 * directly when `php artisan storage:link`'s symlink doesn't work — some
 * shared hosts (Hostinger included, on some plans) restrict symlinks.
 * This uses Laravel's own built-in FilesystemServiceProvider route
 * (registered as "storage.public"), not custom code.
 */
class StorageLinkFallbackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        putenv('STORAGE_LINK_FALLBACK=true');
        $_ENV['STORAGE_LINK_FALLBACK'] = 'true';
        $_SERVER['STORAGE_LINK_FALLBACK'] = 'true';

        parent::setUp();

        Storage::fake('public');
    }

    protected function tearDown(): void
    {
        putenv('STORAGE_LINK_FALLBACK');
        unset($_ENV['STORAGE_LINK_FALLBACK'], $_SERVER['STORAGE_LINK_FALLBACK']);

        parent::tearDown();
    }

    public function test_fallback_serves_an_existing_uploaded_file(): void
    {
        Storage::disk('public')->put('dances/test-image.jpg', 'fake-image-bytes');

        $response = $this->get('/storage/dances/test-image.jpg');

        $response->assertOk();
        $this->assertSame('fake-image-bytes', $response->streamedContent());
    }

    public function test_fallback_404s_for_a_missing_file(): void
    {
        $this->get('/storage/dances/does-not-exist.jpg')->assertNotFound();
    }
}
