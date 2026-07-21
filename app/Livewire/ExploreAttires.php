<?php

namespace App\Livewire;

use App\Models\Attire;
use App\Models\InteractiveGuide;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ExploreAttires extends Component
{
    public ?string $selectedMunicipality = null;

    public ?int $selectedAttireId = null;
    public bool $showAttireModal = false;

    public array $municipalities = [
        'Alfonso Lista',
        'Aguinaldo',
        'Asipulo',
        'Banaue',
        'Hingyon',
        'Hungduan',
        'Kiangan',
        'Lagawe',
        'Lamut',
        'Mayoyao',
        'Tinoc',
    ];

    #[Computed]
    public function womenAttires()
    {
        if (! $this->selectedMunicipality) {
            return collect();
        }

        return Attire::where('municipality', $this->selectedMunicipality)
            ->where('gender', 'women')
            ->orderBy('name_general')
            ->get();
    }

    #[Computed]
    public function menAttires()
    {
        if (! $this->selectedMunicipality) {
            return collect();
        }

        return Attire::where('municipality', $this->selectedMunicipality)
            ->where('gender', 'men')
            ->orderBy('name_general')
            ->get();
    }

    #[Computed]
    public function guide(): ?InteractiveGuide
    {
        if (! $this->selectedMunicipality) {
            return null;
        }

        return InteractiveGuide::with('hotspots.attire')
            ->where('municipality', $this->selectedMunicipality)
            ->first();
    }

    #[Computed]
    public function selectedAttire(): ?Attire
    {
        return $this->selectedAttireId ? Attire::find($this->selectedAttireId) : null;
    }

    /** Other attires in the same municipality, for the "Related" row. */
    #[Computed]
    public function relatedAttires()
    {
        if (! $this->selectedAttire) {
            return collect();
        }

        return Attire::where('municipality', $this->selectedAttire->municipality)
            ->where('id', '!=', $this->selectedAttire->id)
            ->orderBy('name_general')
            ->limit(6)
            ->get();
    }

    public function selectMunicipality(string $municipality): void
    {
        $this->selectedMunicipality = $municipality;
        unset($this->womenAttires, $this->menAttires, $this->guide);
    }

    public function clearMunicipality(): void
    {
        $this->selectedMunicipality = null;
        unset($this->womenAttires, $this->menAttires, $this->guide);
    }

    public function selectAttire(int $id): void
    {
        $this->selectedAttireId = $id;
        $this->showAttireModal = true;
    }

    public function closeAttireModal(): void
    {
        $this->showAttireModal = false;
        $this->selectedAttireId = null;
    }

    public function render()
    {
        return view('livewire.explore-attires')
            ->layout('layouts.app', [
                'title'       => 'Explore Attires — Heri-THREADS',
                'description' => 'Browse the traditional woven attires of Ifugao\'s 11 municipalities — each textile tells a story of heritage, identity, and the weavers who carry it forward.',
            ]);
    }
}
