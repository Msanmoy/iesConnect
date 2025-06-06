<?php

namespace App\Http\Controllers;

use App\Models\Cuestionario;
use App\Models\RespuestaEstudiante;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function formularioEstudiante(Tarea $tarea)
    {
        $cuestionario = $tarea->cuestionario()->with('preguntas.respuestas')->firstOrFail();

        return view('cuestionarios.responder', compact('cuestionario', 'tarea'));
    }

    public function guardarRespuestas(Request $request, Tarea $tarea)
    {
        $cuestionario = $tarea->cuestionario()
            ->with(['preguntas.respuestas'])
            ->firstOrFail();

        $usuarioId = Auth::id();

        foreach ($cuestionario->preguntas as $pregunta) {
            $campo = 'pregunta_' . $pregunta->id;
            $respuesta = $request->input($campo);

            if (!is_null($respuesta)) {
                if ($pregunta->tipo === 'test') {
                    RespuestaEstudiante::updateOrCreate(
                        [
                            'pregunta_id' => $pregunta->id,
                            'usuario_id' => $usuarioId,
                        ],
                        [
                            'respuesta_id' => (int) $respuesta,
                            'respuesta_abierta' => null,
                        ]
                    );
                } else {
                    RespuestaEstudiante::updateOrCreate(
                        [
                            'pregunta_id' => $pregunta->id,
                            'usuario_id' => $usuarioId,
                        ],
                        [
                            'respuesta_abierta' => $respuesta,
                            'respuesta_id' => null,
                        ]
                    );
                }
            }
        }

        return redirect()->route('cuestionarios.resultado', $tarea)->with('success', 'Respuestas enviadas correctamente');
    }


    public function verResultado(Tarea $tarea)
    {
        $cuestionario = $tarea->cuestionario()
            ->with(['preguntas.respuestas', 'preguntas'])
            ->firstOrFail();

        $preguntas = $cuestionario->preguntas;
        $usuarioId = auth()->id();

        $resultado = [];
        $total = 0;
        $maximo = 0;

        foreach ($preguntas as $pregunta) {
            $respuesta = \App\Models\RespuestaEstudiante::where('pregunta_id', $pregunta->id)
                ->where('usuario_id', $usuarioId)
                ->first();

            $nota = 0;

            if ($pregunta->tipo === 'test') {
                $respuestaCorrecta = $pregunta->respuestas->firstWhere('es_correcta', true);
                if ($respuesta && $respuestaCorrecta && $respuesta->respuesta_id === $respuestaCorrecta->id) {
                    $nota = $pregunta->puntos;
                    $total += $nota;
                }
            } else {

                $nota = $respuesta->nota ?? null;
                if ($nota !== null) {
                    $total += $nota;
                }
            }

            $maximo += $pregunta->puntos;

            $resultado[] = [
                'pregunta' => $pregunta,
                'respuesta_estudiante' => $respuesta,
                'nota' => $nota,
            ];
        }

        return view('cuestionarios.resultado', compact('tarea', 'resultado', 'total', 'maximo'));
    }

}
