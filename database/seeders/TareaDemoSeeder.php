<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Asignatura;
use App\Models\Tarea;
use App\Models\ProgresoTarea;
use App\Models\Entrega;
use App\Enums\RolEnum;
use App\Enums\NivelEnum;

class TareaDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear profesor
        $profesor = Usuario::create([
            'nombre' => 'Laura Profesora',
            'email' => 'laura@demo.com',
            'password' => Hash::make('password'),
            'rol' => 'PROFESOR',
        ]);

        // Crear estudiantes
        $estudiante1 = Usuario::create([
            'nombre' => 'Carlos Estudiante',
            'email' => 'carlos@demo.com',
            'password' => Hash::make('password'),
            'rol' => 'ESTUDIANTE',
        ]);

        $estudiante2 = Usuario::create([
        'nombre' => 'Marta Estudiante',
        'email' => 'marta@demo.com',
        'password' => Hash::make('password'),
        'rol' => 'ESTUDIANTE',
        ]);

        // Crear asignatura
        $asignatura = Asignatura::create([
        'nombre' => 'Programación Web',
        'descripcion' => 'Laravel y Vue',
        'codigo' => 'PW2025',
        'usuario_id' => $profesor->id,
        'slug' => 'programacion-web',
        ]);

        // Relacionar estudiantes con asignatura
        $asignatura->estudiantes()->attach([$estudiante1->id, $estudiante2->id]);

        // Crear tarea
        $tarea = Tarea::create([
        'asignatura_id' => $asignatura->id,
        'titulo' => 'Crear un blog con Laravel',
        'descripcion' => 'Debes crear un pequeño blog funcional con rutas y vistas.',
        'fecha_limite' => now()->addWeek(),
        ]);

        // Asignar niveles y simular progreso
        $progreso1 = ProgresoTarea::create([
        'tarea_id' => $tarea->id,
        'usuario_id' => $estudiante1->id,
        'nivel_asignado' => NivelEnum::SENCILLO,
        'entregado_sencillo' => true,
        'entregado_intermedio' => false,
        'entregado_avanzado' => false,
        ]);

        $progreso2 = ProgresoTarea::create([
        'tarea_id' => $tarea->id,
        'usuario_id' => $estudiante2->id,
        'nivel_asignado' => NivelEnum::INTERMEDIO,
        'entregado_sencillo' => false,
        'entregado_intermedio' => false,
        'entregado_avanzado' => false,
        ]);

        // Entrega simulada
        Entrega::create([
        'progreso_tarea_id' => $progreso1->id,
        'nivel' => 'sencillo',
        'archivo' => 'entregas/demo_sencillo.pdf',
        'fecha_entrega' => now(),
        ]);
    }
}
