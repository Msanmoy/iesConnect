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
        $this->autorizarTarea($tarea);

        $estudiantes = $tarea->asignatura->estudiantes;
        $tarea->load('progresos');

        return view('progreso.create', compact('tarea', 'estudiantes'));
    }

    public function store(Request $request, Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'usuario_id.*' => 'exists:usuarios,id',
            'nivel_asignado' => 'required|in:sencillo,intermedio,avanzado',
        ]);

        $contador = 0;

        foreach ($request->usuario_id as $usuarioId) {
            $nivel = $request->nivel_asignado[$usuarioId] ?? null;

            if (!in_array($nivel, ['sencillo', 'intermedio', 'avanzado'])) {
                continue; // Nivel inválido o no enviado
            }

            // Evita duplicados
            $yaExiste = ProgresoTarea::where('tarea_id', $tarea->id)
                ->where('usuario_id', $usuarioId)
                ->exists();

            if ($yaExiste) {
                continue;
            }

            ProgresoTarea::create([
                'tarea_id' => $tarea->id,
                'usuario_id' => $usuarioId,
                'nivel_asignado' => NivelEnum::from($nivel),
            ]);

            $contador++;
        }

        return redirect()
            ->route('tareas.index')
            ->with('success', "$contador estudiante(s) asignado(s) correctamente.");
    }

    private function autorizarTarea(Tarea $tarea)
    {
        if ($tarea->asignatura->usuario_id !== auth()->id()) {
            abort(403, 'No autorizado.');
        }
    }

}

