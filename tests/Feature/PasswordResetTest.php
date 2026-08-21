<?php

namespace Tests\Feature;

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_form_sends_reset_link_notification(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->call('submit')
            ->assertSet('sent', true);

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_forgot_password_form_reports_sent_even_for_unknown_email(): void
    {
        Notification::fake();

        Livewire::test(ForgotPassword::class)
            ->set('email', 'nobody@example.com')
            ->call('submit')
            ->assertSet('sent', true);

        Notification::assertNothingSent();
    }

    public function test_reset_password_with_valid_token_updates_password(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        Livewire::withQueryParams(['email' => $user->email])
            ->test(ResetPassword::class, ['token' => $token])
            ->set('password', 'new-secret-password')
            ->set('password_confirmation', 'new-secret-password')
            ->call('submit')
            ->assertRedirect(route('login'));

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-secret-password', $user->fresh()->password));
    }

    public function test_reset_password_with_invalid_token_fails(): void
    {
        $user = User::factory()->create();

        Livewire::withQueryParams(['email' => $user->email])
            ->test(ResetPassword::class, ['token' => 'not-a-real-token'])
            ->set('password', 'new-secret-password')
            ->set('password_confirmation', 'new-secret-password')
            ->call('submit')
            ->assertHasErrors('email');
    }
}
