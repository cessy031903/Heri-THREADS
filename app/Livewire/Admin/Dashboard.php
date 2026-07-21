<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Attire;
use App\Models\Dance;
use App\Models\InteractiveGuide;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Dashboard extends Component
{
    #[Computed]
    public function stats(): array
    {
        return [
            ['title' => 'Total Dances',   'value' => Dance::count()],
            ['title' => 'Total Attires',  'value' => Attire::count()],
            ['title' => 'Municipalities', 'value' => Attire::distinct('municipality')->count()],
        ];
    }

    #[Computed]
    public function guidesCount(): int
    {
        return InteractiveGuide::count();
    }

    /** Dance counts per category, for the CSS bar chart. */
    #[Computed]
    public function danceByCategory(): array
    {
        $labels = ['pagaddut' => 'Pagaddut', 'hinggatut' => 'Hinggatut', 'dinuy-a' => 'Dinuy-a'];
        $counts = Dance::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $rows = [];
        foreach ($labels as $key => $label) {
            $rows[] = ['label' => $label, 'value' => (int) ($counts[$key] ?? 0)];
        }

        return $rows;
    }

    /** Attire counts per municipality (top of the list), for the CSS bar chart. */
    #[Computed]
    public function attireByMunicipality(): array
    {
        return Attire::selectRaw('municipality, COUNT(*) as total')
            ->groupBy('municipality')
            ->orderByDesc('total')
            ->limit(6)
            ->get()
            ->map(fn ($r) => ['label' => $r->municipality, 'value' => (int) $r->total])
            ->all();
    }

    #[Computed]
    public function recentActivity()
    {
        return AuditLog::latest()->take(8)->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
