<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple'],
            ['name' => 'Asus'],
            ['name' => 'Dell'],
            ['name' => 'HP'],
            ['name' => 'Samsung'],
            ['name' => 'Sony'],
            ['name' => 'Lenovo'],
            ['name' => 'Acer'],
            ['name' => 'Microsoft'],
            ['name' => 'LG'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
