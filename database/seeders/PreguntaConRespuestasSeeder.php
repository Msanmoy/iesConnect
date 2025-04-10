<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\Fase;

class PreguntaConRespuestasSeeder extends Seeder
{
    public function run(): void
    {
        // Puedes ajustar el número de fases o pasarlas desde otro seeder
        Fase::factory()
            ->count(5)
            ->create()
            ->each(function ($fase) {
                // Por cada fase, creamos entre 2 y 4 preguntas
                Pregunta::factory()
                    ->count(rand(2, 4))
                    ->create(['fase_id' => $fase->id])
                    ->each(function ($pregunta) {
                        // Creamos 4 respuestas
                        $respuestas = Respuesta::factory()
                            ->count(4)
                            ->make(['pregunta_id' => $pregunta->id]);

                        // Elegimos una respuesta aleatoria y la marcamos como correcta
                        $respuestas->random()->correcta = true;

                        // Guardamos todas las respuestas
                        foreach ($respuestas as $respuesta) {
                            $respuesta->pregunta_id = $pregunta->id;
                            $respuesta->save();
                        }
                    });
            });
    }
}
