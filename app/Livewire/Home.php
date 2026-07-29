<?php

namespace App\Livewire;

use App\Models\Attire;
use App\Models\Dance;
use App\Support\PlaceholderPalette;
use Illuminate\Support\Facades\Cache;
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
        return Cache::remember('home.dance-count', 3600, fn () => Dance::count());
    }

    #[Computed]
    public function attireCount(): int
    {
        return Cache::remember('home.attire-count', 3600, fn () => Attire::count());
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

    /** Number of items the showcase carousel cycles through. */
    private const SHOWCASE_SIZE = 10;

    /**
     * Visual items for the homepage showcase carousel.
     * Prefers records that have an uploaded image; falls back to themed
     * gradient placeholders so the carousel always looks complete.
     */
    #[Computed]
    public function showcaseItems(): array
    {
        return Cache::remember('home.showcase-items', 3600, fn () => $this->buildShowcaseItems());
    }

    private function buildShowcaseItems(): array
    {
        $items = collect();

        // Dances first (most visual), then attires, preferring those with images.
        Dance::query()->orderByRaw('image_path is null')->take(self::SHOWCASE_SIZE)->get()
            ->each(function ($d) use (&$items) {
                $items->push([
                    'label' => $d->name,
                    'sub'   => ucfirst((string) $d->category),
                    'image' => $d->image_path ? Storage::disk('public')->url($d->image_path) : null,
                    'href'  => route('dances'),
                ]);
            });

        if ($items->count() < self::SHOWCASE_SIZE) {
            Attire::query()->whereNotNull('image_path')->take(self::SHOWCASE_SIZE - $items->count())->get()
                ->each(function ($a) use (&$items) {
                    $items->push([
                        'label' => $a->name_general,
                        'sub'   => $a->municipality,
                        'image' => Storage::disk('public')->url($a->image_path),
                        'href'  => route('attires'),
                    ]);
                });
        }

        // Pad up to SHOWCASE_SIZE slots with culturally themed placeholders.
        $placeholders = [
            ['label' => 'Sacred Dances',  'sub' => 'Ritual',        'href' => route('dances')],
            ['label' => 'Woven Attires',  'sub' => 'Textiles',      'href' => route('attires')],
            ['label' => 'Banaue',         'sub' => 'Eighth Wonder', 'href' => route('attires')],
            ['label' => 'Kiangan',        'sub' => 'Heritage',      'href' => route('attires')],
            ['label' => 'Hungduan',       'sub' => 'Highland',      'href' => route('attires')],
            ['label' => 'Lagawe',         'sub' => 'Provincial Capital', 'href' => route('attires')],
            ['label' => 'Aguinaldo',      'sub' => 'Traditions',    'href' => route('attires')],
            ['label' => 'Asipulo',        'sub' => 'Mountain Springs', 'href' => route('attires')],
            ['label' => 'Hingyon',        'sub' => 'Village of Weavers', 'href' => route('attires')],
            ['label' => 'Mayoyao',        'sub' => 'Where Eagles Soar', 'href' => route('attires')],
        ];
        $p = 0;
        while ($items->count() < self::SHOWCASE_SIZE) {
            $items->push(array_merge($placeholders[$p % count($placeholders)], ['image' => null]));
            $p++;
        }

        return $items->take(self::SHOWCASE_SIZE)->values()
            ->map(function ($item, $i) {
                $item['palette'] = PlaceholderPalette::showcase($i);
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
