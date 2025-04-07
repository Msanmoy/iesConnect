<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use App\Models\Aula;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Curso;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         Usuario::factory(100)->create();

        DB::table('asignaturas')->insert([
            ['nombre' => 'Lengua'],
            ['nombre' => 'Matematicas'],
            ['nombre' => 'Biologia'],
            ['nombre' => 'Geografia'],
            ['nombre' => 'Historia'],
            ['nombre' => 'Economia'],
            ['nombre' => 'Francés'],
            ['nombre' => 'Geologia'],
            ['nombre' => 'Inglés'],
        ]);

        DB::table('cursos')->insert([
            ['nombre' => '1ºESO'],
            ['nombre' => '2ºESO'],
            ['nombre' => '3ºESO'],
            ['nombre' => '4ºESO'],
            ['nombre' => '1ºBachillerato'],
            ['nombre' => '2ºBachillerato'],
            ['nombre' => '1ºSMR'],
            ['nombre' => '2ºSMR']
        ]);

        DB::table('clases')->insert([
            [
                'asignatura_id' => Asignatura::all()->random()->id,
                'curso_id' => Curso::all()->random()->id,
            ],
            [
                'asignatura_id' => Asignatura::all()->random()->id,
                'curso_id' => Curso::all()->random()->id,
            ],
            [
                'asignatura_id' => Asignatura::all()->random()->id,
                'curso_id' => Curso::all()->random()->id,
            ],
            [
                'asignatura_id' => Asignatura::all()->random()->id,
                'curso_id' => Curso::all()->random()->id,
            ],

        ]);



        DB::table('aulas')->insert([
            [
                'anio' => '25/26',
                'grupo' => 'A',
                'clase_id' => '1',
                'propietario_id' => '2',
                'eliminado' => false
            ],
            [
                'anio' => '25/26',
                'grupo' => 'B',
                'clase_id' => '2',
                'propietario_id' => '2',
                'eliminado' => false
            ],
            [
                'anio' => '25/26',
                'grupo' => 'C',
                'clase_id' => '3',
                'propietario_id' => '3',
                'eliminado' => false
            ]
        ]);




    }
}
