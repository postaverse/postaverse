<?php

namespace Tests\Feature\Services;

use App\Models\User\User;
use App\Models\User\Banned;
use App\Models\BannedIp;
use App\Models\Admin\AdminLogs;
use App\Services\IpBanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class IpBanServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $admin;
    protected $regularUser;
    protected $ipBanService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->ipBanService = new IpBanService();
        
        // Create an admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'handle' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'admin_rank' => 4, // Has permission to ban users
        ]);

        // Create a regular user
        $this->regularUser = User::create([
            'name' => 'Regular User',
            'handle' => 'regularuser',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'admin_rank' => 0,
        ]);
    }

    public function test_admin_can_ban_user_with_ip_addresses(): void
    {
        // Create mock session data for the user
        DB::table('sessions')->insert([
            'id' => 'test_session_1',
            'user_id' => $this->regularUser->id,
            'ip_address' => '192.168.1.100',
            'payload' => 'test_payload',
            'last_activity' => time(),
        ]);

        DB::table('sessions')->insert([
            'id' => 'test_session_2',
            'user_id' => $this->regularUser->id,
            'ip_address' => '192.168.1.101',
            'payload' => 'test_payload',
            'last_activity' => time(),
        ]);

        // Ban the user
        $result = $this->ipBanService->banUser($this->regularUser->id, 'Test ban reason', $this->admin);

        // Assert the operation was successful
        $this->assertTrue($result['success']);
        $this->assertEquals(2, $result['ip_count']);

        // Assert the ban was created
        $this->assertDatabaseHas('banned', [
            'user_id' => $this->regularUser->id,
            'reason' => 'Test ban reason',
        ]);

        // Get the ban record
        $ban = Banned::where('user_id', $this->regularUser->id)->first();
        
        // Assert IP addresses were banned
        $this->assertDatabaseHas('banned_ips', [
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.100',
        ]);

        $this->assertDatabaseHas('banned_ips', [
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.101',
        ]);

        // Assert the correct number of IP bans were created
        $this->assertEquals(2, $ban->bannedIps()->count());

        // Assert admin log was created
        $this->assertDatabaseHas('admin_logs', [
            'admin_id' => $this->admin->id,
        ]);
    }

    public function test_admin_can_ban_user_without_ip_addresses(): void
    {
        // Ban the user (no session data = no IPs)
        $result = $this->ipBanService->banUser($this->regularUser->id, 'Test ban reason', $this->admin);

        // Assert the operation was successful
        $this->assertTrue($result['success']);
        $this->assertEquals(0, $result['ip_count']);

        // Assert the ban was created
        $this->assertDatabaseHas('banned', [
            'user_id' => $this->regularUser->id,
            'reason' => 'Test ban reason',
        ]);

        // Get the ban record
        $ban = Banned::where('user_id', $this->regularUser->id)->first();
        
        // Assert no IP addresses were banned
        $this->assertEquals(0, $ban->bannedIps()->count());
    }

    public function test_admin_can_unban_user_and_remove_ip_bans(): void
    {
        // Create a ban with IP addresses
        $ban = Banned::create([
            'user_id' => $this->regularUser->id,
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

        // Unban the user
        $result = $this->ipBanService->unbanUser($this->regularUser->id, $this->admin);

        // Assert the operation was successful
        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['ban_count']);
        $this->assertEquals(2, $result['ip_count']);

        // Assert the ban was removed
        $this->assertDatabaseMissing('banned', [
            'user_id' => $this->regularUser->id,
        ]);

        // Assert IP bans were removed (cascade delete)
        $this->assertDatabaseMissing('banned_ips', [
            'banned_id' => $ban->id,
        ]);
    }

    public function test_regular_user_cannot_ban_other_users(): void
    {
        // Create another user to ban
        $anotherUser = User::create([
            'name' => 'Another User',
            'handle' => 'anotheruser',
            'email' => 'another@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'admin_rank' => 0,
        ]);

        // Try to ban as regular user
        $result = $this->ipBanService->banUser($anotherUser->id, 'Test ban reason', $this->regularUser);

        // Assert the operation failed
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Insufficient permissions', $result['message']);

        // Assert no ban was created
        $this->assertDatabaseMissing('banned', [
            'user_id' => $anotherUser->id,
        ]);
    }

    public function test_admin_cannot_ban_higher_rank_admin(): void
    {
        // Create a higher rank admin
        $higherAdmin = User::create([
            'name' => 'Higher Admin',
            'handle' => 'higheradmin',
            'email' => 'higheradmin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'admin_rank' => 4, // Higher than our test admin (3)
        ]);

        // Try to ban higher rank admin
        $result = $this->ipBanService->banUser($higherAdmin->id, 'Test ban reason', $this->admin);

        // Debug if the ban succeeded when it shouldn't
        if ($result['success']) {
            dd('Ban succeeded when it should have failed:', 
               'Higher admin rank:', $higherAdmin->admin_rank, 
               'Current admin rank:', $this->admin->admin_rank,
               'Result:', $result);
        }

        // Assert the operation failed
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('equal or higher rank', $result['message']);

        // Assert no ban was created
        $this->assertDatabaseMissing('banned', [
            'user_id' => $higherAdmin->id,
        ]);
    }

    public function test_duplicate_ip_addresses_are_handled_correctly(): void
    {
        // Create multiple sessions with the same IP
        DB::table('sessions')->insert([
            'id' => 'test_session_1',
            'user_id' => $this->regularUser->id,
            'ip_address' => '192.168.1.100',
            'payload' => 'test_payload_1',
            'last_activity' => time(),
        ]);

        DB::table('sessions')->insert([
            'id' => 'test_session_2',
            'user_id' => $this->regularUser->id,
            'ip_address' => '192.168.1.100', // Same IP
            'payload' => 'test_payload_2',
            'last_activity' => time(),
        ]);

        // Ban the user
        $result = $this->ipBanService->banUser($this->regularUser->id, 'Test ban reason', $this->admin);

        // Assert the operation was successful
        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['ip_count']); // Only one unique IP

        // Get the ban record
        $ban = Banned::where('user_id', $this->regularUser->id)->first();
        
        // Assert only one IP ban was created (distinct IPs)
        $this->assertEquals(1, $ban->bannedIps()->count());
        $this->assertDatabaseHas('banned_ips', [
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.100',
        ]);
    }

    public function test_is_ip_banned_check(): void
    {
        // Create a ban with IP addresses
        $ban = Banned::create([
            'user_id' => $this->regularUser->id,
            'reason' => 'Test ban reason',
        ]);

        BannedIp::create([
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.100',
        ]);

        // Test IP ban check
        $this->assertTrue($this->ipBanService->isIpBanned('192.168.1.100'));
        $this->assertFalse($this->ipBanService->isIpBanned('192.168.1.999'));
    }

    public function test_is_user_banned_check(): void
    {
        // Create a ban
        Banned::create([
            'user_id' => $this->regularUser->id,
            'reason' => 'Test ban reason',
        ]);

        // Test user ban check
        $this->assertTrue($this->ipBanService->isUserBanned($this->regularUser->id));
        $this->assertFalse($this->ipBanService->isUserBanned($this->admin->id));
    }

    public function test_unban_non_banned_user_returns_error(): void
    {
        // Try to unban a user who isn't banned
        $result = $this->ipBanService->unbanUser($this->regularUser->id, $this->admin);

        // Assert the operation failed
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('not currently banned', $result['message']);
    }

    public function test_ban_nonexistent_user_returns_error(): void
    {
        // Try to ban a non-existent user
        $result = $this->ipBanService->banUser(99999, 'Test ban reason', $this->admin);

        // Assert the operation failed
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('User not found', $result['message']);
    }

    public function test_unban_nonexistent_user_returns_error(): void
    {
        // Try to unban a non-existent user
        $result = $this->ipBanService->unbanUser(99999, $this->admin);

        // Assert the operation failed
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('User not found', $result['message']);
    }
}
