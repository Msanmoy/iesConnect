<?php

namespace Database\Factories;

use App\Models\Pregunta;
use App\Models\Fase;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreguntaFactory extends Factory
{
    protected $model = Pregunta::class;

    public function definition(): array
    {
        return [
            'fase_id' => Fase::factory(),
            'enunciado' => $this->faker->sentence(6),
            'tipo' => $this->faker->randomElement(['opcion_multiple', 'abierta']),
        ];
    }
}
