<?php

namespace Tests\Unit\Models;

use App\Models\User\Banned;
use App\Models\BannedIp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannedIpModelTest extends TestCase
{
    use RefreshDatabase;

    protected $bannedIp;
    protected $banned;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user first
        $user = \Database\Factories\UserFactory::new()->create();
        
        // Create a banned record
        $this->banned = Banned::create([
            'user_id' => $user->id,
            'reason' => 'Test ban reason',
        ]);

        $this->bannedIp = BannedIp::create([
            'banned_id' => $this->banned->id,
            'ip_address' => '192.168.1.100',
        ]);
    }

    public function test_banned_ip_model_has_correct_fillable_fields(): void
    {
        $fillable = ['banned_id', 'ip_address'];
        
        $this->assertEquals($fillable, $this->bannedIp->getFillable());
    }

    public function test_banned_ip_uses_correct_table(): void
    {
        $this->assertEquals('banned_ips', $this->bannedIp->getTable());
    }

    public function test_banned_ip_belongs_to_banned(): void
    {
        $this->assertInstanceOf(Banned::class, $this->bannedIp->banned);
        $this->assertEquals($this->banned->id, $this->bannedIp->banned->id);
        $this->assertEquals($this->banned->reason, $this->bannedIp->banned->reason);
    }

    public function test_banned_ip_can_be_created_with_mass_assignment(): void
    {
        $bannedIp = BannedIp::create([
            'banned_id' => $this->banned->id,
            'ip_address' => '192.168.1.101',
        ]);

        $this->assertInstanceOf(BannedIp::class, $bannedIp);
        $this->assertEquals($this->banned->id, $bannedIp->banned_id);
        $this->assertEquals('192.168.1.101', $bannedIp->ip_address);
    }

    public function test_banned_ip_has_timestamps(): void
    {
        $this->assertNotNull($this->bannedIp->created_at);
        $this->assertNotNull($this->bannedIp->updated_at);
    }

    public function test_banned_ip_can_store_ipv4_addresses(): void
    {
        $ipv4Addresses = [
            '192.168.1.1',
            '10.0.0.1',
            '172.16.0.1',
            '127.0.0.1',
            '8.8.8.8',
        ];

        foreach ($ipv4Addresses as $ip) {
            $bannedIp = BannedIp::create([
                'banned_id' => $this->banned->id,
                'ip_address' => $ip,
            ]);

            $this->assertEquals($ip, $bannedIp->ip_address);
        }
    }

    public function test_banned_ip_can_store_ipv6_addresses(): void
    {
        $ipv6Addresses = [
            '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
            '::1',
            'fe80::1',
            '2001:db8::1',
        ];

        foreach ($ipv6Addresses as $ip) {
            $bannedIp = BannedIp::create([
                'banned_id' => $this->banned->id,
                'ip_address' => $ip,
            ]);

            $this->assertEquals($ip, $bannedIp->ip_address);
        }
    }

    public function test_multiple_banned_ips_can_belong_to_same_ban(): void
    {
        $additionalIps = [
            '192.168.1.101',
            '192.168.1.102',
            '192.168.1.103',
        ];

        foreach ($additionalIps as $ip) {
            BannedIp::create([
                'banned_id' => $this->banned->id,
                'ip_address' => $ip,
            ]);
        }

        // Should have original IP plus 3 additional = 4 total
        $this->assertCount(4, $this->banned->bannedIps);
    }

    public function test_banned_ip_unique_constraint_works(): void
    {
        // Attempt to create duplicate banned IP for same ban
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        BannedIp::create([
            'banned_id' => $this->banned->id,
            'ip_address' => '192.168.1.100', // Same IP as setUp
        ]);
    }

    public function test_same_ip_can_be_banned_under_different_bans(): void
    {
        // Create another user and ban
        $anotherUser = \Database\Factories\UserFactory::new()->create();
        $anotherBan = Banned::create([
            'user_id' => $anotherUser->id,
            'reason' => 'Another ban reason',
        ]);

        // Create banned IP with same IP address but different ban
        $bannedIp = BannedIp::create([
            'banned_id' => $anotherBan->id,
            'ip_address' => '192.168.1.100', // Same IP as setUp
        ]);

        $this->assertEquals('192.168.1.100', $bannedIp->ip_address);
        $this->assertEquals($anotherBan->id, $bannedIp->banned_id);
    }

    public function test_banned_ip_can_be_queried_by_ip_address(): void
    {
        $foundBannedIp = BannedIp::where('ip_address', '192.168.1.100')->first();
        
        $this->assertNotNull($foundBannedIp);
        $this->assertEquals($this->bannedIp->id, $foundBannedIp->id);
        $this->assertEquals('192.168.1.100', $foundBannedIp->ip_address);
    }

    public function test_banned_ip_can_be_queried_with_banned_relationship(): void
    {
        $bannedIpWithRelation = BannedIp::with('banned')
            ->where('ip_address', '192.168.1.100')
            ->first();

        $this->assertNotNull($bannedIpWithRelation->banned);
        $this->assertEquals($this->banned->id, $bannedIpWithRelation->banned->id);
    }

    public function test_banned_ip_foreign_key_constraint_works(): void
    {
        // Attempt to create banned IP with non-existent banned_id
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        BannedIp::create([
            'banned_id' => 99999, // Non-existent banned ID
            'ip_address' => '192.168.1.200',
        ]);
    }
}
