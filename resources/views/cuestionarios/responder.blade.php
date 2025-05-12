@extends('layouts.app')

@section('title', 'Resultado del cuestionario')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">Resultado: {{ $tarea->titulo }}</h2>

        @foreach ($tarea->preguntas as $pregunta)
            @php
                $respuestaEst = $respuestasEstudiante[$pregunta->id] ?? null;
            @endphp

            <div class="mb-4">
                <strong>{{ $loop->iteration }}. {{ $pregunta->enunciado }}</strong>
                <div class="mt-2">
                    @foreach ($pregunta->respuestas as $respuesta)
                        @php
                            $esSeleccionada = $respuestaEst && $respuestaEst->respuesta_id == $respuesta->id;
                            $esCorrecta = $respuesta->es_correcta;
                            $clase = $esCorrecta ? 'text-success' : ($esSeleccionada ? 'text-danger' : '');
                        @endphp

                        <div class="form-check {{ $clase }}">
                            <input class="form-check-input"
                                   type="radio"
                                   disabled
                                   @if($esSeleccionada) checked @endif>
                            <label class="form-check-label">
                                {{ $respuesta->texto }}
                                @if($esCorrecta)
                                    <span class="badge bg-success ms-2">Correcta</span>
                                @elseif($esSeleccionada)
                                    <span class="badge bg-danger ms-2">Incorrecta</span>
                                @endif
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
