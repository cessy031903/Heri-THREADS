<?php

namespace Tests\Feature;

use App\Livewire\ExploreAttires;
use App\Livewire\ExploreDances;
use App\Models\Attire;
use App\Models\Dance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Region/Origin/Cultural Meaning/Historical Background (Dances) and
 * Fabric/Material (Attires) were removed from the admin forms; this
 * verifies they're also hidden from the public detail view for records
 * that already have that data saved, not just new ones.
 */
class PublicDetailFieldRemovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_dance_modal_hides_region_origin_and_removed_text_blocks(): void
    {
        $dance = Dance::create([
            'name'                   => 'Test Dance',
            'category'               => 'pagaddut',
            'description'            => 'A test description',
            'region'                 => 'Cordillera Administrative Region',
            'origin'                 => 'Ifugao Province',
            'cultural_meaning'       => 'Some cultural meaning text',
            'historical_background'  => 'Some historical background text',
        ]);

        Livewire::test(ExploreDances::class)
            ->call('selectDance', $dance->id)
            ->assertDontSee('Cordillera Administrative Region')
            ->assertDontSee('Ifugao Province')
            ->assertDontSee('Some cultural meaning text')
            ->assertDontSee('Some historical background text')
            ->assertDontSee('Cultural Meaning')
            ->assertDontSee('Historical Background')
            ->assertSee('Test Dance')
            ->assertSee('Pagaddut'); // category badge still shows
    }

    public function test_attire_modal_hides_fabric_material_but_keeps_source(): void
    {
        $attire = Attire::create([
            'name_general'  => 'Test Attire',
            'name_dialect'  => 'Test Dialect',
            'municipality'  => 'Asipulo',
            'gender'        => 'women',
            'description'   => 'A test description',
            'material'      => 'Handwoven cotton',
            'source_info'   => 'National Museum',
        ]);

        Livewire::test(ExploreAttires::class)
            ->call('selectAttire', $attire->id)
            ->assertDontSee('Handwoven cotton')
            ->assertDontSee('Fabric / Material')
            ->assertSee('National Museum') // source_info still shown
            ->assertSee('Test Attire');
    }
}
