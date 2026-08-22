<?php

namespace App\Livewire\Admin\Settings;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ManageAccounts extends Component
{
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    // Form fields
    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($this->editingId),
            ],
            'password' => $this->isEditing
                ? 'nullable|string|min:8|confirmed'
                : 'required|string|min:8|confirmed',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required'  => 'Enter the account name.',
            'email.required' => 'Enter an email address.',
            'email.unique'   => 'An account with this email already exists.',
            'password.min'   => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    #[Computed]
    public function accounts()
    {
        return User::orderBy('name')->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->fill($user->only(['name', 'email']));
        $this->editingId = $id;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $user = User::findOrFail($this->editingId);
            $user->update([
                'name'  => $validated['name'],
                'email' => $validated['email'],
                ...(filled($validated['password']) ? ['password' => Hash::make($validated['password'])] : []),
            ]);
            AuditLog::record('update', 'user_account', $user->id, $user->name);
            $this->dispatch('toast', message: 'Updated Successfully', type: 'success');
        } else {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'admin',
            ]);
            AuditLog::record('create', 'user_account', $user->id, $user->name);
            $this->dispatch('toast', message: "Account \"{$user->name}\" created.", type: 'success');
        }

        $this->showModal = false;
        $this->resetForm();
        unset($this->accounts);
    }

    public function delete(int $id): void
    {
        if ($id === auth()->id()) {
            $this->dispatch('toast', message: 'You cannot delete your own account while signed in.', type: 'error');
            return;
        }

        $user = User::findOrFail($id);
        AuditLog::record('delete', 'user_account', $user->id, $user->name);
        $user->delete();
        $this->dispatch('toast', message: "Account \"{$user->name}\" deleted.", type: 'success');
        unset($this->accounts);
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'editingId']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.settings.manage-accounts')
            ->layout('layouts.admin', ['title' => 'Manage Accounts']);
    }
}
