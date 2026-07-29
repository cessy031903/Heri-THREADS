<?php

namespace App\Livewire;

use App\Models\Attire;
use App\Models\Dance;
use App\Models\ShowcasePhoto;
use App\Support\PlaceholderPalette;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Home extends Component
{
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

    /**
     * Visual items for the homepage showcase carousel, sourced entirely
     * from admin-uploaded photos (Manage Home Showcase) rather than Dance
     * or Attire records — these are meant to be the client's own paired
     * attire photos, unconnected to the archive's catalog data. Adapts to
     * however many photos exist; empty means the carousel section is
     * hidden (see hero-gallery @if in home.blade.php).
     */
    #[Computed]
    public function showcaseItems(): array
    {
        return Cache::remember('home.showcase-items', 3600, fn () => $this->buildShowcaseItems());
    }

    private function buildShowcaseItems(): array
    {
        return ShowcasePhoto::query()->orderBy('order')->get()
            ->values()
            ->map(fn ($photo, $i) => [
                'label'   => $photo->label,
                'sub'     => $photo->sub_label,
                'image'   => Storage::disk('public')->url($photo->image_path),
                'href'    => $photo->link_url ?: null,
                'palette' => PlaceholderPalette::showcase($i),
            ])
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
