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
        static $nombres = ['Informatica', 'Biologia'];
        $nombre = array_shift($nombres);

        return [
            'nombre' => $nombre,
            'codigo' => strtoupper($this->faker->unique()->bothify('COD###')),
            'descripcion' => $this->faker->sentence,
            'slug' => Str::slug($nombre),
            'profesor_id' => 1,
        ];
    }

}
