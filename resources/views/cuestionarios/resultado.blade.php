@extends('layouts.app')

@section('title', 'Resultados del cuestionario')

@section('content')
    <div class="container-xl py-4">
        <h2 class="mb-4">🎯 Resultados: {{ $tarea->titulo }}</h2>

        <div class="alert alert-info">
            Has obtenido <strong>{{ number_format($total, 2) }}</strong> puntos de <strong>{{ $maximo }}</strong>.
            ({{ round(($total / max($maximo, 1)) * 100) }}%)
        </div>

        @foreach($resultado as $i => $item)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">{{ $i + 1 }}. {{ $item['pregunta']->enunciado }} <span class="text-muted">({{ $item['pregunta']->puntos }} pts)</span></h5>

                    @if($item['pregunta']->tipo === 'test')
                        <ul class="list-group">
                            @foreach($item['pregunta']->respuestas as $respuesta)
                                <li class="list-group-item
                                @if($respuesta->es_correcta) list-group-item-success
                                @elseif($item['respuesta_estudiante']?->respuesta_id === $respuesta->id) list-group-item-danger
                                @endif">
                                    {{ $respuesta->texto }}
                                    @if($respuesta->es_correcta)
                                        <span class="badge bg-success float-end">Correcta</span>
                                    @elseif($item['respuesta_estudiante']?->respuesta_id === $respuesta->id)
                                        <span class="badge bg-danger float-end">Tu respuesta</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p><strong>Tu respuesta:</strong></p>
                        <div class="border p-2 bg-light rounded">
                            {{ $item['respuesta_estudiante']?->respuesta_texto ?? 'No respondida' }}
                        </div>
                        <p class="mt-2">
                            <strong>Nota:</strong>
                            {{ $item['nota'] !== null ? $item['nota'] . ' puntos' : 'Pendiente de corrección' }}
                        </p>
                    @endif
                </div>
            </div>
        @endforeach

        <a href="{{ route('asignaturas.show', $tarea->asignatura->slug) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Volver a tareas
        </a>
    </div>
@endsection
