@extends('layouts.app')

@section('title', 'Resultado del cuestionario')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">{{ $tarea->titulo }}</h2>

        <div class="alert alert-info">
            Has respondido <strong>{{ $total }}</strong> preguntas.<br>
            Acertaste <strong>{{ $correctas }}</strong> de ellas. <br>
            <strong>Resultado:</strong> {{ $porcentaje }}%
            <br>

            @php
                $mensaje = match (true) {
                    $porcentaje === 100 => '¡Excelente! Has acertado todo. ¡Sigue así! 🎉',
                    $porcentaje >= 80 => '¡Muy bien! Estás dominando el tema. 💪',
                    $porcentaje >= 60 => 'Buen trabajo, pero aún puedes mejorar. 🔄',
                    $porcentaje >= 40 => 'Ánimo, repasa un poco más y lo lograrás. 📚',
                    default => 'No te preocupes, ¡sigue practicando! 🚀',
                };
            @endphp
            <strong>{{ $mensaje }}</strong>


        </div>

        <hr>

        <h4 class="mb-3">Detalle de respuestas</h4>

        @foreach($tarea->preguntas as $pregunta)
            @php
                $respuestaEstudiante = $respuestasEstudiante[$pregunta->id] ?? null;
                $respuestaSeleccionada = $respuestaEstudiante?->respuesta;
            @endphp

            <div class="mb-4 p-3 border rounded">
                <strong>{{ $loop->iteration }}. {{ $pregunta->enunciado }}</strong>

                <ul class="list-group mt-2">
                    @foreach($pregunta->respuestas as $respuesta)
                        @php
                            $esSeleccionada = $respuestaSeleccionada && $respuesta->id === $respuestaSeleccionada->id;
                            $esCorrecta = $respuesta->es_correcta;
                        @endphp

                        <li class="list-group-item d-flex justify-content-between align-items-center
                            @if($esCorrecta) list-group-item-success @elseif($esSeleccionada) list-group-item-danger @endif
                        ">
                            {{ $respuesta->texto }}

                            @if($esCorrecta)
                                <span class="badge bg-success">Correcta</span>
                            @elseif($esSeleccionada)
                                <span class="badge bg-danger">Tu respuesta</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        <div class="my-5">
            <h5 class="mb-3 text-center">Resumen visual</h5>
            <div class="text-left" style="max-width: 250px; margin: 0 auto;">
                <canvas id="graficoResultados" width="200" height="200"></canvas>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('tareas.ver.estudiante', $tarea) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a la tarea
            </a>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('graficoResultados');

            if (ctx && window.Chart) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Correctas', 'Incorrectas'],
                        datasets: [{
                            label: 'Resultados',
                            data: [{{ $correctas }}, {{ $total - $correctas }}],
                            backgroundColor: ['#198754', '#dc3545'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.raw + ' respuestas';
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                console.warn('Chart.js no está disponible o canvas no encontrado.');
            }
        });
    </script>
@endpush
