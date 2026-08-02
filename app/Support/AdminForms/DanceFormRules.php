<?php

namespace App\Support\AdminForms;

use App\Enums\Municipality;

final class DanceFormRules
{
    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255',
            'category'              => 'required|in:pagaddut,hinggatut,dinuy-a',
            'description'           => 'required|string|max:1000',
            'municipality'          => 'nullable|in:'.Municipality::validationList(),
            'region'                => 'nullable|string|max:255',
            'origin'                => 'nullable|string|max:255',
            'cultural_meaning'      => 'nullable|string|max:2000',
            'historical_background' => 'nullable|string|max:2000',
            'video_url'             => ['nullable', 'url', 'regex:'.FormRules::YOUTUBE_URL_REGEX],
            'image'                 => FormRules::image(),
            'video'                 => 'nullable|mimes:mp4,mov,webm|max:51200',
        ];
    }

    /** @return array<string, string> */
    public static function messages(): array
    {
        return [
            'name.required'        => 'Enter the dance name.',
            'category.required'    => 'Select a category.',
            'category.in'          => 'Choose Pagaddut, Hinggatut, or Dinuy-a.',
            'description.required' => 'Add a description.',
            'video_url.url'        => 'Enter a valid YouTube link.',
            'video_url.regex'      => 'YouTube links must be watch, youtu.be, shorts, or embed URLs.',
            'video.mimes'          => 'Video must be MP4, MOV, or WebM.',
            'video.max'            => 'Video must be 50 MB or smaller.',
            'image.image'          => 'Upload a JPG or PNG image.',
            'image.max'            => 'Image must be 10 MB or smaller.',
        ];
    }

    /** @param  array<string, mixed>  $validated */
    public static function normalize(array $validated): array
    {
        return FormRules::nullIfBlank($validated, [
            'municipality', 'region', 'origin', 'cultural_meaning',
            'historical_background', 'video_url',
        ]);
    }
}
