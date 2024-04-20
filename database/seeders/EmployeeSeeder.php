<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initialize Faker
        $faker = Faker::create();

        // Get all departments from the database
        $departments = Department::all();

        // Define the number of employees to create for each department
        $numEmployeesPerDepartment = 5;

        // Create employees for each department
        foreach ($departments as $department) {
            for ($i = 1; $i <= $numEmployeesPerDepartment; $i++) {
                    Employee::create([
                        'name' => $faker->unique()->name,
                        'dept_id' => $department->id,
                        'employee_id' => $department->code . '-' . $i,
                ]);
            }
        }
    }
}
