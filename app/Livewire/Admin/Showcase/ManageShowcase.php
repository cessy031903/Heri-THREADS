<?php

namespace App\Livewire\Admin\Showcase;

use App\Caching\HomepageCache;
use App\Livewire\Concerns\ManagesCrudModal;
use App\Models\AuditLog;
use App\Models\ShowcasePhoto;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageShowcase extends Component
{
    use ManagesCrudModal, WithFileUploads;

    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    // Form fields
    public string $label     = '';
    public ?string $sub_label = '';
    public ?string $link_url  = '';
    public $image;
    public ?string $existingImagePath = null;

    protected function rules(): array
    {
        return [
            'label'     => 'required|string|max:255',
            'sub_label' => 'nullable|string|max:255',
            'link_url'  => 'nullable|string|max:2048',
            'image'     => $this->isEditing
                ? 'nullable|image|mimes:jpeg,png,jpg|max:10240'
                : 'required|image|mimes:jpeg,png,jpg|max:10240',
        ];
    }

    #[Computed]
    public function photos()
    {
        return ShowcasePhoto::query()->orderBy('order')->get();
    }

    public function openEdit(int $id): void
    {
        $photo = ShowcasePhoto::findOrFail($id);
        $this->fill($photo->only(['label', 'sub_label', 'link_url']));
        $this->existingImagePath = $photo->image_path;
        $this->editingId = $id;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        foreach (['sub_label', 'link_url'] as $optional) {
            $validated[$optional] = filled($validated[$optional] ?? null) ? $validated[$optional] : null;
        }

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->replaceStoredFile($this->image, ShowcasePhoto::class, $this->editingId ?? 0, 'image_path', 'showcase');
        }

        if ($this->isEditing) {
            $photo = ShowcasePhoto::findOrFail($this->editingId);
            $photo->update(array_merge(
                $validated,
                $imagePath ? ['image_path' => $imagePath] : []
            ));
            $this->logAndNotify('update', 'showcase_photo', $photo->id, $photo->label, 'updated');
        } else {
            $maxOrder  = ShowcasePhoto::max('order');
            $nextOrder = $maxOrder === null ? 0 : $maxOrder + 1;
            $photo = ShowcasePhoto::create(array_merge($validated, [
                'image_path' => $imagePath,
                'order'      => $nextOrder,
            ]));
            $this->logAndNotify('create', 'showcase_photo', $photo->id, $photo->label, 'added');
        }

        $this->showModal = false;
        $this->resetForm();
        unset($this->photos);
        HomepageCache::flush();
    }

    public function delete(int $id): void
    {
        $photo = ShowcasePhoto::findOrFail($id);
        if ($photo->image_path) {
            Storage::disk('public')->delete($photo->image_path);
        }
        $photo->delete();
        $this->logAndNotify('delete', 'showcase_photo', $photo->id, $photo->label, 'deleted');
        unset($this->photos);
        HomepageCache::flush();
    }

    public function moveUp(int $id): void
    {
        $this->swapOrder($id, -1);
    }

    public function moveDown(int $id): void
    {
        $this->swapOrder($id, 1);
    }

    private function swapOrder(int $id, int $direction): void
    {
        $photos = ShowcasePhoto::orderBy('order')->get();
        $index  = $photos->search(fn ($p) => $p->id === $id);
        $swapWith = $photos->get($index + $direction);

        if ($index === false || ! $swapWith) {
            return;
        }

        $current = $photos->get($index);
        [$currentOrder, $swapOrder] = [$current->order, $swapWith->order];

        $current->update(['order' => $swapOrder]);
        $swapWith->update(['order' => $currentOrder]);

        unset($this->photos);
        HomepageCache::flush();
    }

    private function resetForm(): void
    {
        $this->reset(['label', 'sub_label', 'link_url', 'image', 'editingId', 'existingImagePath']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.showcase.manage-showcase')
            ->layout('layouts.admin', ['title' => 'Manage Home Showcase']);
    }
}
