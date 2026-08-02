<?php

namespace App\Support\AdminForms;

use App\Enums\Municipality;
use Illuminate\Validation\Rule;

final class GuideFormRules
{
    /** @return array<string, mixed> */
    public static function rules(?int $editingId = null): array
    {
        return [
            'municipality' => [
                'required',
                'in:'.Municipality::validationList(),
                Rule::unique('interactive_guides', 'municipality')->ignore($editingId),
            ],
            'title'                    => 'required|string|max:255',
            'instruction'              => 'nullable|string|max:255',
            'image'                    => FormRules::image(),
            'cardImage'                => FormRules::image(),
            'hotspots'                 => 'array',
            'hotspots.*.label'         => 'required|string|max:255',
            'hotspots.*.description'   => 'nullable|string|max:1000',
            'hotspots.*.pos_x'         => 'required|numeric|min:0|max:100',
            'hotspots.*.pos_y'         => 'required|numeric|min:0|max:100',
            'hotspots.*.attire_id'     => 'nullable|integer|exists:attires,id',
        ];
    }

    /** @return array<string, string> */
    public static function messages(): array
    {
        return [
            'municipality.required'         => 'Select a municipality.',
            'municipality.unique'           => 'This municipality already has a guide — edit that one instead.',
            'title.required'                => 'Enter a guide title.',
            'hotspots.*.label.required'     => 'Each hotspot needs a label.',
            'hotspots.*.pos_x.required'     => 'Set the horizontal position (0–100).',
            'hotspots.*.pos_y.required'     => 'Set the vertical position (0–100).',
            'hotspots.*.attire_id.exists'   => 'The linked attire no longer exists.',
            'image.image'                   => 'Background image must be JPG or PNG.',
            'cardImage.image'               => 'Card image must be JPG or PNG.',
        ];
    }

    /** @param  array<string, mixed>  $validated */
    public static function normalize(array $validated): array
    {
        return FormRules::nullIfBlank($validated, ['instruction']);
    }
}
