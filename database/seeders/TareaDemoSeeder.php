<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Asignatura;
use App\Models\Tarea;
use App\Models\ProgresoTarea;
use App\Models\Entrega;
use App\Enums\NivelEnum;

class TareaDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear Administrador
        $administrador = Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'rol' => 'ADMINISTRADOR',
        ]);

        // Crear profesor
        $profesor = Usuario::create([
            'nombre' => 'Laura Martinez',
            'email' => 'laura@demo.com',
            'password' => Hash::make('password'),
            'rol' => 'PROFESOR',
        ]);

        $profesor1 = Usuario::create([
            'nombre' => 'Juan Perez',
            'email' => 'juanperez@demo.com',
            'password' => Hash::make('password'),
            'rol' => 'PROFESOR',
        ]);

        // Crear estudiantes
        $estudiante1 = Usuario::create([
            'nombre' => 'Carlos Bautista',
            'email' => 'carlos@demo.com',
            'password' => Hash::make('password'),
            'rol' => 'ESTUDIANTE',
        ]);

        $estudiante2 = Usuario::create([
        'nombre' => 'Marta Díaz',
        'email' => 'marta@demo.com',
        'password' => Hash::make('password'),
        'rol' => 'ESTUDIANTE',
        ]);

        $estudiante3 = Usuario::create([
            'nombre' => 'Antonio Dominguez',
            'email' => 'antonio@demo.com',
            'password' => Hash::make('password'),
            'rol' => 'ESTUDIANTE',
        ]);

        $estudiante4 = Usuario::create([
            'nombre' => 'Sara Duarte',
            'email' => 'sara@demo.com',
            'password' => Hash::make('password'),
            'rol' => 'ESTUDIANTE',
        ]);

        // Crear asignatura
        $asignatura = Asignatura::create([
        'nombre' => 'Programación Web 1ºDAW',
        'descripcion' => 'Laravel y Vue',
        'codigo' => 'PRO2025',
        'usuario_id' => $profesor->id,
        'slug' => 'programacion',
        ]);

        $asignatura2 = Asignatura::create([
            'nombre' => 'Historia 3ºESO',
            'descripcion' => 'Historia de 3ºESO',
            'codigo' => 'HIS2025',
            'usuario_id' => $profesor1->id,
            'slug' => 'historia',
        ]);

        $asignatura3 = Asignatura::create([
            'nombre' => 'Matemáticas 3ºESO',
            'descripcion' => 'Matemáticas de 3ºESO',
            'codigo' => 'MAT2025',
            'usuario_id' => $profesor1->id,
            'slug' => 'matematicas',
        ]);

        // Relacionar estudiantes con asignatura
        $asignatura->estudiantes()->attach([$estudiante1->id, $estudiante2->id]);
        $asignatura2->estudiantes()->attach([$estudiante3->id, $estudiante4->id]);
        $asignatura3->estudiantes()->attach([$estudiante3->id, $estudiante4->id]);

        // Crear tarea
        $tarea = Tarea::create([
        'asignatura_id' => $asignatura->id,
        'titulo' => 'Crear un blog con Laravel',
        'descripcion' => 'Debes crear un pequeño blog funcional con rutas y vistas.',
        'fecha_limite' => now()->addWeek(),
        ]);

        $tarea1 = Tarea::create([
            'asignatura_id' => $asignatura3->id,
            'titulo' => 'Resuelve la ecuación',
            'descripcion' => 'Debes resolver el cuadernillo de ecuaciones.',
            'fecha_limite' => now()->addWeek(),
        ]);

        $tarea2 = Tarea::create([
            'asignatura_id' => $asignatura2->id,
            'titulo' => 'Realiza un resumen del tema 4',
            'descripcion' => 'Debes crear un resumen del tema 4 del libro.',
            'fecha_limite' => now()->addWeek(),
        ]);

        // Asignar niveles y simular progreso
        $progreso1 = ProgresoTarea::create([
        'tarea_id' => $tarea->id,
        'usuario_id' => $estudiante1->id,
        'nivel_asignado' => NivelEnum::SENCILLO,
        'entregado_sencillo' => false,
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

        $progreso3 = ProgresoTarea::create([
            'tarea_id' => $tarea1->id,
            'usuario_id' => $estudiante4->id,
            'nivel_asignado' => NivelEnum::SENCILLO,
            'entregado_sencillo' => false,
            'entregado_intermedio' => false,
            'entregado_avanzado' => false,
        ]);

        $progreso4 = ProgresoTarea::create([
            'tarea_id' => $tarea1->id,
            'usuario_id' => $estudiante3->id,
            'nivel_asignado' => NivelEnum::AVANZADO,
            'entregado_sencillo' => false,
            'entregado_intermedio' => false,
            'entregado_avanzado' => false,
        ]);

        // Entrega simulada
        Entrega::create([
        'progreso_tarea_id' => $progreso1->id,
        'nivel' => 'sencillo',
        'archivo' => 'entregas/demo.pdf', // poner demo.pdf en la carpeta public/storage/entregas.
        'fecha_entrega' => now(),
        ]);
    }
}
