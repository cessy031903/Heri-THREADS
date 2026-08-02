<?php

namespace App\Support\AdminForms;

final class ShowcaseFormRules
{
    /** @return array<string, mixed> */
    public static function rules(bool $isEditing = false): array
    {
        return [
            'label'     => 'required|string|max:255',
            'sub_label' => 'nullable|string|max:255',
            'link_url'  => 'nullable|string|max:2048',
            'image'     => FormRules::image(required: ! $isEditing),
        ];
    }

    /** @return array<string, string> */
    public static function messages(): array
    {
        return [
            'label.required' => 'Enter a label for this photo.',
            'image.required' => 'Upload a photo for the homepage carousel.',
            'image.image'    => 'Upload a JPG or PNG image.',
            'image.max'      => 'Image must be 10 MB or smaller.',
        ];
    }

    /** @param  array<string, mixed>  $validated */
    public static function normalize(array $validated): array
    {
        return FormRules::nullIfBlank($validated, ['sub_label', 'link_url']);
    }
}
