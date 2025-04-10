<?php

namespace Database\Factories;

use App\Models\Fase;
use App\Models\Tarea;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaseFactory extends Factory
{
    protected $model = Fase::class;

    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'orden' => $this->faker->unique(true)->numberBetween(1, 10),
            'tarea_id' => Tarea::factory(),
        ];
    }
}
