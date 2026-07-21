<?php

namespace Database\Seeders;

use App\Models\InteractiveGuide;
use Illuminate\Database\Seeder;

class GuideSeeder extends Seeder
{
    /**
     * Every Ifugao municipality gets an editable Interactive Guide row.
     * Banaue ships with a fully worked example; the rest are placeholders the
     * admin can flesh out (upload an image, click to place hotspots).
     *
     * Idempotent + non-destructive: existing guides are never overwritten, so
     * re-seeding won't wipe content edited in the admin panel.
     */
    public function run(): void
    {
        $municipalities = [
            'Alfonso Lista', 'Aguinaldo', 'Asipulo', 'Banaue', 'Hingyon',
            'Hungduan', 'Kiangan', 'Lagawe', 'Lamut', 'Mayoyao', 'Tinoc',
        ];

        foreach ($municipalities as $municipality) {
            InteractiveGuide::firstOrCreate(
                ['municipality' => $municipality],
                [
                    'title'       => "{$municipality} Traditional Attire — Interactive Guide",
                    'instruction' => 'Hover or tap each number to explore',
                    'image_path'  => null,
                ]
            );
        }

        $this->seedBanaueExample();
    }

    /** Worked example so the feature has real content out of the box. */
    private function seedBanaueExample(): void
    {
        $guide = InteractiveGuide::where('municipality', 'Banaue')->first();

        // Only populate the demo hotspots once, never clobber later edits.
        if (! $guide || $guide->hotspots()->exists()) {
            return;
        }

        $guide->update([
            'title'       => 'Traditional Paired Dress — Interactive Guide',
            'instruction' => 'Hover or tap each number to explore',
        ]);

        $hotspots = [
            ['label' => 'Headdress',     'description' => 'The woven headpiece worn during rituals and gatherings, often adorned with feathers or beads.', 'pos_x' => 50, 'pos_y' => 14],
            ['label' => "Women's Upper", 'description' => 'The handwoven blouse with intricate supplementary-weft patterns unique to the locale.',          'pos_x' => 30, 'pos_y' => 40],
            ['label' => "Men's Upper",   'description' => 'The bare or lightly covered upper body, sometimes paired with a woven sash or beaded ornament.',  'pos_x' => 70, 'pos_y' => 40],
            ['label' => "Women's Lower", 'description' => 'The tapis (wraparound skirt) whose stripes and motifs signal village identity and status.',       'pos_x' => 33, 'pos_y' => 66],
            ['label' => "Men's Lower",   'description' => 'The wanno (g-string / loincloth), the traditional lower garment woven for everyday and ritual use.', 'pos_x' => 67, 'pos_y' => 66],
            ['label' => 'Accessories',   'description' => 'Beads, amulets, and woven belts that complete the paired dress and carry symbolic meaning.',       'pos_x' => 50, 'pos_y' => 86],
        ];

        foreach ($hotspots as $i => $h) {
            $guide->hotspots()->create(array_merge($h, ['order' => $i]));
        }
    }
}
