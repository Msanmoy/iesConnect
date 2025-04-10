<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Asignatura;
use App\Models\Tarea;
use App\Models\Fase;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Profesores y estudiantes
        Usuario::factory()->createMany([
            [
                'nombre' => 'Profe María',
                'email' => 'maria@ies.com',
                'rol' => 'PROFESOR',
            ],
            [
                'nombre' => 'Alumno Juan',
                'email' => 'juan@ies.com',
                'rol' => 'ESTUDIANTE',
            ],
            [
                'nombre' => 'Alumno Carla',
                'email' => 'carla@ies.com',
                'rol' => 'ESTUDIANTE',
            ],
        ]);

        $profesor = Usuario::where('rol', 'PROFESOR')->first();

        // Crear Asignaturas
        $asignaturas = Asignatura::factory()->count(2)->create([
            'profesor_id' => $profesor->id,
        ]);

        // Unir estudiantes a las asignaturas
        foreach ($asignaturas as $asignatura) {
            $asignatura->usuarios()->attach(
                Usuario::where('rol', 'ESTUDIANTE')->pluck('id')->toArray()
            );
        }

        // Crear tareas con fases, preguntas y respuestas
        $asignaturas->each(function ($asignatura) {
            $tareas = Tarea::factory()->count(2)->create([
                'asignatura_id' => $asignatura->id,
            ]);

            $tareas->each(function ($tarea) {
                $fases = Fase::factory()->count(3)->create([
                    'tarea_id' => $tarea->id,
                ]);

                $fases->each(function ($fase) {
                    $preguntas = Pregunta::factory()->count(2)->create([
                        'fase_id' => $fase->id,
                    ]);

                    $preguntas->each(function ($pregunta) {
                        Respuesta::factory()->count(3)->create([
                            'pregunta_id' => $pregunta->id,
                        ]);
                    });
                });
            });
        });


        $this->call(PreguntaConRespuestasSeeder::class);
        $this->call(IESDemoSeeder::class);

    }
}
