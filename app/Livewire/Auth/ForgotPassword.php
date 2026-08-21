<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';
    public bool $sent = false;

    protected array $rules = [
        'email' => 'required|email|max:255',
    ];

    public function submit(): void
    {
        $this->validate();

        // Always report success regardless of whether the email exists,
        // so this form can't be used to check which addresses are registered.
        Password::sendResetLink(['email' => $this->email]);

        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layout('layouts.guest');
    }
}
