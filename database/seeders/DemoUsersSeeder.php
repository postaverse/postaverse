<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $demoUsers = [
            [
                'username' => 'johndoe',
                'email' => 'john@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'bio' => 'Tech enthusiast and coffee lover ☕ | Sharing my journey in web development',
                'location' => 'San Francisco, CA',
                'website' => 'https://johndoe.dev',
                'is_verified' => true,
            ],
            [
                'username' => 'janesmithdev',
                'email' => 'jane@example.com',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'bio' => '🎨 UI/UX Designer | Creating beautiful digital experiences | Dog mom 🐕',
                'location' => 'New York, NY',
                'website' => 'https://janesmith.design',
                'is_verified' => true,
            ],
            [
                'username' => 'mikejohnson',
                'email' => 'mike@example.com',
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'bio' => 'Photographer 📸 | Travel addict ✈️ | Capturing moments around the world',
                'location' => 'Los Angeles, CA',
                'is_verified' => false,
            ],
            [
                'username' => 'sarahchen',
                'email' => 'sarah@example.com',
                'first_name' => 'Sarah',
                'last_name' => 'Chen',
                'bio' => 'Data Scientist 📊 | AI researcher | Turning data into insights',
                'location' => 'Seattle, WA',
                'website' => 'https://sarahchen.ai',
                'is_verified' => true,
            ],
            [
                'username' => 'alexdeveloper',
                'email' => 'alex@example.com',
                'first_name' => 'Alex',
                'last_name' => 'Rodriguez',
                'bio' => 'Full-stack developer 💻 | Open source contributor | React & Laravel enthusiast',
                'location' => 'Austin, TX',
                'is_verified' => false,
            ],
        ];

        foreach ($demoUsers as $userData) {
            User::create(array_merge([
                'password' => Hash::make('DemoPassword123!'),
                'email_verified_at' => now(),
                'status' => 'active',
                'admin_level' => null, // Regular users
                'timezone' => 'America/New_York',
                'language' => 'en',
                'theme_preference' => 'auto',
                'email_notifications' => true,
                'push_notifications' => true,
                'is_private' => false,
                'last_active_at' => now()->subMinutes(rand(1, 1440)), // Random last activity
            ], $userData));
        }

        $this->command->info('Created 5 demo users');
    }
}
