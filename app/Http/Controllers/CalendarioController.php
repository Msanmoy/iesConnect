<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function eventos(Request $request)
    {
        $usuario = auth()->user();

        $tareas = Tarea::whereHas('asignatura.usuarios', function ($query) use ($usuario) {
            $query->where('usuarios.id', $usuario->id);
        })->where('visible', true)->get();

        $eventos = $tareas->map(function ($tarea) {
            return [
                'title' => $tarea->titulo,
                'start' => $tarea->fecha_entrega,
                'url'   => route('asignaturas.tarea', $tarea->id),
                'backgroundColor' => '#0d6efd',
                'borderColor' => '#0d6efd',
            ];
        });

        return response()->json($eventos);
    }
}
