<?php

namespace Tests\Feature\Auth;

use App\Models\User\PendingUser;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_new_users_are_stored_as_pending_users(): void
    {
        Mail::fake();
        
        $email = $this->faker->safeEmail();
        $handle = 'testuser' . random_int(100, 999); // Safe username without special characters
        
        // Set the correct emoji in the session
        session(['correct_emoji' => '👍']);
        
        $response = $this->post('/register', [
            'handle' => $handle,
            'email' => $email,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'age' => '1', // Checkbox value
            'selected_emoji' => '👍',
            'terms' => '1', // Terms acceptance
        ]);

        // Registration should redirect to verification notice
        $response->assertRedirect('/email/verify');
        
        // Should exist in pending_users table
        $this->assertDatabaseHas('pending_users', [
            'email' => $email,
            'handle' => $handle,
        ]);
        
        // Should NOT exist in users table yet
        $this->assertDatabaseMissing('users', [
            'email' => $email,
            'handle' => $handle,
        ]);
    }

    public function test_user_can_verify_email_through_verification_link(): void
    {
        // Create a pending user
        $pendingUser = PendingUser::create([
            'name' => 'Test User',
            'handle' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'),
            'verification_token' => Str::random(60),
        ]);

        // Visit the verification URL
        $response = $this->get("/verify-email/{$pendingUser->id}/{$pendingUser->verification_token}");

        // Should redirect to login with success message
        $response->assertRedirect('/login');
        $response->assertSessionHas('status');
        
        // Should now be in the users table
        $this->assertDatabaseHas('users', [
            'email' => $pendingUser->email,
            'handle' => $pendingUser->handle,
        ]);
        
        // Should be removed from pending_users
        $this->assertDatabaseMissing('pending_users', [
            'email' => $pendingUser->email,
        ]);
        
        // User should now be verified
        $user = User::where('email', $pendingUser->email)->first();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_invalid_verification_token_is_rejected(): void
    {
        // Create a pending user
        $pendingUser = PendingUser::create([
            'name' => 'Test User',
            'handle' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'),
            'verification_token' => Str::random(60),
        ]);

        // Visit with wrong token
        $response = $this->get("/verify-email/{$pendingUser->id}/wrong-token");

        // Should redirect to register with error
        $response->assertRedirect('/register');
        $response->assertSessionHas('error');
        
        // Should still be in pending_users
        $this->assertDatabaseHas('pending_users', [
            'email' => $pendingUser->email,
        ]);
        
        // Should NOT be in users table
        $this->assertDatabaseMissing('users', [
            'email' => $pendingUser->email,
        ]);
    }
}
