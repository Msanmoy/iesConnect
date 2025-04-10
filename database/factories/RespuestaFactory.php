<?php

namespace Database\Factories;

use App\Models\Respuesta;
use App\Models\Pregunta;
use Illuminate\Database\Eloquent\Factories\Factory;

class RespuestaFactory extends Factory
{
    protected $model = Respuesta::class;

    public function definition(): array
    {
        return [
            'pregunta_id' => Pregunta::factory(),
            'texto' => $this->faker->sentence(4),
            'correcta' => $this->faker->boolean(25),
        ];
    }
}
