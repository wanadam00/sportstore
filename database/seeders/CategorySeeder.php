<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Shirt',
            'slug'=>'shirt'
        ]);
        Category::create([
            'name' => 'Shoes',
            'slug'=>'shoes'
        ]);
        Category::create([
            'name' => 'Pants',
            'slug'=>'pants'
        ]);
    }
}
