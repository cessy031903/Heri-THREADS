<?php

namespace App\Livewire\Admin\Guides;

use App\Models\Attire;
use App\Models\AuditLog;
use App\Models\GuideHotspot;
use App\Models\InteractiveGuide;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageGuides extends Component
{
    use WithFileUploads;

    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    // Guide fields
    public string $municipality = '';
    public string $title        = '';
    public string $instruction  = '';
    public $image;
    public ?string $existingImagePath = null;

    // Nested hotspots (array of rows)
    public array $hotspots = [];

    public array $municipalities = [
        'Alfonso Lista', 'Aguinaldo', 'Asipulo', 'Banaue', 'Hingyon',
        'Hungduan', 'Kiangan', 'Lagawe', 'Lamut', 'Mayoyao', 'Tinoc',
    ];

    protected function rules(): array
    {
        return [
            'municipality' => [
                'required',
                'in:Alfonso Lista,Aguinaldo,Asipulo,Banaue,Hingyon,Hungduan,Kiangan,Lagawe,Lamut,Mayoyao,Tinoc',
                Rule::unique('interactive_guides', 'municipality')->ignore($this->editingId),
            ],
            'title'              => 'required|string|max:255',
            'instruction'        => 'nullable|string|max:255',
            'image'              => $this->isEditing ? 'nullable|image|mimes:jpeg,png,jpg|max:10240' : 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'hotspots'             => 'array',
            'hotspots.*.label'     => 'required|string|max:255',
            'hotspots.*.description' => 'nullable|string|max:1000',
            'hotspots.*.pos_x'     => 'required|numeric|min:0|max:100',
            'hotspots.*.pos_y'     => 'required|numeric|min:0|max:100',
            'hotspots.*.attire_id' => 'nullable|integer|exists:attires,id',
        ];
    }

    protected function messages(): array
    {
        return [
            'municipality.unique'   => 'This municipality already has a guide. Edit the existing one instead.',
            'hotspots.*.label.required' => 'Each hotspot needs a label.',
        ];
    }

    #[Computed]
    public function guides()
    {
        return InteractiveGuide::withCount('hotspots')
            ->orderBy('municipality')
            ->get();
    }

    /** Attires of the currently selected municipality, for optional hotspot links. */
    #[Computed]
    public function attireOptions()
    {
        if (! $this->municipality) {
            return collect();
        }

        return Attire::where('municipality', $this->municipality)
            ->orderBy('name_general')
            ->get(['id', 'name_general', 'gender']);
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $guide = InteractiveGuide::with('hotspots')->findOrFail($id);

        $this->editingId         = $id;
        $this->municipality      = $guide->municipality;
        $this->title             = $guide->title;
        $this->instruction       = (string) $guide->instruction;
        $this->existingImagePath = $guide->image_path;
        $this->hotspots = $guide->hotspots->map(fn ($h) => [
            'id'          => $h->id,
            'label'       => $h->label,
            'description' => (string) $h->description,
            'pos_x'       => (float) $h->pos_x,
            'pos_y'       => (float) $h->pos_y,
            'attire_id'   => $h->attire_id,
        ])->toArray();

        $this->isEditing = true;
        $this->showModal = true;
        $this->resetValidation();
    }

    public function addHotspot(): void
    {
        $this->hotspots[] = [
            'id'          => null,
            'label'       => '',
            'description' => '',
            'pos_x'       => 50,
            'pos_y'       => 50,
            'attire_id'   => null,
        ];
    }

    /** Click-to-place: add a hotspot at the clicked position on the preview image. */
    public function addHotspotAt($x, $y): void
    {
        $this->hotspots[] = [
            'id'          => null,
            'label'       => '',
            'description' => '',
            'pos_x'       => $this->clampPct($x),
            'pos_y'       => $this->clampPct($y),
            'attire_id'   => null,
        ];
    }

    /** Drag-to-move: update an existing hotspot's position. */
    public function moveHotspot(int $index, $x, $y): void
    {
        if (! isset($this->hotspots[$index])) {
            return;
        }
        $this->hotspots[$index]['pos_x'] = $this->clampPct($x);
        $this->hotspots[$index]['pos_y'] = $this->clampPct($y);
    }

    private function clampPct($value): float
    {
        return round(max(0, min(100, (float) $value)), 1);
    }

    public function removeHotspot(int $index): void
    {
        unset($this->hotspots[$index]);
        $this->hotspots = array_values($this->hotspots);
    }

    public function save(): void
    {
        $validated = $this->validate();
        if ($this->image) {
    logger()->info('Image upload received', [
        'name' => $this->image->getClientOriginalName(),
        'size' => $this->image->getSize(),
    ]);
}
        $imagePath = $this->existingImagePath;
        if ($this->image) {
            if ($this->existingImagePath) {
                Storage::disk('public')->delete($this->existingImagePath);
            }
            $imagePath = $this->image->store('guides', 'public');
        }

        $guide = InteractiveGuide::updateOrCreate(
            ['id' => $this->editingId],
            [
                'municipality' => $this->municipality,
                'title'        => $this->title,
                'instruction'  => $this->instruction ?: null,
                'image_path'   => $imagePath,
            ]
        );

        // Sync hotspots: delete removed, upsert the rest with order = index.
        $keepIds = collect($this->hotspots)->pluck('id')->filter()->all();
        $guide->hotspots()->whereNotIn('id', $keepIds ?: [0])->delete();

        foreach (array_values($this->hotspots) as $i => $row) {
            GuideHotspot::updateOrCreate(
                ['id' => $row['id'] ?? null],
                [
                    'interactive_guide_id' => $guide->id,
                    'order'       => $i,
                    'label'       => $row['label'],
                    'description' => $row['description'] ?: null,
                    'pos_x'       => $row['pos_x'],
                    'pos_y'       => $row['pos_y'],
                    'attire_id'   => $row['attire_id'] ?: null,
                ]
            );
        }

        AuditLog::record($this->isEditing ? 'update' : 'create', 'guide', $guide->id, $guide->title);
        $this->dispatch('toast', message: "Guide \"{$guide->title}\" saved.", type: 'success');

        $this->showModal = false;
        $this->resetForm();
        unset($this->guides);
    }

    public function delete(int $id): void
    {
        $guide = InteractiveGuide::findOrFail($id);
        if ($guide->image_path) {
            Storage::disk('public')->delete($guide->image_path);
        }
        AuditLog::record('delete', 'guide', $guide->id, $guide->title);
        $guide->delete();
        $this->dispatch('toast', message: "Guide \"{$guide->title}\" deleted.", type: 'success');
        unset($this->guides);
    }

    private function resetForm(): void
    {
        $this->reset(['municipality', 'title', 'instruction', 'image', 'existingImagePath', 'editingId', 'hotspots']);
        $this->hotspots = [];
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.guides.manage-guides')
            ->layout('layouts.admin', ['title' => 'Manage Guides']);
    }
}
