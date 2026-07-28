<?php

namespace App\Livewire\Concerns;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Storage;

/**
 * Shared scaffolding for admin CRUD-with-modal Livewire components
 * (ManageDances, ManageAttires). Covers only what is genuinely identical
 * between them: opening the create modal, replacing a stored file without
 * leaking the old one, and the audit-log-plus-toast pair on write/delete.
 *
 * Deliberately does not attempt to unify save()/resetForm()/openEdit(),
 * since those differ per-model (fields, extra file types, validation) in
 * ways that would need callback/config plumbing to force into a trait.
 */
trait ManagesCrudModal
{
    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    /**
     * Deletes the given model's existing file at $column (if any) before
     * storing the new upload, then returns the new stored path.
     */
    private function replaceStoredFile($newFile, string $model, int $id, string $column, string $directory): string
    {
        if ($this->isEditing) {
            $existing = $model::find($id);
            if ($existing?->{$column}) {
                Storage::disk('public')->delete($existing->{$column});
            }
        }

        return $newFile->store($directory, 'public');
    }

    private function logAndNotify(string $action, string $type, int $id, string $subject, string $verb): void
    {
        AuditLog::record($action, $type, $id, $subject);
        $this->dispatch('toast', message: "\"{$subject}\" {$verb}.", type: 'success');
    }
}
