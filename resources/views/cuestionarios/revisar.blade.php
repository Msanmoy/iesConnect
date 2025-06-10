@extends('layouts.app')

@section('title', 'Revisión del cuestionario')

@section('content')
    <div class="container-xl py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">🧑‍🏫 Revisión del cuestionario: {{ $tarea->titulo }}</h2>
            <a href="{{ route('cuestionarios.estadisticas', $tarea) }}" class="btn btn-outline-secondary">
                <i class="bi bi-graph-up me-1"></i> Ver estadísticas
            </a>
        </div>

        @if ($respuestas->isEmpty())
            <div class="alert alert-info">Aún no hay respuestas registradas.</div>
        @else
            <form method="POST" action="{{ route('cuestionarios.respuestas.guardar', $tarea) }}">
                @csrf

                @foreach($respuestas as $usuarioId => $respuestasAlumno)
                    @php
                        $alumno = $respuestasAlumno->first()->estudiante ?? null;
                        $notaTotal = $respuestasAlumno->sum('nota');
                        $notaMaxima = $respuestasAlumno->sum(fn($r) => $r->pregunta->puntos);
                    @endphp

                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div class="fw-bold">
                                👤 {{ $alumno?->nombre ?? 'Estudiante' }}
                            </div>
                            <div>
                            <span class="badge rounded-pill bg-primary fs-6">
                                {{ number_format($notaTotal, 1) }}/{{ $notaMaxima }} pts
                            </span>
                            </div>
                        </div>
                        <div class="card-body bg-white">
                            @foreach($respuestasAlumno as $respuesta)
                                <div class="mb-4 pb-3 border-bottom">
                                    <p class="fw-semibold mb-1">
                                        {{ $respuesta->pregunta->enunciado }}
                                        <span class="badge bg-secondary ms-2">{{ $respuesta->pregunta->tipo }}</span>
                                    </p>

                                    @if($respuesta->pregunta->tipo === 'test')
                                        <div class="mt-1">
                                            <strong class="text-muted">Respuesta marcada:</strong>
                                            <div class="border p-2 rounded mt-1
                                            @if($respuesta->respuesta?->es_correcta) bg-success text-white
                                            @elseif($respuesta->respuesta_id) bg-danger text-white
                                            @else bg-light text-muted @endif">
                                                {{ $respuesta->respuesta?->texto ?? 'Sin respuesta' }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-1">
                                            <strong class="text-muted">Respuesta escrita:</strong>
                                            <div class="border p-2 rounded bg-light mt-1">
                                                {{ $respuesta->respuesta_texto ?? 'Sin respuesta' }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        <label class="form-label fw-semibold">Nota (máx. {{ $respuesta->pregunta->puntos }} pts)</label>
                                        <input type="number" name="notas[{{ $respuesta->id }}]"
                                               class="form-control" min="0" step="0.1" max="{{ $respuesta->pregunta->puntos }}"
                                               value="{{ $respuesta->nota }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="text-end">
                    <button class="btn btn-success px-4">
                        <i class="bi bi-check2-square me-1"></i> Guardar todas las notas
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection
