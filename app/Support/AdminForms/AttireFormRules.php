<?php

namespace App\Support\AdminForms;

use App\Enums\Municipality;

final class AttireFormRules
{
    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'name_general'          => 'required|string|max:255',
            'municipality'          => 'required|in:'.Municipality::validationList(),
            'gender'                => 'required|in:women,men',
            'name_dialect'          => 'nullable|string|max:255',
            'description'           => 'nullable|string|max:1500',
            'material'              => 'nullable|string|max:255',
            'cultural_significance' => 'nullable|string|max:2000',
            'source_info'           => 'nullable|string|max:500',
            'image'                 => FormRules::image(),
        ];
    }

    /** @return array<string, string> */
    public static function messages(): array
    {
        return [
            'name_general.required' => 'Enter the general name.',
            'municipality.required' => 'Select a municipality.',
            'municipality.in'       => 'Choose one of the 11 Ifugao municipalities.',
            'gender.required'       => 'Select women\'s or men\'s attire.',
            'gender.in'             => 'Gender must be women or men.',
            'image.image'           => 'Upload a JPG or PNG image.',
            'image.max'             => 'Image must be 10 MB or smaller.',
        ];
    }

    /** @param  array<string, mixed>  $validated */
    public static function normalize(array $validated): array
    {
        $validated = FormRules::nullIfBlank($validated, [
            'material', 'cultural_significance', 'source_info',
        ]);

        // These columns are NOT NULL — store '' when left blank.
        foreach (['name_dialect', 'description'] as $key) {
            if (array_key_exists($key, $validated) && ! filled($validated[$key])) {
                $validated[$key] = '';
            }
        }

        return $validated;
    }
}
