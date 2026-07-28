<?php

namespace App\Livewire\Admin\Dances;

use App\Caching\HomepageCache;
use App\Enums\Municipality;
use App\Models\AuditLog;
use App\Models\Dance;
use App\Services\VideoOptimizer;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageDances extends Component
{
    use WithFileUploads, WithPagination;

    public bool $showModal  = false;
    public bool $isEditing  = false;
    public ?int $editingId  = null;

    // Form fields
    public string $name        = '';
    public string $category    = '';
    public ?string $municipality = '';
    public string $description = '';
    public ?string $region                = '';
    public ?string $origin                = '';
    public ?string $cultural_meaning      = '';
    public ?string $historical_background = '';
    public ?string $video_url  = '';
    public $image;
    public $video;
    public ?string $existingImagePath = null;
    public ?string $existingVideoPath = null;
    public bool $removeExistingVideo = false;

    // Filters
    public string $search         = '';
    public string $categoryFilter = '';

    // Sorting
    public string $sortBy  = 'created_at';
    public string $sortDir = 'desc';

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:pagaddut,hinggatut,dinuy-a',
            'municipality' => 'nullable|in:'.Municipality::validationList(),
            'description' => 'required|string|max:1000',
            'region'                => 'nullable|string|max:255',
            'origin'                => 'nullable|string|max:255',
            'cultural_meaning'      => 'nullable|string|max:2000',
            'historical_background' => 'nullable|string|max:2000',
            'video_url'   => [
                'nullable', 'url',
                'regex:/^https?:\/\/(www\.|m\.)?(youtube\.com\/(watch\?v=|shorts\/|embed\/)|youtu\.be\/).+/',
            ],
            'image'       => $this->isEditing
                ? 'nullable|image|mimes:jpeg,png,jpg|max:10240'
                : 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'video'       => 'nullable|mimes:mp4,mov,webm|max:51200',
        ];
    }

    public array $headers = [
        ['key' => 'image_path',  'label' => ''],
        ['key' => 'name',        'label' => 'Dance Name', 'sortable' => true],
        ['key' => 'category',    'label' => 'Category',   'sortable' => true],
        ['key' => 'video_url',   'label' => 'Video'],
        ['key' => 'created_at',  'label' => 'Added',      'sortable' => true],
    ];

    public array $danceTypes = [
        ['id' => 'pagaddut',  'name' => 'Pagaddut'],
        ['id' => 'hinggatut', 'name' => 'Hinggatut'],
        ['id' => 'dinuy-a',   'name' => 'Dinuy-a'],
    ];

    /** @return list<array{id: string, name: string}> */
    #[Computed]
    public function municipalityOptions(): array
    {
        return Municipality::options();
    }

    #[Computed]
    public function videoUrlEmbed(): ?string
    {
        return Dance::youtubeEmbedUrl($this->video_url);
    }

    #[Computed]
    public function dances()
    {
        return Dance::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->categoryFilter, fn ($q) => $q->where('category', $this->categoryFilter))
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
        unset($this->dances);
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $dance = Dance::findOrFail($id);
        $this->fill($dance->only([
            'name', 'category', 'municipality', 'description', 'region', 'origin',
            'cultural_meaning', 'historical_background', 'video_url',
        ]));
        $this->existingImagePath = $dance->image_path;
        $this->existingVideoPath = $dance->video_path;
        $this->removeExistingVideo = false;
        $this->editingId = $id;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function markExistingVideoForRemoval(): void
    {
        $this->removeExistingVideo = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        // Store null (not an empty string) for optional text fields left blank.
        foreach (['municipality', 'video_url', 'region', 'origin', 'cultural_meaning', 'historical_background'] as $optional) {
            $validated[$optional] = filled($validated[$optional] ?? null) ? $validated[$optional] : null;
        }

        $imagePath = null;
        if ($this->image) {
            // Delete old image before storing new one (prevent storage leak)
            if ($this->isEditing) {
                $existing = Dance::find($this->editingId);
                if ($existing?->image_path) {
                    Storage::disk('public')->delete($existing->image_path);
                }
            }
            $imagePath = $this->image->store('dances', 'public');
        }

        $videoPath = null;
        if ($this->video) {
            // Delete old video before storing new one (prevent storage leak)
            if ($this->isEditing) {
                $existing = Dance::find($this->editingId);
                if ($existing?->video_path) {
                    Storage::disk('public')->delete($existing->video_path);
                }
            }
            $videoPath = $this->video->store('dances-videos', 'public');
            VideoOptimizer::faststart('public', $videoPath);
        } elseif ($this->isEditing && $this->removeExistingVideo && $this->existingVideoPath) {
            Storage::disk('public')->delete($this->existingVideoPath);
        }

        if ($this->isEditing) {
            $dance = Dance::findOrFail($this->editingId);
            $dance->update(array_merge(
                $validated,
                $imagePath ? ['image_path' => $imagePath] : [],
                $videoPath ? ['video_path' => $videoPath] : [],
                (! $videoPath && $this->removeExistingVideo) ? ['video_path' => null] : []
            ));
            AuditLog::record('update', 'dance', $dance->id, $dance->name);
            $this->dispatch('toast', message: "Dance \"{$dance->name}\" updated.", type: 'success');
        } else {
            $dance = Dance::create(array_merge($validated, ['image_path' => $imagePath, 'video_path' => $videoPath]));
            AuditLog::record('create', 'dance', $dance->id, $dance->name);
            $this->dispatch('toast', message: "Dance \"{$dance->name}\" added.", type: 'success');
        }

        $this->showModal = false;
        $this->resetForm();
        unset($this->dances);
        HomepageCache::flush();
    }

    public function delete(int $id): void
    {
        $dance = Dance::findOrFail($id);
        if ($dance->image_path) {
            Storage::disk('public')->delete($dance->image_path);
        }
        if ($dance->video_path) {
            Storage::disk('public')->delete($dance->video_path);
        }
        AuditLog::record('delete', 'dance', $dance->id, $dance->name);
        $dance->delete();
        $this->dispatch('toast', message: "Dance \"{$dance->name}\" deleted.", type: 'success');
        unset($this->dances);
        HomepageCache::flush();
    }

    private function resetForm(): void
    {
        $this->reset([
            'name', 'category', 'municipality', 'description', 'region', 'origin',
            'cultural_meaning', 'historical_background', 'video_url', 'image', 'video', 'editingId',
            'existingImagePath', 'existingVideoPath', 'removeExistingVideo',
        ]);
        $this->resetValidation();
    }

    public function updatingSearch(): void        { $this->resetPage(); }
    public function updatingCategoryFilter(): void { $this->resetPage(); }

    public function render()
    {
        return view('livewire.admin.dances.manage-dances')
            ->layout('layouts.admin', ['title' => 'Manage Dances']);
    }
}
