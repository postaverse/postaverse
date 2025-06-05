<?php

namespace Database\Seeders;

use App\Models\Admin\AdminLogs;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdminLogsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get admin users to use for the logs
        $admins = User::where('admin_rank', '>=', 1)->get();
        
        if ($admins->isEmpty()) {
            // Create a test admin if none exist
            $admin = User::create([
                'name' => 'Test Admin',
                'handle' => 'testadmin',
                'email' => 'admin@test.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'admin_rank' => 3,
            ]);
            $admins = collect([$admin]);
        }

        // Get some regular users for actions
        $users = User::where('admin_rank', 0)->limit(5)->get();
        
        if ($users->isEmpty()) {
            // Create test users if none exist
            $testUsers = [];
            for ($i = 1; $i <= 3; $i++) {
                $testUsers[] = User::create([
                    'name' => "Test User {$i}",
                    'handle' => "testuser{$i}",
                    'email' => "user{$i}@test.com",
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'admin_rank' => 0,
                ]);
            }
            $users = collect($testUsers);
        }

        // Sample admin actions with timestamps spread over the last 30 days
        $adminActions = [
            // Ban/Unban actions
            [
                'action' => "Banned user {$users->first()->name} (ID: {$users->first()->id}) and 2 IP addresses",
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'action' => "Unbanned user {$users->first()->name} (ID: {$users->first()->id}) and removed 2 IP bans",
                'created_at' => Carbon::now()->subHours(12),
            ],
            
            // User management actions
            [
                'action' => "Changed {$users->get(1)->name}'s rank from Regular User to Junior Moderator",
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'action' => "Demoted {$users->get(1)->name} from Junior Moderator to Regular User",
                'created_at' => Carbon::now()->subDays(2),
            ],
            
            // Email whitelisting
            [
                'action' => 'Whitelisted email newuser@example.com',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'action' => 'Whitelisted email beta-tester@domain.org',
                'created_at' => Carbon::now()->subDays(7),
            ],
            
            // Content moderation
            [
                'action' => "Deleted post with ID 15 by {$users->get(2)->name} for violating community guidelines",
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'action' => "Removed comment with ID 42 for containing profanity",
                'created_at' => Carbon::now()->subDays(6),
            ],
            [
                'action' => "Deleted blog post 'Inappropriate Content' by {$users->get(1)->name}",
                'created_at' => Carbon::now()->subDays(8),
            ],
            
            // IP banning actions
            [
                'action' => 'Banned IP address 192.168.1.100 for suspicious activity',
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'action' => 'Removed IP ban for 192.168.1.100 after appeal review',
                'created_at' => Carbon::now()->subDays(9),
            ],
            
            // Badge management (if badges are enabled)
            [
                'action' => "Gave 'Early Adopter' badge to {$users->get(2)->name}",
                'created_at' => Carbon::now()->subDays(12),
            ],
            [
                'action' => "Removed 'Verified' badge from {$users->first()->name}",
                'created_at' => Carbon::now()->subDays(11),
            ],
            
            // System actions
            [
                'action' => 'Updated site terms of service',
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'action' => 'Cleared spam comments from the last 24 hours',
                'created_at' => Carbon::now()->subDays(13),
            ],
            [
                'action' => 'Executed bulk user cleanup for inactive accounts',
                'created_at' => Carbon::now()->subDays(20),
            ],
            
            // Appeal handling
            [
                'action' => "Reviewed ban appeal for {$users->get(1)->name} - Appeal denied",
                'created_at' => Carbon::now()->subDays(14),
            ],
            [
                'action' => "Reviewed ban appeal for {$users->get(2)->name} - Appeal approved, user unbanned",
                'created_at' => Carbon::now()->subDays(16),
            ],
            
            // Security actions
            [
                'action' => 'Updated security settings for admin dashboard',
                'created_at' => Carbon::now()->subDays(18),
            ],
            [
                'action' => 'Locked account for user suspicious-user@spam.com due to multiple failed login attempts',
                'created_at' => Carbon::now()->subDays(17),
            ],
            
            // Content management
            [
                'action' => 'Approved featured post: "Community Guidelines Update"',
                'created_at' => Carbon::now()->subDays(21),
            ],
            [
                'action' => 'Rejected post submission for containing copyrighted material',
                'created_at' => Carbon::now()->subDays(19),
            ],
            
            // Maintenance actions
            [
                'action' => 'Performed database maintenance and optimization',
                'created_at' => Carbon::now()->subDays(25),
            ],
            [
                'action' => 'Updated spam detection filters',
                'created_at' => Carbon::now()->subDays(23),
            ],
            [
                'action' => 'Cleared expired session data',
                'created_at' => Carbon::now()->subDays(24),
            ],
            
            // Recent actions (last few hours)
            [
                'action' => 'Reviewed and approved 5 pending user registrations',
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'action' => 'Updated profanity filter with new terms',
                'created_at' => Carbon::now()->subHours(3),
            ],
            [
                'action' => 'Responded to user report about harassment in comments',
                'created_at' => Carbon::now()->subHour(),
            ],
        ];

        // Create admin logs with different admins performing actions
        foreach ($adminActions as $index => $actionData) {
            $admin = $admins->random(); // Randomly assign actions to different admins
            
            AdminLogs::create([
                'admin_id' => $admin->id,
                'action' => $actionData['action'],
                'created_at' => $actionData['created_at'],
                'updated_at' => $actionData['created_at'],
            ]);
        }

        $this->command->info('Admin logs seeded successfully with ' . count($adminActions) . ' log entries.');
    }
}
