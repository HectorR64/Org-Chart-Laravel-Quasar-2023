<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create(['name' => 'Gerencia']);
        Department::create(['name' => 'Sistemas']);
        Department::create(['name' => 'Contabilidad']);
        Department::create(['name' => 'Recurso Humanos']);
        Department::create(['name' => 'Administracion']);

    }
}
