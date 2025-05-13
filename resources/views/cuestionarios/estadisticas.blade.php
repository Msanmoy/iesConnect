@extends('layouts.app')

@section('title', 'Estadísticas del cuestionario')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">Estadísticas de: {{ $tarea->titulo }}</h2>

        <div class="mb-3">
            <p><strong>Total estudiantes asignados:</strong> {{ $totalEstudiantes }}</p>
            <p><strong>Han respondido:</strong> {{ $respondieron }}</p>
            <p><strong>Participación:</strong> {{ $totalEstudiantes > 0 ? round(($respondieron / $totalEstudiantes) * 100) : 0 }}%</p>
        </div>

        <hr>

        <h4 class="mt-4">Desglose por pregunta</h4>

        @foreach ($estadisticasPreguntas as $i => $stat)
            <div class="mb-4">
                <strong>{{ $loop->iteration }}. {{ $stat['pregunta'] }}</strong>
                <canvas id="graficoPregunta{{ $i }}" width="400" height="100" style="max-width: 100%;"></canvas>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @foreach ($estadisticasPreguntas as $i => $stat)
            const ctx{{ $i }} = document.getElementById('graficoPregunta{{ $i }}').getContext('2d');

            new Chart(ctx{{ $i }}, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($stat['respuestas']->pluck('texto')) !!},
                    datasets: [{
                        labels: {!! json_encode(collect($stat['respuestas'])->pluck('texto')) !!},
                        data: {!! json_encode(collect($stat['respuestas'])->pluck('conteo')) !!},
                        backgroundColor: {!! json_encode(collect($stat['respuestas'])->map(fn($r) => $r['correcta'] ? '#198754' : '#0d6efd')->values()) !!}

                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 16
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 16
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            bodyFont: {
                                size: 16
                            }
                        }
                    }
                }
            });
            @endforeach
        });
    </script>
@endpush
