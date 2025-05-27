<?php

namespace App\Http\Controllers;

use App\Models\Cuestionario;
use App\Models\Tarea;
use Illuminate\Http\Request;

class CuestionarioController extends Controller
{
    public function build(Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        $cuestionario = $tarea->cuestionario;

        if (!$cuestionario) {
            $cuestionario = $tarea->cuestionario()->create([
                'fecha_publicacion' => now(),
                'fecha_entrega' => now()->addDays(7),
            ]);
        }

        $cuestionario->load('preguntas.respuestas');

        return view('cuestionarios.build', [
            'tarea' => $tarea,
            'cuestionario' => $cuestionario,
            'esGenerica' => $tarea->es_generica,
            'preguntasPorNivel' => $cuestionario->preguntas->groupBy('nivel'),
        ]);
    }

    public function estadisticas(Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        $cuestionario = $tarea->cuestionario()->with('preguntas.respuestas.respuestasEstudiante')->firstOrFail();

        $resumen = [];

        foreach ($cuestionario->preguntas as $pregunta) {
            $respuestas = $pregunta->respuestas->map(function ($respuesta) {
                return [
                    'texto' => $respuesta->texto,
                    'conteo' => $respuesta->respuestasEstudiante->count(),
                    'correcta' => $respuesta->es_correcta,
                ];
            });

            $total = $respuestas->sum('conteo');
            $correctas = $respuestas->filter(fn($r) => $r['correcta'])->sum('conteo');

            $resumen[] = [
                'pregunta' => $pregunta->enunciado,
                'respuestas' => $respuestas,
                'total' => $total,
                'correctas' => $correctas,
                'porcentaje' => $total > 0 ? round(($correctas / $total) * 100) : 0,
            ];
        }

        $respuestasPorUsuario = \App\Models\RespuestaEstudiante::whereIn('pregunta_id', $cuestionario->preguntas->pluck('id'))
            ->with('pregunta')
            ->get()
            ->groupBy('usuario_id');

        $notasAlumnos = $respuestasPorUsuario->map(function ($respuestas, $usuarioId) {
            $nota = $respuestas->sum('nota');
            $nombre = \App\Models\Usuario::find($usuarioId)?->name ?? 'Sin nombre';
            return [
                'usuario' => $nombre,
                'nota' => $nota,
            ];
        })->values();

        return view('cuestionarios.estadisticas', compact('tarea', 'resumen', 'notasAlumnos'));
    }


    private function autorizarTarea(Tarea $tarea)
    {
        if ($tarea->asignatura->usuario_id !== auth()->id()) {
            abort(403, 'No autorizado.');
        }
    }
}
