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
            'usuario_id' => 'required|array',
            'usuario_id.*' => 'exists:usuarios,id',
            'nivel_asignado' => 'required|array',
        ]);

        $contador = 0;

        foreach ($request->usuario_id as $usuarioId) {
            $nivel = $request->nivel_asignado[$usuarioId] ?? null;

            if (!in_array($nivel, NivelEnum::values())) {
                continue;
            }

            if (isset($request->progreso_id[$usuarioId])) {
                // Actualizar
                $progreso = ProgresoTarea::find($request->progreso_id[$usuarioId]);

                if ($progreso && $progreso->nivel_asignado->value !== $nivel) {
                    $progreso->nivel_asignado = NivelEnum::from($nivel);
                    $progreso->save();
                    $contador++;
                }
            } else {
                $yaExiste = ProgresoTarea::where('tarea_id', $tarea->id)
                    ->where('usuario_id', $usuarioId)
                    ->exists();

                if (! $yaExiste) {
                    ProgresoTarea::create([
                        'tarea_id' => $tarea->id,
                        'usuario_id' => $usuarioId,
                        'nivel_asignado' => NivelEnum::from($nivel),
                    ]);
                    $contador++;
                }
            }
        }

        return back()->with('success', "$contador niveles actualizados o asignados correctamente.");
    }


    private function autorizarTarea(Tarea $tarea)
    {
        if ($tarea->asignatura->usuario_id !== auth()->id()) {
            abort(403, 'No autorizado.');
        }
    }

}

