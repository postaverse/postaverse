<?php

namespace Tests\Unit\Models;

use App\Models\User\User;
use App\Models\User\Banned;
use App\Models\BannedIp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class BannedModelTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $banned;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::create([
            'name' => 'Test User',
            'handle' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'admin_rank' => 0,
        ]);

        $this->banned = Banned::create([
            'user_id' => $this->user->id,
            'reason' => 'Test ban reason',
        ]);
    }

    public function test_banned_model_has_correct_fillable_fields(): void
    {
        $fillable = ['user_id', 'reason'];
        
        $this->assertEquals($fillable, $this->banned->getFillable());
    }

    public function test_banned_model_uses_correct_table(): void
    {
        $this->assertEquals('banned', $this->banned->getTable());
    }

    public function test_banned_belongs_to_user(): void
    {
        $this->assertInstanceOf(User::class, $this->banned->user);
        $this->assertEquals($this->user->id, $this->banned->user->id);
        $this->assertEquals($this->user->name, $this->banned->user->name);
    }

    public function test_banned_has_many_banned_ips(): void
    {
        // Create banned IPs
        BannedIp::create([
            'banned_id' => $this->banned->id,
            'ip_address' => '192.168.1.100',
        ]);

        BannedIp::create([
            'banned_id' => $this->banned->id,
            'ip_address' => '192.168.1.101',
        ]);

        $this->assertCount(2, $this->banned->bannedIps);
        $this->assertInstanceOf(BannedIp::class, $this->banned->bannedIps->first());
    }

    public function test_user_has_many_bans(): void
    {
        // Create another ban for the same user
        Banned::create([
            'user_id' => $this->user->id,
            'reason' => 'Another ban reason',
        ]);

        $user = User::find($this->user->id);
        
        $this->assertCount(2, $user->bans);
        $this->assertInstanceOf(Banned::class, $user->bans->first());
    }

    public function test_banned_model_can_be_created_with_mass_assignment(): void
    {
        $banned = Banned::create([
            'user_id' => $this->user->id,
            'reason' => 'Mass assignment test',
        ]);

        $this->assertInstanceOf(Banned::class, $banned);
        $this->assertEquals($this->user->id, $banned->user_id);
        $this->assertEquals('Mass assignment test', $banned->reason);
    }

    public function test_banned_model_has_timestamps(): void
    {
        $this->assertNotNull($this->banned->created_at);
        $this->assertNotNull($this->banned->updated_at);
    }

    public function test_banned_ips_are_deleted_when_ban_is_deleted(): void
    {
        // Create banned IPs
        $bannedIp1 = BannedIp::create([
            'banned_id' => $this->banned->id,
            'ip_address' => '192.168.1.100',
        ]);

        $bannedIp2 = BannedIp::create([
            'banned_id' => $this->banned->id,
            'ip_address' => '192.168.1.101',
        ]);

        // Verify they exist
        $this->assertDatabaseHas('banned_ips', ['id' => $bannedIp1->id]);
        $this->assertDatabaseHas('banned_ips', ['id' => $bannedIp2->id]);

        // Delete the ban
        $this->banned->delete();

        // Verify cascade delete worked
        $this->assertDatabaseMissing('banned_ips', ['id' => $bannedIp1->id]);
        $this->assertDatabaseMissing('banned_ips', ['id' => $bannedIp2->id]);
    }

    public function test_banned_model_can_query_with_relationships(): void
    {
        // Create banned IPs
        BannedIp::create([
            'banned_id' => $this->banned->id,
            'ip_address' => '192.168.1.100',
        ]);

        // Query with relationships
        $bannedWithRelations = Banned::with(['user', 'bannedIps'])
            ->where('id', $this->banned->id)
            ->first();

        $this->assertNotNull($bannedWithRelations->user);
        $this->assertNotNull($bannedWithRelations->bannedIps);
        $this->assertCount(1, $bannedWithRelations->bannedIps);
    }
}
