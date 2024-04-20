<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hardwareTypes = [
            ['name' => 'Monitor'],
            ['name' => 'Headphone'],
            ['name' => 'Laptop'],
            ['name' => 'Keyboard'],
            ['name' => 'Mouse'],
            ['name' => 'Printer'],
            ['name' => 'Scanner'],
            ['name' => 'Speaker'],
            ['name' => 'Tablet'],
            ['name' => 'Projector'],
        ];

        foreach ($hardwareTypes as $type) {
            Type::create($type);
        }
    }
}
