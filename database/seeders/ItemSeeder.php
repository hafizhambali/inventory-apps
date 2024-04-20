<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Brand;
use App\Models\Type;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = Brand::pluck('id');
        $types = Type::pluck('id');

        // Generate random serial numbers
        $faker = Faker::create();

        // Randomly assign serial numbers, brands, and types to items
        for ($i = 0; $i < 20; $i++) {
            Item::create([
                'serial_number' => $faker->unique()->ean8,
                'brand_id' => $brands->random(),
                'type_id' => $types->random(),
            ]);
        }
    }

   
}

