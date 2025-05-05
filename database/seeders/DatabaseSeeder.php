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

        $this->call(TareaDemoSeeder::class);

    }
}
