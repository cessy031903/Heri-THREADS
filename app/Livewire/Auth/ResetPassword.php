<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;

class ResetPassword extends Component
{
    public string $token = '';

    #[Url]
    public string $email = '';

    public string $password = '';
    public string $password_confirmation = '';

    protected array $rules = [
        'token'    => 'required',
        'email'    => 'required|email',
        'password' => 'required|min:8|confirmed',
    ];

    /** Bound from the {token} route segment, e.g. /reset-password/{token}. */
    public function mount(string $token): void
    {
        $this->token = $token;
    }

    public function submit(): void
    {
        $this->validate();

        $status = Password::reset(
            [
                'email'                 => $this->email,
                'password'              => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token'                 => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password'       => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', 'Your password has been reset. You can now sign in.');
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.reset-password')
            ->layout('layouts.guest');
    }
}
