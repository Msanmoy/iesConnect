@extends('layouts.app')

@section('title', 'Resultados del cuestionario')

@section('content')
    <div class="container-xl py-4">
        <h2 class="mb-4">🎯 Resultados: {{ $tarea->titulo }}</h2>

        <div class="alert alert-info shadow-sm">
            Has obtenido <strong>{{ number_format($total, 2) }}</strong> puntos de
            <strong>{{ $maximo }}</strong>.
            ({{ round(($total / max($maximo, 1)) * 100) }}%)
        </div>

        @php
            /**
             * Agrupar preguntas por nivel
             */
            $porNivel = collect($resultado)
                ->groupBy(fn($item) => $item['pregunta']->nivel ?? 'Sin nivel')
                ->sortKeys();
        @endphp

        <div class="accordion accordion-flush" id="resultadoPorNivel">
            @foreach($porNivel as $nivel => $items)
                @php
                    $respondido = $items->contains(fn($it) => !is_null($it['respuesta_estudiante']));
                @endphp
                @continue(!$respondido)

                <div class="accordion-item shadow-sm mb-3">
                    <h2 class="accordion-header" id="heading-{{ Str::slug($nivel) }}">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ Str::slug($nivel) }}" aria-expanded="false"
                                aria-controls="collapse-{{ Str::slug($nivel) }}">
                            Nivel {{ $nivel }}
                        </button>
                    </h2>
                    <div id="collapse-{{ Str::slug($nivel) }}" class="accordion-collapse collapse"
                         aria-labelledby="heading-{{ Str::slug($nivel) }}" data-bs-parent="#resultadoPorNivel">
                        <div class="accordion-body">
                            @foreach($items as $i => $item)
                                <div class="card mb-3 border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="mb-3">
                                            {{ $i + 1 }}. {{ $item['pregunta']->enunciado }}
                                            <span class="text-muted">({{ $item['pregunta']->puntos }} pts)</span>
                                        </h5>

                                        @if($item['pregunta']->tipo === 'test')
                                            <ul class="list-group list-group-flush rounded">
                                                @foreach($item['pregunta']->respuestas as $respuesta)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center
                                                        @class([
                                                            'list-group-item-success' => $respuesta->es_correcta,
                                                            'list-group-item-danger' => !$respuesta->es_correcta &&
                                                                $item['respuesta_estudiante']?->respuesta_id === $respuesta->id,
                                                        ])">
                                                        <span>{{ $respuesta->texto }}</span>

                                                        @if($respuesta->es_correcta)
                                                            <span class="badge bg-success">Correcta</span>
                                                        @elseif($item['respuesta_estudiante']?->respuesta_id === $respuesta->id)
                                                            <span class="badge bg-danger">Tu respuesta</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="fw-semibold">Tu respuesta:</p>
                                            <div class="border p-3 bg-light rounded">
                                                {{ $item['respuesta_estudiante']?->respuesta_texto ?? 'No respondida' }}
                                            </div>
                                            <p class="mt-3 mb-0">
                                                <strong>Nota:</strong>
                                                {{ $item['nota'] !== null ? $item['nota'] . ' puntos' : 'Pendiente de corrección' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('asignaturas.show', $tarea->asignatura->slug) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i>
                Volver a tareas
            </a>
        </div>
    </div>
@endsection
