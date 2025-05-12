@extends('layouts.app')

@section('title', 'Responder cuestionario')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">{{ $tarea->titulo }}</h2>

        <p class="text-muted">{{ $tarea->descripcion }}</p>

        <form action="{{ route('cuestionarios.responder.guardar', $tarea) }}" method="POST">
            @csrf

            @foreach($tarea->preguntas as $pregunta)
                <div class="mb-4">
                    <strong>{{ $loop->iteration }}. {{ $pregunta->enunciado }}</strong>

                    <div class="mt-2">
                        @foreach($pregunta->respuestas as $respuesta)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="respuestas[{{ $pregunta->id }}]"
                                    id="respuesta_{{ $respuesta->id }}"
                                    value="{{ $respuesta->id }}"
                                    required
                                >
                                <label class="form-check-label" for="respuesta_{{ $respuesta->id }}">
                                    {{ $respuesta->texto }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> Enviar respuestas
                </button>
            </div>
        </form>
    </div>
@endsection
