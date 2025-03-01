<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $predefinedThemes = [
            [
                'name' => 'Ocean Blue',
                'strap_color' => '#0077BE',
                'body_color' => '#E0F7FA',
                'hover_color' => 'blue-700',
                'count_circle_color' => '#005588',
                'content_bg_color' => '#FFFFFF',
            ],
            [
                'name' => 'Forest Green',
                'strap_color' => '#228B22',
                'body_color' => '#E8F5E9',
                'hover_color' => 'green-700',
                'count_circle_color' => '#1B5E20',
                'content_bg_color' => '#FFFFFF',
            ],
            [
                'name' => 'Sunset Orange',
                'strap_color' => '#FF4500',
                'body_color' => '#FFF3E0',
                'hover_color' => 'orange-700',
                'count_circle_color' => '#D84315',
                'content_bg_color' => '#FFFFFF',
            ],
            [
                'name' => 'Lavender Dream',
                'strap_color' => '#8E4585',
                'body_color' => '#F3E5F5',
                'hover_color' => 'purple-700',
                'count_circle_color' => '#6A1B9A',
                'content_bg_color' => '#FFFFFF',
            ],
            [
                'name' => 'Mint Breeze',
                'strap_color' => '#3EB489',
                'body_color' => '#E0FFF0',
                'hover_color' => 'teal-700',
                'count_circle_color' => '#2D8B6D',
                'content_bg_color' => '#FFFFFF',
            ],
        ];

        foreach ($predefinedThemes as $theme) {
            Theme::create($theme);
        }
    }
}
