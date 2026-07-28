<?php

namespace App\Livewire;

use App\Models\Dance;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ExploreDances extends Component
{
    use WithPagination;

    public ?int $selectedDanceId = null;
    public bool $showModal = false;
    public string $categoryFilter = '';
    public string $search = '';

    public array $categories = [
        ['id' => '',          'name' => 'All'],
        ['id' => 'pagaddut',  'name' => 'Pagaddut'],
        ['id' => 'hinggatut', 'name' => 'Hinggatut'],
        ['id' => 'dinuy-a',   'name' => 'Dinuy-a'],
    ];

    #[Computed]
    public function dances()
    {
        return Dance::query()
            ->when($this->categoryFilter, fn ($q) => $q->where('category', $this->categoryFilter))
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('municipality', 'like', "%{$this->search}%");
            }))
            ->orderBy('name')
            ->paginate(12);
    }

    #[Computed]
    public function selectedDance(): ?Dance 
    {
        return $this->selectedDanceId ? Dance::find($this->selectedDanceId) : null;
    }

    /** Other dances in the same category, for the "Related" section. */
    #[Computed]
    public function relatedDances()
    {
        if (! $this->selectedDance) {
            return collect();
        }

        return Dance::where('category', $this->selectedDance->category)
            ->where('id', '!=', $this->selectedDance->id)
            ->orderBy('name')
            ->limit(6)
            ->get();
    }

    public function selectDance(int $id): void
    {
        $this->selectedDanceId = $id;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedDanceId = null;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->categoryFilter = '';
        $this->resetPage();
        unset($this->dances);
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
        unset($this->dances);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
        unset($this->dances);
    }

    public function render()
    {
        return view('livewire.explore-dances')
            ->layout('layouts.app', [
                'title'       => 'Explore Dances — Heri-THREADS',
                'description' => 'Discover the sacred ritual dances of Ifugao — Pagaddut, Hinggatut, and Dinuy-a — preserved from generations of Cordillera highland tradition.',
            ]);
    }
}
