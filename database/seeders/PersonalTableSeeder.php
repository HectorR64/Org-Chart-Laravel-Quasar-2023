<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Personal;
use App\Models\Department;
use Faker\Factory as Faker; // Importa correctamente Faker

class PersonalTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // El primer registro
        Personal::create([
            'label' => 'Juan',
            'expand' => true,
            'department_id' => 1 // Asegúrate que este ID exista en la tabla Departments
        ]);

        // Generar 9 registros adicionales
        for ($i = 0; $i < 9; $i++) {
            $departmentId = Department::inRandomOrder()->first()->id; // Obtiene un id de departamento aleatorio
            $parentId = Personal::inRandomOrder()->first()->id; // Obtiene un id de personal aleatorio

            Personal::create([
                'label' => $faker->name,
                'expand' => $faker->boolean,
                'department_id' => $departmentId,
                'parent_id' => $parentId
            ]);
        }
    }
}
