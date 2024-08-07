<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TextTheme;

class TextThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some text themes
        $themes = [
            ['theme_name' => 'glitch', 'meteorPrice' => 15, 'class_name' => 'glitch'],
        ];

        foreach ($themes as $theme) {
            TextTheme::create($theme);
        }
    }
}