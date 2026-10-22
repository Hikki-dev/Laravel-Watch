<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'user',
                         'access_token',
                         'token_type'
                     ]
                 ]);
        
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'access_token',
                     ]
                 ]);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Successfully logged out']);
    }

    private function fakeGoogle(array $user): void
    {
        config(['services.firebase.web_api_key' => 'test-key']);
        Http::fake([
            'identitytoolkit.googleapis.com/*' => Http::response($user ? ['users' => [$user]] : ['error' => 'bad'], $user ? 200 : 400),
        ]);
    }

    public function test_google_login_requires_an_id_token()
    {
        $this->postJson('/api/auth/google', ['email' => 'victim@example.com'])->assertStatus(422);
    }

    public function test_google_login_rejects_an_invalid_token()
    {
        $this->fakeGoogle([]);

        $this->postJson('/api/auth/google', ['id_token' => 'forged'])->assertStatus(401);
        $this->assertDatabaseMissing('users', ['email' => 'victim@example.com']);
    }

    public function test_google_login_rejects_an_unverified_email()
    {
        $this->fakeGoogle(['email' => 'a@example.com', 'emailVerified' => false]);

        $this->postJson('/api/auth/google', ['id_token' => 't'])->assertStatus(401);
    }

    public function test_google_login_creates_a_customer_from_the_verified_token()
    {
        $this->fakeGoogle([
            'email' => 'new@example.com',
            'emailVerified' => true,
            'providerUserInfo' => [['providerId' => 'google.com', 'rawId' => 'g-123']],
        ]);

        $this->postJson('/api/auth/google', ['id_token' => 't', 'email' => 'ignored@example.com'])
            ->assertOk()
            ->assertJsonPath('data.user.email', 'new@example.com');

        $this->assertDatabaseHas('users', ['email' => 'new@example.com', 'role' => 'customer', 'google_id' => 'g-123']);
        $this->assertDatabaseMissing('users', ['email' => 'ignored@example.com']);
    }

    public function test_google_login_does_not_change_role_or_password_of_existing_user()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret-pass'),
            'role' => 'admin',
        ]);
        $this->fakeGoogle(['email' => 'admin@example.com', 'emailVerified' => true]);

        $this->postJson('/api/auth/google', ['id_token' => 't'])->assertOk();

        $admin->refresh();
        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('secret-pass', $admin->password));
    }
}
