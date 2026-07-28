<?php

use App\Enums\Municipality;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->legacySlugs() as $slug) {
            $municipality = Municipality::fromLegacySlug($slug);
            if ($municipality === null) {
                continue;
            }

            DB::table('dances')
                ->where('municipality', $slug)
                ->update(['municipality' => $municipality->value]);
        }
    }

    public function down(): void
    {
        $labelToSlug = [];
        foreach ($this->legacySlugs() as $slug) {
            $municipality = Municipality::fromLegacySlug($slug);
            if ($municipality !== null) {
                $labelToSlug[$municipality->value] = $slug;
            }
        }

        foreach ($labelToSlug as $label => $slug) {
            DB::table('dances')
                ->where('municipality', $label)
                ->update(['municipality' => $slug]);
        }
    }

    /** @return list<string> */
    private function legacySlugs(): array
    {
        return [
            'aguinaldo', 'alista', 'asipulo', 'banaue', 'hingyon',
            'hungduan', 'kiangan', 'lagawe', 'lamut', 'mayoyao', 'tinoc',
        ];
    }
};
