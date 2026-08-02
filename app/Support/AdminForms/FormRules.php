<?php

namespace App\Support\AdminForms;

/**
 * Shared helpers for admin form validation — Laravel-side schema utilities
 * (this stack is Livewire/PHP, not a JS client, so rules live here rather
 * than in Zod).
 */
final class FormRules
{
    /** Blank strings become null so optional DB columns stay clean. */
    public static function nullIfBlank(array $data, array $keys): array
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $data)) {
                $data[$key] = filled($data[$key]) ? $data[$key] : null;
            }
        }

        return $data;
    }

    public static function image(bool $required = false): string
    {
        $mime = 'image|mimes:jpeg,png,jpg|max:10240';

        return $required ? "required|{$mime}" : "nullable|{$mime}";
    }

    public const YOUTUBE_URL_REGEX = '/^https?:\/\/(www\.|m\.)?(youtube\.com\/(watch\?v=|shorts\/|embed\/)|youtu\.be\/).+/';
}
