<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Harapan',
                'slug' => 'harapan',
                'icon' => 'ri-heart-line',
                'color' => 'success',
                'description' => 'Harapan dan impian yang ingin kamu sampaikan',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pengalaman',
                'slug' => 'pengalaman',
                'icon' => 'ri-book-open-line',
                'color' => 'info',
                'description' => 'Cerita pengalaman hidup yang ingin dibagikan',
                'sort_order' => 2,
            ],
            [
                'name' => 'Perasaan',
                'slug' => 'perasaan',
                'icon' => 'ri-emotion-line',
                'color' => 'warning',
                'description' => 'Luapan perasaan yang ingin diungkapkan',
                'sort_order' => 3,
            ],
            [
                'name' => 'Saran',
                'slug' => 'saran',
                'icon' => 'ri-lightbulb-line',
                'color' => 'primary',
                'description' => 'Saran dan masukan untuk program ini',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
