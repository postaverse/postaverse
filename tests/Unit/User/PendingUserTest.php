<?php

namespace Tests\Unit\User;

use App\Models\User\PendingUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Http\Controllers\EmailVerificationController;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PendingUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_user_can_be_created(): void
    {
        $pendingUser = PendingUser::create([
            'name' => 'Pending User',
            'handle' => 'pendinguser',
            'email' => 'pending@example.com',
            'password' => Hash::make('password'),
            'verification_token' => Str::random(60),
        ]);
        
        $this->assertDatabaseHas('pending_users', [
            'id' => $pendingUser->id,
            'email' => 'pending@example.com',
        ]);
    }
    
    public function test_verification_controller_creates_user_from_pending_user(): void
    {
        // Create a pending user
        $pendingUser = PendingUser::create([
            'name' => 'Pending User',
            'handle' => 'pendinguser',
            'email' => 'pending@example.com',
            'password' => Hash::make('password'),
            'verification_token' => 'valid-token',
        ]);
        
        // Mock the verification request
        $request = Request::create('/verify-email/' . $pendingUser->id . '/valid-token', 'GET');
        
        // Call the controller method
        $controller = new EmailVerificationController();
        $response = $controller->verify($pendingUser->id, 'valid-token');
        
        // Assert that a user was created
        $this->assertDatabaseHas('users', [
            'email' => 'pending@example.com',
            'handle' => 'pendinguser',
        ]);
        
        // Assert that the pending user was deleted
        $this->assertDatabaseMissing('pending_users', [
            'id' => $pendingUser->id,
        ]);
        
        // Assert that the created user has a verified email
        $user = User::where('email', 'pending@example.com')->first();
        $this->assertNotNull($user->email_verified_at);
    }
    
    public function test_invalid_token_does_not_create_user(): void
    {
        // Create a pending user
        $pendingUser = PendingUser::create([
            'name' => 'Pending User',
            'handle' => 'pendinguser',
            'email' => 'pending@example.com',
            'password' => Hash::make('password'),
            'verification_token' => 'valid-token',
        ]);
        
        // Mock the verification request with invalid token
        $request = Request::create('/verify-email/' . $pendingUser->id . '/invalid-token', 'GET');
        
        // Call the controller method
        $controller = new EmailVerificationController();
        $response = $controller->verify($pendingUser->id, 'invalid-token');
        
        // Assert that no user was created
        $this->assertDatabaseMissing('users', [
            'email' => 'pending@example.com',
        ]);
        
        // Assert that the pending user still exists
        $this->assertDatabaseHas('pending_users', [
            'id' => $pendingUser->id,
        ]);
    }
}
