<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'admin',
                'description' => 'Awarded for being an admin.',
                'icon' => 'admin.png',
            ],
            [
                'name' => 'cadet',
                'description' => 'Awarded for posting 10 posts.',
                'icon' => 'cadet.png',
            ],
            [
                'name' => 'moonwalker',
                'description' => 'Awarded for posting 50 posts.',
                'icon' => 'moonwalker.png',
            ],
            [
                'name' => 'rocketeer',
                'description' => 'Awarded for posting 100 posts.',
                'icon' => 'rocketeer.png',
            ],
        ];

        DB::table('badges')->insert($badges);
    }
}