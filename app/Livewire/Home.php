<?php

namespace App\Livewire;

use App\Models\Attire;
use App\Models\Dance;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Home extends Component
{
    public array $carouselMunicipalities = [
       
        ['name' => 'Banaue',    'tagline' => 'Home of the Eighth Wonder'],
        ['name' => 'Kiangan',   'tagline' => 'Cradle of Ifugao Civilization'],
        ['name' => 'Hungduan',  'tagline' => 'Heart of Highland Tradition'],
        ['name' => 'Lagawe',    'tagline' => 'Provincial Capital of Ifugao'],
        ['name' => 'Aguinaldo', 'tagline' => 'Where Traditions Breathe'],
        
    ];

    #[Computed]
    public function danceCount(): int
    {
        return Dance::count();
    }

    #[Computed]
    public function attireCount(): int
    {
        return Attire::count();
    }

    #[Computed]
    public function munCount(): int
    {
        return 11;
    }

    #[Computed]
    public function featuredDance(): ?Dance
    {
        return Dance::inRandomOrder()->first();
    }

    /**
     * Up to 5 visual items for the homepage showcase gallery.
     * Prefers records that have an uploaded image; falls back to themed
     * gradient placeholders so the gallery always looks complete.
     */
    #[Computed]
    public function showcaseItems(): array
    {
        $palettes = [
            ['#7B3A10', '#C4854A'],
            ['#1A3A10', '#3A7A24'],
            ['#5C1F1F', '#C85A17'],
            ['#3A2A10', '#A0824D'],
            ['#1A2A4A', '#3A6A95'],
        ];

        $items = collect();

        // Dances first (most visual), then attires, preferring those with images.
        Dance::query()->orderByRaw('image_path is null')->take(5)->get()
            ->each(function ($d) use (&$items) {
                $items->push([
                    'label' => $d->name,
                    'sub'   => ucfirst((string) $d->category),
                    'image' => $d->image_path ? Storage::disk('public')->url($d->image_path) : null,
                    'href'  => route('dances'),
                ]);
            });

        if ($items->count() < 5) {
            Attire::query()->whereNotNull('image_path')->take(5 - $items->count())->get()
                ->each(function ($a) use (&$items) {
                    $items->push([
                        'label' => $a->name_general,
                        'sub'   => $a->municipality,
                        'image' => Storage::disk('public')->url($a->image_path),
                        'href'  => route('attires'),
                    ]);
                });
        }

        // Pad to exactly 5 slots with culturally themed placeholders.
        $placeholders = [
            ['label' => 'Sacred Dances',  'sub' => 'Ritual',        'href' => route('dances')],
            ['label' => 'Woven Attires',  'sub' => 'Textiles',      'href' => route('attires')],
            ['label' => 'Banaue',         'sub' => 'Eighth Wonder', 'href' => route('attires')],
            ['label' => 'Kiangan',        'sub' => 'Heritage',      'href' => route('attires')],
            ['label' => 'Hungduan',       'sub' => 'Highland',      'href' => route('attires')],
        ];
        $p = 0;
        while ($items->count() < 5) {
            $items->push(array_merge($placeholders[$p % count($placeholders)], ['image' => null]));
            $p++;
        }

        return $items->take(5)->values()
            ->map(function ($item, $i) use ($palettes) {
                $item['palette'] = $palettes[$i % count($palettes)];
                return $item;
            })
            ->all();
    }

    public function render()
    {
        return view('livewire.home')
            ->layout('layouts.app', [
                'title'       => 'Heri-THREADS — Ifugao Cultural Archive',
                'description' => 'A living digital archive of Ifugao traditional dances and woven attires — preserving the cultural heritage of 11 municipalities in the Cordillera highlands.',
            ]);
    }
}
