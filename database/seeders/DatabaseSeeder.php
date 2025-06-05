<?php

namespace Database\Seeders;

use App\Models\User\User;
use App\Models\User\Site;
use App\Models\Post\Post;
use App\Models\Blog\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a new user
        $user = User::create([
            'name' => 'Test User',                   // Display name
            'handle' => 'testuser',                  // Handle
            'email' => 'test@test.test',             // Fake email for testing
            'password' => Hash::make('password123'), // Password
            'email_verified_at' => now(),            // Verify to ignore looking through logs
            'admin_rank' => 5,                       // Set to Owner rank
            'bio' => 'This is a test user.',         // Set a bio
        ]);

        // Create a new site for the user
        Site::create([
            'user_id' => $user->id,
            'domain' => 'example.com',
            'is_verified' => true,
        ]);

        // Create a new post for the user
        Post::create([
            'user_id' => $user->id,
            'title' => 'Test Post',
            'content' => 'This is a test post.',
            'has_profanity' => false,
        ]);

        // Create a new blog for the user
        Blog::create([
            'user_id' => $user->id,
            'title' => 'Test Blog',
            'content' => 'This is a test blog.',
        ]);

        // Run the AdminLogsSeeder to populate admin logs for testing
        $this->call(AdminLogsSeeder::class);
    }
}
