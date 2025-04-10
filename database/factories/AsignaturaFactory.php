<?php

namespace Database\Factories;

use App\Models\Asignatura;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AsignaturaFactory extends Factory
{
    protected $model = Asignatura::class;

    public function definition(): array
    {

        $nombre = $this->faker->words(2, true);
        return [
            'nombre' => $nombre,
            'codigo' => strtoupper($this->faker->unique(true)->bothify('ASG###')),
            'descripcion' => $this->faker->sentence,
            'slug' => Str::slug($nombre),
            'profesor_id' => 1,
        ];
    }
}
