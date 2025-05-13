@extends('layouts.app')

@section('title', 'Cuestionario: ' . $tarea->titulo)

@section('content')
    <div class="container-xl">
        <h2 class="fw-bold mb-4"><i class="bi bi-ui-checks-grid text-primary me-2"></i>Cuestionario – {{ $tarea->titulo }}</h2>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4" id="tabsCuestionario" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="respuestas-tab" data-bs-toggle="tab" data-bs-target="#respuestas" type="button" role="tab">
                    Respuestas del alumnado
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="estadisticas-tab" data-bs-toggle="tab" data-bs-target="#estadisticas" type="button" role="tab">
                    Estadísticas
                </button>
            </li>
        </ul>

        <div class="tab-content" id="tabsCuestionarioContent">
            {{-- TAB: RESPUESTAS DEL ALUMNADO --}}
            <div class="tab-pane fade show active" id="respuestas" role="tabpanel">
                @if ($respuestasAlumnos->isEmpty())
                    <div class="alert alert-warning">No hay respuestas registradas para este cuestionario.</div>
                @else
                    <form action="{{ route('cuestionarios.actualizarNotas', $tarea) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Nota actual</th>
                                    <th>Nueva nota</th>
                                    <th>Última entrega</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($respuestasAlumnos as $respuesta)
                                    <tr>
                                        <td>{{ $respuesta->estudiante->nombre }}</td>
                                        <td>{{ $respuesta->nota ?? '—' }}</td>
                                        <td style="max-width: 100px;">
                                            <input type="number" step="0.1" min="0" max="10"
                                                   name="notas[{{ $respuesta->id }}]"
                                                   class="form-control form-control-sm"
                                                   value="{{ $respuesta->nota }}">
                                        </td>
                                        <td>{{ $respuesta->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end mt-3">
                            <button class="btn btn-success">
                                <i class="bi bi-floppy me-1"></i> Guardar cambios
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            {{-- TAB: ESTADÍSTICAS --}}
            <div class="tab-pane fade" id="estadisticas" role="tabpanel">
                <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                    <div class="col">
                        <div class="card shadow-sm border-0 rounded-4 p-3 text-center">
                            <i class="bi bi-people-fill fs-1 text-secondary mb-2"></i>
                            <p class="mb-1 text-muted">Estudiantes asignados</p>
                            <h4 class="fw-bold">{{ $totalEstudiantes }}</h4>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow-sm border-0 rounded-4 p-3 text-center">
                            <i class="bi bi-check-circle-fill fs-1 text-success mb-2"></i>
                            <p class="mb-1 text-muted">Han respondido</p>
                            <h4 class="fw-bold">{{ $respondieron }}</h4>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow-sm border-0 rounded-4 p-3 text-center">
                            <i class="bi bi-graph-up-arrow fs-1 text-primary mb-2"></i>
                            <p class="mb-1 text-muted">Participación</p>
                            <h4 class="fw-bold">
                                {{ $totalEstudiantes > 0 ? round(($respondieron / $totalEstudiantes) * 100) : 0 }}%
                            </h4>
                        </div>
                    </div>
                </div>

                <h4 class="fw-semibold mb-4">📊 Desglose por pregunta</h4>

                @foreach ($estadisticasPreguntas as $i => $stat)
                    <div class="mb-5 p-4 bg-light rounded shadow-sm">
                        <strong class="d-block mb-3 text-dark">{{ $loop->iteration }}. {{ $stat['pregunta'] }}</strong>
                        <canvas id="graficoPregunta{{ $i }}" height="100" style="max-width: 100%;"></canvas>
                    </div>
                @endforeach
            </div>
        </div>
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
                        label: 'Respuestas',
                        data: {!! json_encode(collect($stat['respuestas'])->pluck('conteo')) !!},
                        backgroundColor: {!! json_encode(collect($stat['respuestas'])->map(fn($r) => $r['correcta'] ? '#198754' : '#0d6efd')->values()) !!},
                        borderRadius: 5,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
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
                                    size: 14,
                                    family: "'Inter', sans-serif"
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 14,
                                    family: "'Inter', sans-serif"
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
                                size: 14,
                                family: "'Inter', sans-serif"
                            },
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 14
                            }
                        }
                    }
                }
            });
            @endforeach
        });
    </script>
@endpush
