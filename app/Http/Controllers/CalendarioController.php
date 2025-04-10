<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function eventos(Request $request)
    {
        $usuario = auth()->user();

        // Trae todas las tareas visibles asignadas a asignaturas en las que está el usuario
        $tareas = Tarea::whereHas('asignatura.usuarios', function ($query) use ($usuario) {
            $query->where('usuarios.id', $usuario->id);
        })->where('visible', true)->get();

        // Formato de eventos para FullCalendar
        $eventos = $tareas->map(function ($tarea) {
            return [
                'title' => $tarea->titulo,
                'start' => $tarea->fecha_entrega, // asegúrate de tener esta columna en la tabla tareas
                'url'   => route('asignaturas.tarea', $tarea->id),
                'backgroundColor' => '#0d6efd', // azul Bootstrap
                'borderColor' => '#0d6efd',
            ];
        });

        return response()->json($eventos);
    }
}
