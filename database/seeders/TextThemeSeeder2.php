<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TextTheme;

class TextThemeSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some text themes
        $themes = [
            ['theme_name' => 'wave', 'meteorPrice' => 20, 'class_name' => 'wave'],
        ];

        foreach ($themes as $theme) {
            TextTheme::create($theme);
        }
    }
}