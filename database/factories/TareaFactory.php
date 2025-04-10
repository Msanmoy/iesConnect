<?php

namespace Database\Factories;

use App\Models\Tarea;
use App\Models\Usuario;
use App\Models\Asignatura;
use Illuminate\Database\Eloquent\Factories\Factory;

class TareaFactory extends Factory
{
    protected $model = Tarea::class;

    public function definition(): array
    {

        if (Usuario::where('rol', 'PROFESOR')->count() === 0) {
            Usuario::factory()->count(3)->create(['rol' => 'PROFESOR']);
        }

        if (Asignatura::count() === 0) {
            Asignatura::factory()->count(5)->create();
        }


        return [
            'titulo' => $this->faker->sentence,
            'descripcion' => $this->faker->paragraph,
            'profesor_id' => Usuario::where('rol', 'PROFESOR')->inRandomOrder()->first()?->id,
            'asignatura_id' => Asignatura::inRandomOrder()->first()?->id,
            'visible' => $this->faker->boolean(90), // 90% probabilidad de estar visible
            'eliminado' => false,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Tarea $tarea) {
            \App\Models\Fase::factory()
                ->count(rand(2, 4))
                ->for($tarea)
                ->create()
                ->each(function ($fase) {
                    \App\Models\Pregunta::factory()
                        ->count(rand(2, 5))
                        ->for($fase)
                        ->create()
                        ->each(function ($pregunta) {
                            \App\Models\Respuesta::factory()
                                ->count(4)
                                ->for($pregunta)
                                ->create();
                        });
                });
        });
    }
}
