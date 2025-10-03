<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create Super Admin (Level 1)
        User::create([
            'username' => 'superadmin',
            'email' => 'superadmin@postaverse.net',
            'password' => Hash::make('SuperSecurePassword123!'),
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'bio' => 'Platform Super Administrator with full system access.',
            'admin_level' => 1,
            'is_verified' => true,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Create Senior Admin (Level 2)
        User::create([
            'username' => 'senioradmin',
            'email' => 'senioradmin@postaverse.net',
            'password' => Hash::make('SecurePassword123!'),
            'first_name' => 'Senior',
            'last_name' => 'Admin',
            'bio' => 'Senior Administrator with advanced system privileges.',
            'admin_level' => 2,
            'is_verified' => true,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Create Admin (Level 3)
        User::create([
            'username' => 'admin',
            'email' => 'admin@postaverse.net',
            'password' => Hash::make('AdminPassword123!'),
            'first_name' => 'Site',
            'last_name' => 'Admin',
            'bio' => 'Platform Administrator with content and user management access.',
            'admin_level' => 3,
            'is_verified' => true,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Create Moderator (Level 4)
        User::create([
            'username' => 'moderator',
            'email' => 'moderator@postaverse.net',
            'password' => Hash::make('ModPassword123!'),
            'first_name' => 'Mod',
            'last_name' => 'Erator',
            'bio' => 'Content Moderator focused on community guidelines and content review.',
            'admin_level' => 4,
            'is_verified' => true,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Create Junior Moderator (Level 5)
        User::create([
            'username' => 'juniormod',
            'email' => 'juniormod@postaverse.net',
            'password' => Hash::make('JuniorMod123!'),
            'first_name' => 'Junior',
            'last_name' => 'Mod',
            'bio' => 'Junior Moderator learning the ropes of content moderation.',
            'admin_level' => 5,
            'is_verified' => true,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        $this->command->info('Created 5 admin users with levels 1-5');
    }
}
