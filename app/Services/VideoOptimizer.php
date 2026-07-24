<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

/**
 * Many recording tools (OBS and others) write the MP4 "moov" atom — the
 * index browsers need before they can play or seek a video — at the end
 * of the file instead of the start. Browsers then show a player stuck at
 * 0:00 that never becomes playable, even though the file itself is valid.
 *
 * This remuxes the file in place (stream copy, no re-encoding — fast and
 * lossless) so the moov atom moves to the front ("faststart"). If ffmpeg
 * isn't installed, this silently no-ops and the original upload is kept
 * as-is rather than failing the whole upload.
 */
class VideoOptimizer
{
    public static function faststart(string $disk, string $path): void
    {
        if (! self::ffmpegAvailable()) {
            return;
        }

        $storage = Storage::disk($disk);
        $absolutePath = $storage->path($path);
        $tempPath = $absolutePath . '.faststart.tmp.mp4';

        $result = Process::timeout(120)->run([
            'ffmpeg', '-y',
            '-i', $absolutePath,
            '-c', 'copy',
            '-movflags', '+faststart',
            $tempPath,
        ]);

        if (! $result->successful() || ! file_exists($tempPath) || filesize($tempPath) === 0) {
            Log::warning('VideoOptimizer: faststart remux failed, keeping original upload.', [
                'path' => $path,
                'error' => $result->errorOutput(),
            ]);
            @unlink($tempPath);
            return;
        }

        rename($tempPath, $absolutePath);
    }

    private static function ffmpegAvailable(): bool
    {
        static $available = null;

        if ($available === null) {
            try {
                $available = Process::timeout(3)->run(['ffmpeg', '-version'])->successful();
            } catch (\Throwable $e) {
                $available = false;
            }
        }

        return $available;
    }
}
