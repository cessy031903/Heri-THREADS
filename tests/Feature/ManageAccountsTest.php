<?php

namespace Tests\Feature;

use App\Livewire\Admin\Settings\ManageAccounts;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ManageAccountsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(['email' => 'admin@test.com']));
    }

    public function test_admin_can_create_a_new_account(): void
    {
        Livewire::test(ManageAccounts::class)
            ->set('name', 'New Admin')
            ->set('email', 'newadmin@test.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('save');

        $this->assertDatabaseHas('users', [
            'name'  => 'New Admin',
            'email' => 'newadmin@test.com',
            'role'  => 'admin',
        ]);
    }

    public function test_cannot_create_account_with_duplicate_email(): void
    {
        Livewire::test(ManageAccounts::class)
            ->set('name', 'Dup')
            ->set('email', 'admin@test.com') // already used by the acting user
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('save')
            ->assertHasErrors('email');
    }

    public function test_admin_can_update_account_without_changing_password(): void
    {
        $user = User::factory()->create(['name' => 'Old Name', 'email' => 'old@test.com']);
        $originalPassword = $user->password;

        Livewire::test(ManageAccounts::class)
            ->call('openEdit', $user->id)
            ->set('name', 'Updated Name')
            ->call('save')
            ->assertDispatched('toast', message: 'Updated Successfully');

        $user->refresh();
        $this->assertSame('Updated Name', $user->name);
        $this->assertSame($originalPassword, $user->password); // unchanged
    }

    public function test_admin_can_update_account_password(): void
    {
        $user = User::factory()->create();

        Livewire::test(ManageAccounts::class)
            ->call('openEdit', $user->id)
            ->set('password', 'brand-new-password')
            ->set('password_confirmation', 'brand-new-password')
            ->call('save');

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('brand-new-password', $user->fresh()->password));
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $selfId = auth()->id();

        Livewire::test(ManageAccounts::class)
            ->call('delete', $selfId)
            ->assertDispatched('toast', message: 'You cannot delete your own account while signed in.');

        $this->assertNotNull(User::find($selfId));
    }

    public function test_admin_can_delete_another_account(): void
    {
        $other = User::factory()->create();

        Livewire::test(ManageAccounts::class)->call('delete', $other->id);

        $this->assertNull(User::find($other->id));
    }
}
