<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\ProgresoTarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EntregaController extends Controller
{

    public function store(Request $request, ProgresoTarea $progreso)
    {
        $request->validate([
            'nivel' => 'required|in:sencillo,intermedio,avanzado',
            'archivo' => 'required|file|max:20480'
        ]);

        if ($progreso->usuario_id !== Auth::id()) {
            abort(403, 'No autorizado.');
        }

        $nivel = $request->nivel;
        $nivelAsignado = $progreso->nivel_asignado->value;

        $nivelesPermitidos = [
            'sencillo' => [],
            'intermedio' => ['entregado_sencillo'],
            'avanzado' => ['entregado_intermedio'],
        ];

        if ($nivel === 'sencillo' && $nivelAsignado !== 'sencillo') {
            return back()->withErrors(['No puedes entregar este nivel']);
        }

        if ($nivel === 'intermedio' && !$progreso->entregado_sencillo) {
            return back()->withErrors(['Debes completar el nivel sencillo primero']);
        }

        if ($nivel === 'avanzado') {
            if ($nivelAsignado === 'sencillo' && !$progreso->entregado_intermedio) {
                return back()->withErrors(['Debes completar el nivel intermedio primero']);
            } elseif ($nivelAsignado === 'intermedio' && !$progreso->entregado_intermedio) {
                return back()->withErrors(['Debes completar el nivel intermedio primero']);
            }
        }

        $ruta = $request->file('archivo')->store('entregas', 'public');

        $progreso->entregas()->create([
            'nivel' => $nivel,
            'archivo' => $ruta,
            'fecha_entrega' => now(),
        ]);


        $progreso->update([
            "entregado_{$nivel}" => true,
        ]);

        return back()->with('success', 'Entrega enviada correctamente.');
    }

    public function updateFeedback(Request $request, Entrega $entrega)
    {
        $request->validate([
            'comentario' => 'nullable|string',
            'nota' => 'nullable|numeric|min:0|max:10',
        ]);

        $profesorId = $entrega->progreso->tarea->asignatura->usuario_id;
        if (auth()->id() !== $profesorId) {
            abort(403);
        }

        $entrega->comentario = $request->comentario;
        $entrega->nota = $request->nota;
        $entrega->save();

        if ($entrega->nota !== null) {
            $progreso = $entrega->progreso;

            $niveles = ['sencillo', 'intermedio', 'avanzado'];
            $nivelActual = $progreso->nivel_asignado->value;

            $indiceActual = array_search($nivelActual, $niveles);

            if ($indiceActual !== false) {
                if ($nivelActual === 'avanzado') {
                    $progreso->finalizado = true;
                    $progreso->save();
                } elseif ($indiceActual < count($niveles) - 1) {
                    $nuevoNivel = $niveles[$indiceActual + 1];
                    $progreso->nivel_asignado = $nuevoNivel;
                    $progreso->save();
                }
            }
        }

        $tarea = $entrega->progreso->tarea;
        $estudiante = $entrega->progreso->estudiante;

        $estudiante->notify(new \App\Notifications\TaskCorrectedNotification(
            'Se ha actualizado el feedback de una tarea: ' . $tarea->titulo,
            route('tareas.ver.estudiante', $tarea->id),
        ));


        return back()->with('success', 'Feedback guardado correctamente.');
    }


}
