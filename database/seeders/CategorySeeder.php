<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Mochi Original',
                'description' => 'Mochi dengan rasa original dan tradisional',
            ],
            [
                'name' => 'Mochi Fruit',
                'description' => 'Mochi dengan isian buah-buahan',
            ],
            [
                'name' => 'Mochi Premium',
                'description' => 'Mochi dengan bahan premium dan rasa eksklusif',
            ],
            [
                'name' => 'Mochi Chocolate',
                'description' => 'Mochi dengan rasa cokelat',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Categories seeded successfully!');
    }
}
