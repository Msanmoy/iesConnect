@extends('layouts.app')

@section('title', 'Estadísticas del cuestionario')

@section('content')
    <div class="container-xl py-4">
        <h2 class="mb-4">📊 Estadísticas del cuestionario: {{ $tarea->titulo }}</h2>

        @foreach ($resumen as $index => $pregunta)
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $index + 1 }}. {{ $pregunta['pregunta'] }}</h5>
                    <canvas id="chartPregunta{{ $index }}" height="100"></canvas>
                    <p class="mt-2 text-muted">Total respuestas: {{ $pregunta['total'] }} | Correctas: {{ $pregunta['correctas'] }} ({{ $pregunta['porcentaje'] }}%)</p>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @foreach ($resumen as $index => $pregunta)
            new Chart(document.getElementById('chartPregunta{{ $index }}'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($pregunta['respuestas']->pluck('texto')) !!},
                    datasets: [{
                        label: 'Veces seleccionada',
                        data: {!! json_encode($pregunta['respuestas']->pluck('conteo')) !!},
                        backgroundColor: {!! json_encode(
                    $pregunta['respuestas']->map(fn($r) => $r['correcta'] ? 'rgba(40, 167, 69, 0.6)' : 'rgba(220, 53, 69, 0.6)')
                ) !!}
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.label}: ${ctx.raw}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Nº de respuestas' },
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
            @endforeach
        });
    </script>
@endpush
