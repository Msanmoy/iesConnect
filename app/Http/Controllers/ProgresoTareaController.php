<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProgresoTarea;
use App\Models\Tarea;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Enums\NivelEnum;

class ProgresoTareaController extends Controller
{
    public function create(Tarea $tarea)
    {
        $estudiantes = $tarea->asignatura->estudiantes;
        return view('progreso.create', compact('tarea', 'estudiantes'));
    }

    public function store(Request $request, Tarea $tarea)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'nivel_asignado' => 'required|in:sencillo,intermedio,avanzado',
        ]);

        $yaExiste = ProgresoTarea::where('tarea_id', $tarea->id)
            ->where('usuario_id', $request->usuario_id)
            ->exists();

        if ($yaExiste) {
            return back()->withErrors(['Este estudiante ya tiene un progreso asignado.']);
        }

        ProgresoTarea::create([
            'tarea_id' => $tarea->id,
            'usuario_id' => $request->usuario_id,
            'nivel_asignado' => $request->nivel_asignado,
        ]);

        return back()->with('success', 'Nivel asignado correctamente.');
    }
}
