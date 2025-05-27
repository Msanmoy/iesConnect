@extends('layouts.app')

@section('title', 'Responder cuestionario')

@section('content')
    <div class="container-xl py-4">
        <h2 class="mb-4">📝 Cuestionario: {{ $tarea->titulo }}</h2>

        <form method="POST" action="{{ route('cuestionarios.responder.guardar', $tarea) }}">
            @csrf

            @forelse($preguntas as $i => $pregunta)
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">{{ $i + 1 }}. {{ $pregunta->enunciado }} <span class="text-muted">({{ $pregunta->puntos }} pts)</span></h5>

                        @if($pregunta->tipo === 'test')
                            @foreach($pregunta->respuestas as $respuesta)
                                <div class="form-check">
                                    <input type="radio"
                                           class="form-check-input"
                                           name="respuestas[{{ $pregunta->id }}]"
                                           value="{{ $respuesta->id }}"
                                           id="respuesta_{{ $respuesta->id }}"
                                           required>
                                    <label class="form-check-label" for="respuesta_{{ $respuesta->id }}">
                                        {{ $respuesta->texto }}
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <textarea name="respuestas_abiertas[{{ $pregunta->id }}]"
                                      class="form-control"
                                      rows="4"
                                      required
                                      placeholder="Escribe tu respuesta aquí..."></textarea>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info">No hay preguntas disponibles.</div>
            @endforelse

            <div class="text-end">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-send-check me-1"></i> Enviar respuestas
                </button>
            </div>
        </form>
    </div>
@endsection
