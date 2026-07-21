<?php

namespace Database\Seeders;

use App\Models\Dance;
use Illuminate\Database\Seeder;

class DanceSeeder extends Seeder
{
    public function run(): void
    {
        $dances = [
            [
                'name'        => 'Uyauy',
                'category'    => 'hinggatut',
                'description' => 'The Uyauy is the most prestigious ceremonial dance of the Ifugao, performed during the Uyauy feast — a grand celebration hosted by wealthy families to honor their ancestors and elevate their social standing. Dancers wear full traditional regalia, moving in slow, deliberate circular patterns that reflect reverence for the spirits and gratitude for bountiful harvests.',
                'video_url'   => null,
                'image_path'  => null,
            ],
            [
                'name'        => 'Taddok',
                'category'    => 'pagaddut',
                'description' => 'The Taddok is a victory dance traditionally performed after a successful headhunting raid or the resolution of a tribal conflict. Warriors stomp and leap in rhythmic patterns, brandishing their weapons and shields. Today it is performed at cultural festivals as a celebration of Ifugao warrior heritage and bravery.',
                'video_url'   => null,
                'image_path'  => null,
            ],
            [
                'name'        => 'Punnuk',
                'category'    => 'dinuy-a',
                'description' => 'Punnuk is a sacred ritual dance performed during planting and harvest seasons to appease the Ifugao rice gods, the Bulul. Elders lead the dance in slow, meditative movements accompanied by gongs, invoking blessings for the fields and protection against crop failure. It is one of the most spiritually significant dances in Ifugao culture.',
                'video_url'   => null,
                'image_path'  => null,
            ],
            [
                'name'        => 'Bumayah',
                'category'    => 'pagaddut',
                'description' => 'The Bumayah is a celebratory dance performed during peacetime agreements and the conclusion of tribal disputes. Communities from opposing villages dance together as a symbol of reconciliation and renewed kinship. Participants link arms and move in synchronized formations representing the weaving together of two communities.',
                'video_url'   => null,
                'image_path'  => null,
            ],
            [
                'name'        => 'Kinnallogong',
                'category'    => 'hinggatut',
                'description' => 'Kinnallogong is a harvest celebration dance performed by the community after rice is brought down from the fields. Women in colorful woven skirts move gracefully in circles, mimicking the swaying of rice stalks in the wind, while men beat gongs in escalating rhythms. The dance gives thanks to the earth and to the labor of the farming community.',
                'video_url'   => null,
                'image_path'  => null,
            ],
            [
                'name'        => 'Hagabi',
                'category'    => 'dinuy-a',
                'description' => 'The Hagabi is a prestige dance performed exclusively during the Hagabi feast, a rare and costly celebration hosted only by the wealthiest Ifugao families. The host family acquires a massive hardwood bench — the hagabi — as a symbol of elite status. The accompanying dance involves the community in joyful procession marking the family\'s elevation to the highest social rank.',
                'video_url'   => null,
                'image_path'  => null,
            ],
            [
                'name'        => 'Bogwa',
                'category'    => 'dinuy-a',
                'description' => 'The Bogwa accompanies the secondary burial ritual of the same name, in which the bones of deceased ancestors are exhumed, cleaned, and reburied with new offerings. Performed solemnly by family members and elders, the dance honors the spirit\'s continued presence among the living and reaffirms the bond between ancestors and their descendants.',
                'video_url'   => null,
                'image_path'  => null,
            ],
            [
                'name'        => 'Bago',
                'category'    => 'pagaddut',
                'description' => 'Bago is a welcoming dance performed to greet honored guests, visiting dignitaries, or returning warriors. Young men and women form two lines and perform mirrored gestures of hospitality — outstretched arms, rhythmic steps, and graceful turns — accompanied by the steady beat of the gangsa gongs. It represents the Ifugao tradition of warmth and generous hospitality.',
                'video_url'   => null,
                'image_path'  => null,
            ],
        ];

        foreach ($dances as $dance) {
            Dance::firstOrCreate(['name' => $dance['name']], $dance);
        }
    }
}
