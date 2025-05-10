<?php

namespace Tests\Feature;

use App\Models\User\User;
use App\Models\User\PendingUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class VerificationSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_system_is_working(): void
    {
        // Fake the mail to avoid sending actual emails
        Mail::fake();
        
        // This is a meta-test that ensures all the components of the
        // email verification system are working together properly.
        
        // 1. Register a new user
        $email = 'newuser' . random_int(100, 999) . '@example.com';
        $handle = 'newuser' . random_int(100, 999); // Safe username without special characters
        
        // Set correct emoji
        session(['correct_emoji' => '👍']);
        
        $response = $this->post('/register', [
            'handle' => $handle,
            'email' => $email,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'age' => '1',
            'selected_emoji' => '👍',
            'terms' => '1', // Terms acceptance
        ]);
        
        // Should redirect to the verification notice page
        $response->assertRedirect('/email/verify');
        
        // 2. Verify the user should be in pending_users but not users
        $this->assertDatabaseHas('pending_users', ['email' => $email]);
        $this->assertDatabaseMissing('users', ['email' => $email]);
        
        // 3. Get the verification token
        $pendingUser = \App\Models\User\PendingUser::where('email', $email)->first();
        $this->assertNotNull($pendingUser);
        
        // 4. Simulate clicking the verification link
        $response = $this->get("/verify-email/{$pendingUser->id}/{$pendingUser->verification_token}");
        $response->assertRedirect('/login');
        
        // 5. Verify the user is now in users and removed from pending_users
        $this->assertDatabaseHas('users', ['email' => $email]);
        $this->assertDatabaseMissing('pending_users', ['email' => $email]);
        
        // Verify the user's email is marked as verified
        $user = User::where('email', $email)->first();
        $this->assertNotNull($user->email_verified_at);
        
        // 6. Verify the user can now log in
        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'Password123!',
        ]);
        
        $this->assertAuthenticated();
    }
}
