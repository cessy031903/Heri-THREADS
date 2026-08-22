<?php

namespace Tests\Feature;

use App\Livewire\Admin\Attires\ManageAttires;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Cultural Significance and Source/Reference were re-added to the Add
 * Attire admin form after being removed alongside Material; Material
 * itself stays removed. This checks the rendered form markup, not just
 * the underlying Livewire properties.
 */
class AttireFormFieldsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_add_attire_form_shows_cultural_significance_and_source_but_not_material(): void
    {
        Livewire::test(ManageAttires::class)
            ->call('openCreate')
            ->assertSee('Cultural Significance')
            ->assertSee('Source / Reference')
            ->assertDontSee('Fabric / Material');
    }

    public function test_saving_attire_with_cultural_significance_and_source_persists_them(): void
    {
        Livewire::test(ManageAttires::class)
            ->set('name_general', 'Bulul Belt')
            ->set('name_dialect', 'Ginaway')
            ->set('municipality', 'Kiangan')
            ->set('gender', 'women')
            ->set('description', 'A woven belt')
            ->set('cultural_significance', 'Worn during rituals')
            ->set('source_info', 'National Museum')
            ->call('save');

        $attire = \App\Models\Attire::where('name_general', 'Bulul Belt')->firstOrFail();
        $this->assertSame('Worn during rituals', $attire->cultural_significance);
        $this->assertSame('National Museum', $attire->source_info);
        $this->assertNull($attire->material);
    }
}
