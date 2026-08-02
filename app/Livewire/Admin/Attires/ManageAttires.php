<?php

namespace App\Livewire\Admin\Attires;

use App\Caching\HomepageCache;
use App\Enums\Municipality;
use App\Livewire\Concerns\ManagesCrudModal;
use App\Support\AdminForms\AttireFormRules;
use App\Models\Attire;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageAttires extends Component
{
    use ManagesCrudModal, WithFileUploads, WithPagination;

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
    public ?string $existingImagePath = null;

    // Filters
    public string $search               = '';
    public string $filterMunicipality   = '';
    public string $filterGender         = '';

    // Sorting
    public string $sortBy  = 'created_at';
    public string $sortDir = 'desc';

    protected function rules(): array
    {
        return AttireFormRules::rules();
    }

    protected function messages(): array
    {
        return AttireFormRules::messages();
    }

    public array $headers = [
        ['key' => 'image_path',    'label' => ''],
        ['key' => 'name_general',  'label' => 'General Name', 'sortable' => true],
        ['key' => 'name_dialect',  'label' => 'Dialect Name'],
        ['key' => 'municipality',  'label' => 'Municipality', 'sortable' => true],
        ['key' => 'gender',        'label' => 'Gender',       'sortable' => true],
    ];

    /** @return list<array{id: string, name: string}> */
    #[Computed]
    public function municipalityOptions(): array
    {
        return Municipality::options();
    }

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

    public function openEdit(int $id): void
    {
        $attire = Attire::findOrFail($id);
        $this->fill($attire->only([
            'name_general', 'name_dialect', 'municipality', 'gender',
            'description', 'material', 'cultural_significance', 'source_info',
        ]));
        $this->existingImagePath = $attire->image_path;
        $this->editingId = $id;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = AttireFormRules::normalize($this->validate());

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->replaceStoredFile($this->image, Attire::class, $this->editingId ?? 0, 'image_path', 'attires');
        }

        if ($this->isEditing) {
            $attire = Attire::findOrFail($this->editingId);
            $attire->update(array_merge(
                $validated,
                $imagePath ? ['image_path' => $imagePath] : []
            ));
            $this->logAndNotify('update', 'attire', $attire->id, $attire->name_general, 'updated');
        } else {
            $attire = Attire::create(array_merge($validated, ['image_path' => $imagePath]));
            $this->logAndNotify('create', 'attire', $attire->id, $attire->name_general, 'added');
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
        $attire->delete();
        $this->logAndNotify('delete', 'attire', $attire->id, $attire->name_general, 'deleted');
        unset($this->attires);
        HomepageCache::flush();
    }

    private function resetForm(): void
    {
        $this->reset([
            'name_general', 'name_dialect', 'municipality', 'gender', 'description',
            'material', 'cultural_significance', 'source_info', 'image', 'editingId',
            'existingImagePath',
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
