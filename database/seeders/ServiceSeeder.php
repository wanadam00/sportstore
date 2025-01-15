<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'name' => 'Construction',
            'slug'=>'construction'
        ]);
        Service::create([
            'name' => 'Custom Part',
            'slug'=>'custom-part'
        ]);
        Service::create([
            'name' => 'Rapid Prototyping',
            'slug'=>'rapid-prototyping'
        ]);
    }
}
