<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Asignatura;
use App\Models\Tarea;
use App\Models\Fase;
use App\Models\Pregunta;
use App\Models\Respuesta;

class IESDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear o recuperar profesor
        $profesor = Usuario::firstOrCreate(
            ['email' => 'maria@ies.com'],
            [
                'nombre' => 'Profe María',
                'password' => Hash::make('password'),
                'rol' => 'PROFESOR',
            ]
        );

        // Crear asignatura si no existe
        $asignatura = Asignatura::firstOrCreate(
            ['codigo' => 'MAT2025'],
            [
                'nombre' => 'Matemáticas Avanzadas',
                'descripcion' => 'Asignatura de prueba para el seeder.',
                'profesor_id' => $profesor->id,
                'slug' => 'matematicas-avanzadas',
            ]
        );

        // Crear tarea
        $tarea = Tarea::create([
            'titulo' => 'Tarea de Álgebra',
            'descripcion' => 'Resolver ecuaciones y sistemas.',
            'profesor_id' => $profesor->id,
            'asignatura_id' => $asignatura->id,
            'visible' => true,
            'eliminado' => false,
        ]);

        // Crear 2 fases
        foreach (range(1, 2) as $i) {
            $fase = Fase::create([
                'titulo' => "Fase $i",
                'orden' => $i,
                'tarea_id' => $tarea->id,
            ]);

            // Cada fase con 3 preguntas
            foreach (range(1, 3) as $j) {
                $pregunta = Pregunta::create([
                    'fase_id' => $fase->id,
                    'enunciado' => "¿Cuál es el resultado de $j + $i?",
                    'tipo' => 'opcion_multiple',
                ]);

                // Crear 4 respuestas
                $respuestas = collect();
                foreach (range(1, 4) as $k) {
                    $respuestas->push(new Respuesta([
                        'texto' => "Respuesta $k",
                        'correcta' => false,
                    ]));
                }

                // Marcar una aleatoria como correcta
                $respuestas->random()->correcta = true;

                // Guardar respuestas
                foreach ($respuestas as $respuesta) {
                    $respuesta->pregunta_id = $pregunta->id;
                    $respuesta->save();
                }
            }
        }
    }
}
