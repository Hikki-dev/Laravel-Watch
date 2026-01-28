<?php

namespace Tests\Feature;

use App\Livewire\Admin\UserManagement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_render_page()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin, 'web');

        $response = $this->get(route('admin.users.index'));
        
        if ($response->status() === 302) {
            dump($response->headers->get('Location'));
        }

        $response->assertStatus(200);
    }

    public function test_can_create_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'web');

        Livewire::test(UserManagement::class)
            ->call('create')
            ->set('state.name', 'Test User')
            ->set('state.email', 'test@example.com')
            ->set('state.role', 'customer')
            ->set('state.password', 'password')
            ->set('state.is_active', true)
            ->call('store')
            ->assertDispatched('saved');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'is_active' => true,
        ]);
    }

    public function test_can_update_user_status_and_role()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'customer', 'is_active' => true]);

        $this->actingAs($admin, 'web');

        Livewire::test(UserManagement::class)
            ->call('edit', $user)
            ->set('state.role', 'seller')
            ->set('state.is_active', false)
            ->call('update')
            ->assertDispatched('saved');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'seller',
            'is_active' => false,
        ]);
    }

    public function test_can_update_password()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $user = User::factory()->create(['password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']); // password

        $this->actingAs($admin, 'web');

        Livewire::test(UserManagement::class)
            ->call('edit', $user)
            ->set('state.role', 'customer')
            ->set('state.password', 'newpassword')
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('saved');

        $user->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword', $user->password));
    }

    public function test_inactive_user_is_logged_out()
    {
        // Test Middleware
        $user = User::factory()->create(['is_active' => false]);

        $this->actingAs($user, 'web');

        // Access a protected route, e.g., dashboard
        $response = $this->get('/dashboard');

        // Should redirect to login
        $response->assertRedirect(route('login'));
        $this->assertGuest('web'); // User should be logged out
    }
}
