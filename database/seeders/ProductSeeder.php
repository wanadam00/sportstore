<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Nike Air Force 1 07',
            'price' => 489,
            'quantity' => 20,
            'category_id'=> 2,
            'brand_id'=>1,
            'description'=>'The radiance lives on in the Nike Air Force 1 07, the basketball original that puts a fresh spin on what you know best: durably stitched overlays, clean finishes and the perfect amount of flash to make you shine.'
        ]);
        Product::create([
            'name' => 'ADIZERO ADIOS PRO 3',
            'price' => 999,
            'quantity' => 15,
            'category_id'=> 2,
            'brand_id'=>2,
            'description'=>'This release is now available to adiClub members. Join the club for more members only discounts, rewards and early access on the things you love.'
        ]);
        Product::create([
            'name' => 'Caven Trainers.',
            'price' => 350,
            'quantity' => 10,
            'category_id'=> 2,
            'brand_id'=>3,
            'description'=>'Elevate your sneaker game with the Puma Caven. These breathable sneakers are designed to keep your feet cool and comfortable all day long.'
        ]);

    }
}
