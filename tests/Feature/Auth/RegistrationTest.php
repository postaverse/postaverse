<?php

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User\PendingUser;
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_registration_page_is_accessible(): void
    {
        $response = $this->get('/register');
        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
    }

    public function test_registration_requires_valid_inputs(): void
    {
        Mail::fake();
        
        $response = $this->post('/register', [
            'handle' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);
        
        $response->assertSessionHasErrors(['handle', 'email', 'password']);
    }
    
    public function test_email_must_be_unique_in_pending_users_and_users(): void
    {
        Mail::fake();
        
        // Create an existing pending user
        PendingUser::create([
            'name' => 'Existing User',
            'handle' => 'uniquehandle123',
            'email' => 'existing@example.com',
            'password' => 'password',
            'verification_token' => 'token',
        ]);
        
        // Create an existing verified user
        User::create([
            'name' => 'Verified User',
            'handle' => 'verifieduser123',
            'email' => 'verified@example.com',
            'password' => 'password',
        ]);
        
        // Set the correct emoji
        session(['correct_emoji' => '👍']);
        
        // Try to register with email already in pending_users
        $response = $this->post('/register', [
            'handle' => 'newuser',
            'email' => 'existing@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'age' => '1',
            'selected_emoji' => '👍',
        ]);
        $response->assertSessionHasErrors('email');
        
        // Try to register with email already in users
        $response = $this->post('/register', [
            'handle' => 'newuser',
            'email' => 'verified@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'age' => '1',
            'selected_emoji' => '👍',
        ]);
        $response->assertSessionHasErrors('email');
    }
    
    public function test_handle_must_be_unique_in_pending_users_and_users(): void
    {
        Mail::fake();
        
        // Create an existing pending user
        PendingUser::create([
            'name' => 'Existing User',
            'handle' => 'existinghandle',
            'email' => 'pending@example.com',
            'password' => 'password',
            'verification_token' => 'token',
        ]);
        
        // Create an existing verified user
        User::create([
            'name' => 'Verified User',
            'handle' => 'verifiedhandle',
            'email' => 'verified@example.com',
            'password' => 'password',
        ]);
        
        // Set the correct emoji
        session(['correct_emoji' => '👍']);
        
        // Try to register with handle already in pending_users
        $response = $this->post('/register', [
            'handle' => 'existinghandle',
            'email' => 'new@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'age' => '1',
            'selected_emoji' => '👍',
        ]);
        $response->assertSessionHasErrors('handle');
        
        // Try to register with handle already in users
        $response = $this->post('/register', [
            'handle' => 'verifiedhandle',
            'email' => 'new2@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'age' => '1',
            'selected_emoji' => '👍',
        ]);
        $response->assertSessionHasErrors('handle');
    }
}
