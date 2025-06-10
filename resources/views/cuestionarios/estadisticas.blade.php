@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('title', 'Estadísticas del cuestionario')

@php
    /*
    |==========================================================================
    |   PREPARAR DATOS PARA CHART.JS EN UNA SOLA ESTRUCTURA ─ stats
    |==========================================================================*/
    $porNivel = collect($resumen)->groupBy(fn($p) => $p['nivel'] ?? 'Sin nivel')->sortKeys();

    $stats = [
        'rendimiento' => [
            'labels' => $notasAlumnos->pluck('usuario')->values(),
            'notas'  => $notasAlumnos->pluck('nota')->values(),
        ],
        'niveles' => $porNivel->mapWithKeys(function ($preguntas, $nivel) {
            $slug = Str::slug($nivel);
            return [
                $slug => [
                    'labels'       => collect($preguntas)->pluck('pregunta')->values(),
                    'porcentajes'  => collect($preguntas)->pluck('porcentaje')->values(),
                    'preguntas'    => collect($preguntas)->map(function ($p) {
                        return [
                            'etiquetas' => $p['respuestas']->pluck('texto')->values(),
                            'conteos'   => $p['respuestas']->pluck('conteo')->values(),
                            'colores'   => $p['respuestas']->map(fn($r) => $r['correcta'] ? 'rgba(40,167,69,0.6)' : 'rgba(220,53,69,0.6)')->values(),
                        ];
                    })->values(),
                ],
            ];
        })->toArray(),
    ];
@endphp

@section('content')
    <div class="container-xl py-4">
        <h2 class="mb-4">📊 Estadísticas del cuestionario: {{ $tarea->titulo }}</h2>

        @if($notasAlumnos->isEmpty())
            <div class="alert alert-warning">Todavía no hay respuestas para este cuestionario.</div>
        @else
            <div class="row g-4 mb-5">
                <div class="col-lg-7">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent border-0">
                            <h4 class="mb-0">📈 Rendimiento general del alumnado</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoRendimiento" height="110"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent border-0">
                            <h4 class="mb-0">📋 Detalle de notas</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0 align-middle">
                                    <thead class="table-light text-center">
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>Estudiante</th>
                                        <th style="width:120px">Nota</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    @foreach ($notasAlumnos as $i => $alumno)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td class="text-start">{{ $alumno['usuario'] }}</td>
                                            <td class="fw-semibold">{{ number_format($alumno['nota'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-pills mb-4" id="nivelTabs" role="tablist">
                @foreach($porNivel as $nivel => $preguntasNivel)
                    @php $slug = Str::slug($nivel); @endphp
                    <li class="nav-item" role="presentation">
                        <button class="bg-white nav-link @if($loop->first) active @endif" data-slug="{{ $slug }}" id="tab-{{ $slug }}" data-bs-toggle="pill" data-bs-target="#panel-{{ $slug }}" type="button" role="tab" aria-controls="panel-{{ $slug }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        </button>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content" id="nivelTabsContent">
                @foreach($porNivel as $nivel => $preguntasNivel)
                    @php $slug = Str::slug($nivel); @endphp
                    <div class="tab-pane fade @if($loop->first) show active @endif" id="panel-{{ $slug }}" role="tabpanel" aria-labelledby="tab-{{ $slug }}">
                        <h4 class="mb-3">📌 Detalles</h4>
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <canvas id="chartNivel{{ $slug }}" height="110"></canvas>
                            </div>
                        </div>

                        @foreach ($preguntasNivel as $idx => $pregunta)
                            <div class="card mb-4 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $pregunta['pregunta'] }}</h5>
                                    <canvas id="chartPregunta{{ $slug }}{{ $idx }}" height="100"></canvas>
                                    <p class="mt-2 text-muted mb-0">Total respuestas: {{ $pregunta['total'] }} | Correctas: {{ $pregunta['correctas'] }} ({{ $pregunta['porcentaje'] }}%)</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-5">
            <a href="{{ route('asignaturas.show', $tarea->asignatura->slug) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Volver a tareas
            </a>
            <a href="{{ route('cuestionarios.build', $tarea) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i> Editar tarea
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stats = @json($stats);
            const charts = {};

            charts.global = new Chart(document.getElementById('graficoRendimiento'), {
                type: 'bar',
                data: {
                    labels: stats.rendimiento.labels,
                    datasets: [{
                        label: 'Nota',
                        data: stats.rendimiento.notas,
                        backgroundColor: 'rgba(54,162,235,0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Nota' }, ticks: { stepSize: 1 } },
                        x: { title: { display: true, text: 'Estudiante' } }
                    }
                }
            });

            const initNivelCharts = (slug) => {
                if (charts[slug]) return; // ya creado

                const nivel = stats.niveles[slug];
                if (!nivel) return;

                charts[slug] = {};
                const ctxNivel = document.getElementById(`chartNivel${slug}`);
                charts[slug].resumen = new Chart(ctxNivel, {
                    type: 'bar',
                    data: {
                        labels: nivel.labels,
                        datasets: [{
                            label: '% Correctas',
                            data: nivel.porcentajes,
                            backgroundColor: 'rgba(40,167,69,0.6)'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { tooltip: { callbacks: { label: ctx => `${ctx.raw}%` } } },
                        scales: {
                            y: { beginAtZero: true, max: 100, title: { display: true, text: '% Correctas' } }
                        }
                    }
                });

                nivel.preguntas.forEach((p, idx) => {
                    charts[slug][idx] = new Chart(document.getElementById(`chartPregunta${slug}${idx}`), {
                        type: 'bar',
                        data: {
                            labels: p.etiquetas,
                            datasets: [{
                                label: 'Veces seleccionada',
                                data: p.conteos,
                                backgroundColor: p.colores,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, title: { display: true, text: 'Nº de respuestas' }, ticks: { stepSize: 1 } }
                            }
                        }
                    });
                });
            };

            const firstSlug = document.querySelector('#nivelTabs .nav-link.active')?.dataset.slug;
            if (firstSlug) initNivelCharts(firstSlug);

            document.querySelectorAll('#nivelTabs .nav-link').forEach(btn => {
                btn.addEventListener('shown.bs.tab', e => {
                    initNivelCharts(e.target.dataset.slug);
                });
            });
        });
    </script>
@endpush
