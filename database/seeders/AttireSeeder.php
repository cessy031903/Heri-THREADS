<?php

namespace Database\Seeders;

use App\Models\Attire;
use Illuminate\Database\Seeder;

class AttireSeeder extends Seeder
{
    public function run(): void
    {
        $attires = [
            // ── BANAUE ──────────────────────────────────────────────────
            [
                'name_general'  => 'Wraparound Skirt',
                'name_dialect'  => 'Tapis',
                'municipality'  => 'Banaue',
                'gender'        => 'women',
                'description'   => 'The Tapis is a hand-woven wraparound skirt worn by Ifugao women of Banaue during rituals and celebrations. It is woven from abaca or cotton threads dyed in rich earth tones — deep reds, blacks, and ochres — using backstrap looms. The geometric patterns embedded in the cloth carry coded meanings about the wearer\'s lineage and social standing.',
                'source_info'   => 'National Museum of the Philippines',
                'image_path'    => null,
            ],
            [
                'name_general'  => 'Loincloth',
                'name_dialect'  => 'Wanno',
                'municipality'  => 'Banaue',
                'gender'        => 'men',
                'description'   => 'The Wanno is the traditional male loincloth of the Ifugao people of Banaue. Woven in hand-dyed threads with intricate stripe and diamond patterns, it wraps around the waist and hangs at the front and back. Warriors and priests alike wore the Wanno as a mark of identity, with the complexity of its weave indicating social rank.',
                'source_info'   => 'Banaue Museum Collection',
                'image_path'    => null,
            ],
            [
                'name_general'  => 'Beaded Vest',
                'name_dialect'  => 'Kambaya',
                'municipality'  => 'Banaue',
                'gender'        => 'women',
                'description'   => 'The Kambaya is a sleeveless ceremonial vest worn by Banaue women during the Uyauy feast and other prestige rituals. It is adorned with intricate beadwork in red, white, and black patterns representing ancestral totems. The Kambaya is considered a family heirloom, passed from mother to daughter across generations.',
                'source_info'   => 'Cordillera Studies Center, UP Baguio',
                'image_path'    => null,
            ],

            // ── KIANGAN ─────────────────────────────────────────────────
            [
                'name_general'  => 'Woven Blouse',
                'name_dialect'  => 'Gawi',
                'municipality'  => 'Kiangan',
                'gender'        => 'women',
                'description'   => 'The Gawi is a short-sleeved ceremonial blouse handwoven by Kiangan artisans. Its distinctive feature is its front panel — decorated with rows of triangular motifs in contrasting colors that symbolize mountain peaks and rice terraces. It is paired with the Tapis during harvest festivals and ancestral rites.',
                'source_info'   => 'Kiangan Municipal Tourism Office',
                'image_path'    => null,
            ],
            [
                'name_general'  => 'Warrior\'s Cloak',
                'name_dialect'  => 'Blanket',
                'municipality'  => 'Kiangan',
                'gender'        => 'men',
                'description'   => 'The ceremonial blanket of Kiangan warriors is a thick, heavy woven textile draped over one shoulder. Dyed in deep indigo and rust with bold horizontal stripes, it was worn by menbul during the Taddok victory dance and headhunting ceremonies. Owning a complete set of warrior\'s attire was a mark of the highest masculine honor.',
                'source_info'   => 'National Historical Commission of the Philippines',
                'image_path'    => null,
            ],

            // ── MAYOYAO ─────────────────────────────────────────────────
            [
                'name_general'  => 'Festival Skirt',
                'name_dialect'  => 'Gipang',
                'municipality'  => 'Mayoyao',
                'gender'        => 'women',
                'description'   => 'The Gipang is a long festive skirt unique to Mayoyao weavers, known for its exceptionally fine thread count and intricate floating-weft patterns. Unlike skirts from other municipalities, the Gipang features elongated diamond chains that reference the Mayoyao rice terraces. It is worn folded at the waist during the Kinnallogong harvest celebration.',
                'source_info'   => 'Mayoyao Cultural Heritage Society',
                'image_path'    => null,
            ],
            [
                'name_general'  => 'Ritual Headband',
                'name_dialect'  => 'Putong',
                'municipality'  => 'Mayoyao',
                'gender'        => 'men',
                'description'   => 'The Putong is a narrow woven headband worn by Mayoyao men during rituals and harvest celebrations. Its tight weave incorporates red and black geometric symbols that identify the wearer\'s clan. Munabuy priests wear a wider version of the Putong adorned with boar tusk fragments during healing ceremonies.',
                'source_info'   => 'Ifugao State University, Research Journal Vol. 3',
                'image_path'    => null,
            ],

            // ── HUNGDUAN ─────────────────────────────────────────────────
            [
                'name_general'  => 'Ceremonial Wrap',
                'name_dialect'  => 'Hablon',
                'municipality'  => 'Hungduan',
                'gender'        => 'women',
                'description'   => 'The Hablon of Hungduan is a full-body ceremonial wrap distinguished by its wide, bold stripes in deep burgundy and black. Women of Hungduan wear it during the Punnuk rice ritual, wrapped tightly around the torso and secured at the shoulder. The red-heavy color palette is said to invoke the blessing of the Bulul rice gods.',
                'source_info'   => 'Hungduan Weaving Cooperative',
                'image_path'    => null,
            ],
        ];

        foreach ($attires as $attire) {
            Attire::firstOrCreate(
                ['name_general' => $attire['name_general'], 'municipality' => $attire['municipality']],
                $attire
            );
        }
    }
}
