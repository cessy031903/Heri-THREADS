<?php

namespace App\Livewire\Admin\Attires;

use App\Caching\HomepageCache;
use App\Models\AuditLog;
use App\Models\Attire;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageAttires extends Component
{
    use WithFileUploads, WithPagination;

    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    // Form fields
    public string $name_general  = '';
    public string $name_dialect  = '';
    public string $municipality  = '';
    public string $gender        = '';
    public string $description   = '';
    public ?string $material               = '';
    public ?string $cultural_significance  = '';
    public string $source_info   = '';
    public $image;

    // Filters
    public string $search               = '';
    public string $filterMunicipality   = '';
    public string $filterGender         = '';

    // Sorting
    public string $sortBy  = 'created_at';
    public string $sortDir = 'desc';

    protected function rules(): array
    {
        return [
            'name_general' => 'required|string|max:255',
            'name_dialect' => 'required|string|max:255',
            'municipality' => 'required|in:Alfonso Lista,Aguinaldo,Asipulo,Banaue,Hingyon,Hungduan,Kiangan,Lagawe,Lamut,Mayoyao,Tinoc',
            'gender'       => 'required|in:women,men',
            'description'  => 'required|string|max:1500',
            'material'              => 'nullable|string|max:255',
            'cultural_significance' => 'nullable|string|max:2000',
            'source_info'  => 'nullable|string|max:500',
            'image'        => $this->isEditing
                ? 'nullable|image|mimes:jpeg,png,jpg|max:10240'
                : 'required|image|mimes:jpeg,png,jpg|max:10240',
        ];
    }

    public array $headers = [
        ['key' => 'image_path',    'label' => ''],
        ['key' => 'name_general',  'label' => 'General Name', 'sortable' => true],
        ['key' => 'name_dialect',  'label' => 'Dialect Name'],
        ['key' => 'municipality',  'label' => 'Municipality', 'sortable' => true],
        ['key' => 'gender',        'label' => 'Gender',       'sortable' => true],
    ];

    public array $municipalities = [
        ['id' => 'Alfonso Lista', 'name' => 'Alfonso Lista'],
        ['id' => 'Aguinaldo',     'name' => 'Aguinaldo'],
        ['id' => 'Asipulo',       'name' => 'Asipulo'],
        ['id' => 'Banaue',        'name' => 'Banaue'],
        ['id' => 'Hingyon',       'name' => 'Hingyon'],
        ['id' => 'Hungduan',      'name' => 'Hungduan'],
        ['id' => 'Kiangan',       'name' => 'Kiangan'],
        ['id' => 'Lagawe',        'name' => 'Lagawe'],
        ['id' => 'Lamut',         'name' => 'Lamut'],
        ['id' => 'Mayoyao',       'name' => 'Mayoyao'],
        ['id' => 'Tinoc',         'name' => 'Tinoc'],
    ];

    public array $genders = [
        ['id' => 'women', 'name' => "Women's"],
        ['id' => 'men',   'name' => "Men's"],
    ];

    #[Computed]
    public function attires()
    {
        return Attire::query()
            ->when($this->search, fn ($q) => $q->where('name_general', 'like', "%{$this->search}%"))
            ->when($this->filterMunicipality, fn ($q) => $q->where('municipality', $this->filterMunicipality))
            ->when($this->filterGender, fn ($q) => $q->where('gender', $this->filterGender))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(10);
    }

    public function sort(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $field;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
        unset($this->attires);
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $attire = Attire::findOrFail($id);
        $this->fill($attire->only([
            'name_general', 'name_dialect', 'municipality', 'gender',
            'description', 'material', 'cultural_significance', 'source_info',
        ]));
        $this->editingId = $id;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        foreach (['material', 'cultural_significance', 'source_info'] as $optional) {
            $validated[$optional] = filled($validated[$optional] ?? null) ? $validated[$optional] : null;
        }

        $imagePath = null;
        if ($this->image) {
            // Delete old image before storing new one (prevent storage leak)
            if ($this->isEditing) {
                $existing = Attire::find($this->editingId);
                if ($existing?->image_path) {
                    Storage::disk('public')->delete($existing->image_path);
                }
            }
            $imagePath = $this->image->store('attires', 'public');
        }

        if ($this->isEditing) {
            $attire = Attire::findOrFail($this->editingId);
            $attire->update(array_merge(
                $validated,
                $imagePath ? ['image_path' => $imagePath] : []
            ));
            AuditLog::record('update', 'attire', $attire->id, $attire->name_general);
            $this->dispatch('toast', message: "Attire \"{$attire->name_general}\" updated.", type: 'success');
        } else {
            $attire = Attire::create(array_merge($validated, ['image_path' => $imagePath]));
            AuditLog::record('create', 'attire', $attire->id, $attire->name_general);
            $this->dispatch('toast', message: "Attire \"{$attire->name_general}\" added.", type: 'success');
        }

        $this->showModal = false;
        $this->resetForm();
        unset($this->attires);
        HomepageCache::flush();
    }

    public function delete(int $id): void
    {
        $attire = Attire::findOrFail($id);
        if ($attire->image_path) {
            Storage::disk('public')->delete($attire->image_path);
        }
        AuditLog::record('delete', 'attire', $attire->id, $attire->name_general);
        $attire->delete();
        $this->dispatch('toast', message: "Attire \"{$attire->name_general}\" deleted.", type: 'success');
        unset($this->attires);
        HomepageCache::flush();
    }

    private function resetForm(): void
    {
        $this->reset([
            'name_general', 'name_dialect', 'municipality', 'gender', 'description',
            'material', 'cultural_significance', 'source_info', 'image', 'editingId',
        ]);
        $this->resetValidation();
    }

    public function updatingSearch(): void              { $this->resetPage(); }
    public function updatingFilterMunicipality(): void  { $this->resetPage(); }
    public function updatingFilterGender(): void        { $this->resetPage(); }

    public function render()
    {
        return view('livewire.admin.attires.manage-attires')
            ->layout('layouts.admin', ['title' => 'Manage Attires']);
    }
}
