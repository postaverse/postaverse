<?php

namespace Tests\Feature\Middleware;

use App\Models\User\User;
use App\Models\User\Banned;
use App\Models\BannedIp;
use App\Http\Middleware\CheckIfBanned;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class CheckIfBannedTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $user;
    protected $bannedUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a regular user
        $this->user = User::create([
            'name' => 'Regular User',
            'handle' => 'regularuser',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'admin_rank' => 0,
        ]);

        // Create a banned user
        $this->bannedUser = User::create([
            'name' => 'Banned User',
            'handle' => 'banneduser',
            'email' => 'banned@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'admin_rank' => 0,
        ]);

        // Create a ban for the banned user
        Banned::create([
            'user_id' => $this->bannedUser->id,
            'reason' => 'Test ban reason',
        ]);

        // Set up a test route with the middleware
        Route::middleware(['web', 'check-banned'])->get('/test-route', function () {
            return response('Success');
        });
    }

    public function test_non_banned_user_can_access_routes(): void
    {
        $this->actingAs($this->user);
        
        $response = $this->get('/test-route');
        
        $response->assertOk();
        $response->assertSeeText('Success');
    }

    public function test_banned_user_is_redirected_to_banned_page(): void
    {
        $this->actingAs($this->bannedUser);
        
        $response = $this->get('/test-route');
        
        $response->assertRedirect(route('banned'));
    }

    public function test_user_with_banned_ip_is_redirected(): void
    {
        // Create a ban with IP address
        $ban = Banned::create([
            'user_id' => $this->bannedUser->id,
            'reason' => 'Test ban reason',
        ]);

        BannedIp::create([
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.100',
        ]);

        // Act as regular user but with banned IP
        $this->actingAs($this->user);
        
        // Simulate request from banned IP
        $response = $this->call('GET', '/test-route', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.100',
        ]);
        
        $response->assertRedirect(route('banned'));
    }

    public function test_guest_user_with_banned_ip_is_redirected(): void
    {
        // Create a ban with IP address
        $ban = Banned::create([
            'user_id' => $this->bannedUser->id,
            'reason' => 'Test ban reason',
        ]);

        BannedIp::create([
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.100',
        ]);

        // Don't authenticate any user
        
        // Simulate request from banned IP
        $response = $this->call('GET', '/test-route', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.100',
        ]);
        
        $response->assertRedirect(route('banned'));
    }

    public function test_user_with_non_banned_ip_can_access_routes(): void
    {
        // Create a ban with different IP address
        $ban = Banned::create([
            'user_id' => $this->bannedUser->id,
            'reason' => 'Test ban reason',
        ]);

        BannedIp::create([
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.100',
        ]);

        // Act as regular user with different IP
        $this->actingAs($this->user);
        
        // Simulate request from non-banned IP
        $response = $this->call('GET', '/test-route', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.200',
        ]);
        
        $response->assertOk();
        $response->assertSeeText('Success');
    }

    public function test_middleware_handles_missing_ip_gracefully(): void
    {
        $this->actingAs($this->user);
        
        // Request without IP address
        $response = $this->call('GET', '/test-route', [], [], [], [
            // No REMOTE_ADDR set
        ]);
        
        $response->assertOk();
        $response->assertSeeText('Success');
    }

    public function test_middleware_handles_null_ip_gracefully(): void
    {
        // Create a ban with null IP (shouldn't match anything)
        $ban = Banned::create([
            'user_id' => $this->bannedUser->id,
            'reason' => 'Test ban reason',
        ]);

        // Don't create any BannedIp records

        $this->actingAs($this->user);
        
        $response = $this->get('/test-route');
        
        $response->assertOk();
        $response->assertSeeText('Success');
    }

    public function test_middleware_handles_multiple_banned_ips(): void
    {
        // Create a ban with multiple IP addresses
        $ban = Banned::create([
            'user_id' => $this->bannedUser->id,
            'reason' => 'Test ban reason',
        ]);

        BannedIp::create([
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.100',
        ]);

        BannedIp::create([
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.101',
        ]);

        $this->actingAs($this->user);
        
        // Test first banned IP
        $response = $this->call('GET', '/test-route', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.100',
        ]);
        $response->assertRedirect(route('banned'));

        // Test second banned IP
        $response = $this->call('GET', '/test-route', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.101',
        ]);
        $response->assertRedirect(route('banned'));

        // Test non-banned IP
        $response = $this->call('GET', '/test-route', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.102',
        ]);
        $response->assertOk();
    }

    public function test_ipv6_addresses_are_handled_correctly(): void
    {
        // Create a ban with IPv6 address
        $ban = Banned::create([
            'user_id' => $this->bannedUser->id,
            'reason' => 'Test ban reason',
        ]);

        BannedIp::create([
            'banned_id' => $ban->id,
            'ip_address' => '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
        ]);

        $this->actingAs($this->user);
        
        // Test banned IPv6
        $response = $this->call('GET', '/test-route', [], [], [], [
            'REMOTE_ADDR' => '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
        ]);
        $response->assertRedirect(route('banned'));

        // Test different IPv6
        $response = $this->call('GET', '/test-route', [], [], [], [
            'REMOTE_ADDR' => '2001:0db8:85a3:0000:0000:8a2e:0370:7335',
        ]);
        $response->assertOk();
    }

    public function test_both_user_ban_and_ip_ban_redirect_to_banned_page(): void
    {
        // Create a user ban and IP ban for the same user
        $ban = Banned::create([
            'user_id' => $this->bannedUser->id,
            'reason' => 'Test ban reason',
        ]);

        BannedIp::create([
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.100',
        ]);

        // Test with banned user and banned IP
        $this->actingAs($this->bannedUser);
        
        $response = $this->call('GET', '/test-route', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.100',
        ]);
        
        $response->assertRedirect(route('banned'));
    }
}
