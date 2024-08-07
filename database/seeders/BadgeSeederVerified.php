<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeederVerified extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'verified',
                'description' => 'Awarded for verifying a website.',
                'icon' => 'verified.png',
            ],
        ];

        DB::table('badges')->insert($badges);

    }
}