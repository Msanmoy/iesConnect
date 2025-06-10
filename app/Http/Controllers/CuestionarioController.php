<?php

namespace App\Http\Controllers;

use App\Models\Cuestionario;
use App\Models\Pregunta;
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

        $cuestionarioId = $tarea->cuestionario->id;

        $notasAlumnos = RespuestaEstudiante::whereHas('pregunta', function ($query) use ($cuestionarioId) {
            $query->where('cuestionario_id', $cuestionarioId);
        })
            ->with(['usuario', 'pregunta.respuestas'])
            ->get()
            ->groupBy('usuario_id')
            ->map(function ($respuestas, $usuarioId) {
                $usuario = $respuestas->first()->usuario;
                $nota = 0;

                foreach ($respuestas as $respuesta) {
                    $pregunta = $respuesta->pregunta;

                    if ($pregunta->tipo === 'test') {
                        $correcta = $pregunta->respuestas->firstWhere('es_correcta', true);
                        if ($respuesta->respuesta_id === $correcta?->id) {
                            $nota += $pregunta->puntos;
                        }
                    } elseif ($pregunta->tipo === 'abierta') {
                        $nota += $respuesta->nota ?? 0;
                    }
                }

                return [
                    'usuario' => $usuario?->nombre ?? 'Alumno sin nombre',
                    'nota' => $nota,
                ];
            })
            ->values();


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
        $cuestionario = $tarea->cuestionario()->firstOrFail();
        $nivelActual = $this->nivelDesbloqueado($cuestionario->id);

        $preguntas = $cuestionario->preguntas()
            ->with('respuestas')
            ->where('nivel', $nivelActual)
            ->get();

        return view('cuestionarios.responder', compact('cuestionario', 'tarea', 'preguntas', 'nivelActual'));
    }

    protected function nivelDesbloqueado($cuestionarioId)
    {
        $respuestas = RespuestaEstudiante::whereHas('pregunta', fn ($q) => $q->where('cuestionario_id', $cuestionarioId))
            ->where('usuario_id', auth()->id())
            ->get();

        $niveles = ['sencillo', 'intermedio', 'avanzado'];

        foreach ($niveles as $i => $nivel) {
            $preguntas = Pregunta::where('cuestionario_id', $cuestionarioId)->where('nivel', $nivel)->get();
            $respuestasNivel = $preguntas->filter(function ($pregunta) use ($respuestas) {
                return $respuestas->where('pregunta_id', $pregunta->id)->isNotEmpty();
            });

            $aciertos = $preguntas->filter(function ($pregunta) use ($respuestas) {
                $respuesta = $respuestas->firstWhere('pregunta_id', $pregunta->id);
                $correcta = $pregunta->respuestas->firstWhere('es_correcta', true);
                return $respuesta && $respuesta->respuesta_id === $correcta?->id;
            });

            if ($preguntas->count() === 0 || $aciertos->count() < $preguntas->count()) {
                return $niveles[$i];
            }
        }

        return 'avanzado';
    }



    public function guardarRespuestas(Request $request, Tarea $tarea)
    {
        $cuestionario = $tarea->cuestionario()
            ->with('preguntas')
            ->firstOrFail();

        $usuarioId = auth()->id();

        $respuestasTest = $request->input('respuestas', []);
        $respuestasAbiertas = $request->input('respuestas_abiertas', []);

        foreach ($cuestionario->preguntas as $pregunta) {
            if ($pregunta->tipo === 'test' && isset($respuestasTest[$pregunta->id])) {
                RespuestaEstudiante::updateOrCreate(
                    [
                        'pregunta_id' => $pregunta->id,
                        'usuario_id' => $usuarioId,
                    ],
                    [
                        'respuesta_id' => (int) $respuestasTest[$pregunta->id],
                        'respuesta_abierta' => null,
                    ]
                );
            }

            if ($pregunta->tipo === 'abierta' && isset($respuestasAbiertas[$pregunta->id])) {
                RespuestaEstudiante::updateOrCreate(
                    [
                        'pregunta_id' => $pregunta->id,
                        'usuario_id' => $usuarioId,
                    ],
                    [
                        'respuesta_abierta' => $respuestasAbiertas[$pregunta->id],
                        'respuesta_id' => null,
                    ]
                );
            }
        }

        return redirect()->route('cuestionarios.resultado', $tarea)
            ->with('success', 'Respuestas enviadas correctamente');
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
