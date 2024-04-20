<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'IT Department', 'code' => 'IT'],
            ['name' => 'Human Resources', 'code' => 'HR'],
            ['name' => 'Finance Department', 'code' => 'FIN'],
            ['name' => 'Marketing Department', 'code' => 'MARK'],
            ['name' => 'Research & Development', 'code' => 'R&D'],
            ['name' => 'Customer Support', 'code' => 'CS'],
            ['name' => 'Sales Department', 'code' => 'SALES'],
            ['name' => 'Operations Department', 'code' => 'OPS'],
            ['name' => 'Legal Department', 'code' => 'LEGAL'],
            ['name' => 'Quality Assurance', 'code' => 'QA'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
