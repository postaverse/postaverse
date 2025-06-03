<?php

namespace Tests\Feature\Admin;

use App\Models\User\User;
use App\Models\User\Banned;
use App\Models\BannedIp;
use App\Livewire\Admin\AdminDashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

class IpBanTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $admin;
    protected $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'handle' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'admin_rank' => 3, // Has permission to ban users
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

        // Act as admin and ban the user using Livewire
        Livewire::actingAs($this->admin)
            ->test(AdminDashboard::class)
            ->set('user_id', $this->regularUser->id)
            ->set('reason', 'Test ban reason')
            ->call('banUser');

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
    }

    public function test_admin_can_ban_user_without_ip_addresses(): void
    {
        // Act as admin and ban the user using Livewire (no session data = no IPs)
        Livewire::actingAs($this->admin)
            ->test(AdminDashboard::class)
            ->set('user_id', $this->regularUser->id)
            ->set('reason', 'Test ban reason')
            ->call('banUser');

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

        // Act as admin and unban the user using Livewire
        Livewire::actingAs($this->admin)
            ->test(AdminDashboard::class)
            ->set('uid', $this->regularUser->id)
            ->call('unbanUser');

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

        // Act as regular user and try to ban another user using IpBanService directly
        $ipBanService = new \App\Services\IpBanService();
        $result = $ipBanService->banUser($anotherUser->id, 'Test ban reason', $this->regularUser);

        // Assert the ban was rejected
        $this->assertFalse($result['success']);
        $this->assertEquals('Insufficient permissions to ban users.', $result['message']);

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

        // Act as lower rank admin and try to ban higher rank admin using IpBanService directly
        $ipBanService = new \App\Services\IpBanService();
        $result = $ipBanService->banUser($higherAdmin->id, 'Test ban reason', $this->admin);

        // Assert the ban was rejected
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('You cannot ban an admin with equal or higher rank', $result['message']);

        // Assert no ban was created
        $this->assertDatabaseMissing('banned', [
            'user_id' => $higherAdmin->id,
        ]);
    }

    public function test_ban_relationships_work_correctly(): void
    {
        // Create a ban with IP addresses
        $ban = Banned::create([
            'user_id' => $this->regularUser->id,
            'reason' => 'Test ban reason',
        ]);

        $bannedIp1 = BannedIp::create([
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.100',
        ]);

        $bannedIp2 = BannedIp::create([
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.101',
        ]);

        // Test relationships
        $this->assertEquals($this->regularUser->id, $ban->user->id);
        $this->assertEquals(2, $ban->bannedIps->count());
        $this->assertEquals($ban->id, $bannedIp1->banned->id);
        $this->assertEquals($ban->id, $bannedIp2->banned->id);

        // Test that user has bans relationship
        $this->assertTrue($this->regularUser->bans()->exists());
        $this->assertEquals(1, $this->regularUser->bans()->count());
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

        // Act as admin and ban the user using Livewire
        Livewire::actingAs($this->admin)
            ->test(AdminDashboard::class)
            ->set('user_id', $this->regularUser->id)
            ->set('reason', 'Test ban reason')
            ->call('banUser');

        // Get the ban record
        $ban = Banned::where('user_id', $this->regularUser->id)->first();
        
        // Assert only one IP ban was created (distinct IPs)
        $this->assertEquals(1, $ban->bannedIps()->count());
        $this->assertDatabaseHas('banned_ips', [
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.100',
        ]);
    }

    public function test_cascade_delete_works_when_ban_is_deleted(): void
    {
        // Create a ban with IP addresses
        $ban = Banned::create([
            'user_id' => $this->regularUser->id,
            'reason' => 'Test ban reason',
        ]);

        $bannedIp = BannedIp::create([
            'banned_id' => $ban->id,
            'ip_address' => '192.168.1.100',
        ]);

        // Verify they exist
        $this->assertDatabaseHas('banned', ['id' => $ban->id]);
        $this->assertDatabaseHas('banned_ips', ['id' => $bannedIp->id]);

        // Delete the ban
        $ban->delete();

        // Verify cascade delete worked
        $this->assertDatabaseMissing('banned', ['id' => $ban->id]);
        $this->assertDatabaseMissing('banned_ips', ['id' => $bannedIp->id]);
    }
}
