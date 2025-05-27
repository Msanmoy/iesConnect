@extends('layouts.app')

@section('title', 'Estadísticas del cuestionario')

@section('content')

    <div class="container-xl py-4">
        <h4 class="mt-5">📈 Rendimiento general del alumnado</h4>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <canvas id="graficoRendimiento" height="120"></canvas>
            </div>
        </div>

        <div class="card shadow-sm mb-5">
            <div class="card-body table-responsive">
                <h5 class="mb-3">📋 Detalle de notas</h5>
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Estudiante</th>
                        <th class="text-end">Nota</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($notasAlumnos as $i => $alumno)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $alumno['usuario'] }}</td>
                            <td class="text-end">{{ number_format($alumno['nota'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">Ningún alumno ha respondido todavía.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('asignaturas.show', $tarea->asignatura->slug) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Volver a tareas
                </a>
            </div>
            <div>
                <a href="{{ route('cuestionarios.build', $tarea) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square me-1"></i> Editar tarea
                </a>
            </div>
        </div>


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

            const rendimiento = document.getElementById('graficoRendimiento');
            new Chart(rendimiento, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($notasAlumnos->pluck('usuario')) !!},
                    datasets: [{
                        label: 'Nota obtenida',
                        data: {!! json_encode($notasAlumnos->pluck('nota')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Nota' },
                            ticks: { stepSize: 1 }
                        },
                        x: {
                            title: { display: true, text: 'Estudiante' }
                        }
                    }
                }
            });

        });
    </script>
@endpush
