<?php

namespace Tests\Feature\Api;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_via_api()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'handle' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'handle',
                        'bio',
                        'profile_photo_url',
                        'admin_rank',
                        'verified',
                        'site_verified',
                        'followers_count',
                        'following_count',
                        'posts_count',
                        'created_at',
                        'updated_at'
                    ],
                    'token',
                    'message'
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'handle' => 'testuser'
        ]);
    }

    public function test_user_can_login_via_api()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'handle' => 'testuser'
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'user',
                    'token',
                    'message'
                ]);
    }

    public function test_authenticated_user_can_get_profile()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'handle' => 'testuser'
        ]);
        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/me');

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $user->id,
                    'email' => $user->email
                ]);
    }

    public function test_user_can_logout_via_api()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'handle' => 'testuser'
        ]);
        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Logged out successfully'
                ]);
    }
}
