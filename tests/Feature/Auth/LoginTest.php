<?php

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }
    
    public function test_users_can_authenticate_with_valid_credentials(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'handle' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        
        $response = $this->withoutMiddleware()
            ->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        
        $this->assertAuthenticated();
        $response->assertRedirect('/home');
    }
    
    public function test_users_cannot_authenticate_with_invalid_credentials(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'handle' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        
        // Wrong password
        $response = $this->withoutMiddleware()
            ->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);
        
        $this->assertGuest();
        $response->assertSessionHasErrors();
        
        // Wrong email
        $response = $this->withoutMiddleware()
            ->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);
        
        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    public function test_users_can_authenticate_with_handle_instead_of_email(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'handle' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        
        $response = $this->withoutMiddleware()
            ->post('/login', [
            'email' => 'testuser', // Using handle in the email field
            'password' => 'password',
        ]);
        
        $this->assertAuthenticated();
        $response->assertRedirect('/home');
    }
}
