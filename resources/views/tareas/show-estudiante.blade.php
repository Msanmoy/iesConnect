@extends('layouts.app')

@section('title', $tarea->titulo)

@section('content')
    <div class="container-xl">
        @php
            $esGenerica = $tarea->archivos->whereNull('nivel')->isNotEmpty();
            $nivel = $progreso->nivel_asignado->value ?? null;

            if (!$esGenerica) {
                $puedeEntregar = match($nivel) {
                    'sencillo' => !$progreso->entregado_sencillo,
                    'intermedio' => !$progreso->entregado_intermedio,
                    'avanzado' => !$progreso->entregado_avanzado,
                    default => false
                };
            } else {
                $puedeEntregar = !$progreso->entregas->count();
            }
        @endphp

        <h2 class="fw-bold mb-3">{{ $tarea->titulo }}</h2>

        <p class="text-muted">{{ $tarea->descripcion }}</p>

        <div class="mb-4">
            <span class="badge bg-light text-dark me-2">
                <i class="bi bi-calendar-event me-1"></i>
                Fecha límite: {{ optional($tarea->fecha_limite)->format('d/m/Y') ?? 'No especificada' }}
            </span>
            <span class="badge bg-primary-subtle text-primary">{{ ucfirst($tarea->tipo) }}</span>
        </div>

        {{-- Nivel actual --}}
        @if (!$esGenerica && $progreso && $tarea->tipo !== 'cuestionario')
            <h5 class="mb-3">Tu nivel actual:
                <span class="badge bg-primary text-uppercase">{{ ucfirst($nivel) }}</span>
            </h5>
        @endif

        {{-- Archivos del profesor --}}
        @if ($tarea->archivos->count())
            <div class="mb-4">
                <h5 class="fw-semibold mb-3">
                    <i class="bi bi-folder me-1"></i>
                    Archivos del profesor
                </h5>

                <ul class="list-group">
                    @foreach ($tarea->archivos as $archivo)
                        @if ($esGenerica || ($progreso && $archivo->nivel === $nivel))
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $archivo->nombre_archivo }}</span>
                                <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                    Ver
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Cuestionario --}}
        @if ($tarea->tipo === 'cuestionario')
            <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm">
                <span><i class="bi bi-info-circle me-2"></i>Esta tarea es un cuestionario. Puedes comenzarlo ahora.</span>
                <a href="{{ route('cuestionarios.responder', $tarea) }}" class="btn btn-primary">
                    <i class="bi bi-play-circle me-1"></i> Comenzar
                </a>
            </div>
            <hr>
        @else
            {{-- Entrega --}}
            <h5 class="fw-semibold mb-3">📤 Tu entrega</h5>

            @if ($puedeEntregar)
                <form action="{{ route('entregas.store', $progreso) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4 mb-4 border-0">
                    @csrf
                    @if (!$esGenerica)
                        <input type="hidden" name="nivel" value="{{ $nivel }}">
                    @endif

                    <div class="mb-3">
                        <label for="archivo" class="form-label">Archivo</label>
                        <input type="file" name="archivo" id="archivo" class="form-control" required>
                        <small class="form-text text-muted">Formatos aceptados: PDF, imágenes, documentos (máx. 20MB)</small>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i> Subir entrega{{ $esGenerica ? '' : ' (' . ucfirst($nivel) . ')' }}
                        </button>
                    </div>
                </form>
            @else
                <div class="alert alert-success shadow-sm">
                    Ya has entregado{{ $esGenerica ? '' : ' el trabajo para el nivel ' . ucfirst($nivel) }}.
                </div>
            @endif

            {{-- Historial de entregas --}}
            @if ($progreso->entregas->count())
                <h5 class="fw-semibold mb-3">📁 Historial de entregas</h5>
                <div class="row g-3">
                    @foreach ($progreso->entregas as $entrega)
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong class="text-primary">
                                            {{ $esGenerica ? 'Entrega' : ucfirst($entrega->nivel) }}
                                        </strong>
                                        <span class="badge {{ $entrega->nota !== null ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $entrega->nota !== null ? "Nota: $entrega->nota" : 'Sin calificar' }}
                                        </span>
                                    </div>

                                    <p class="text-muted mb-1">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $entrega->fecha_entrega->format('d/m/Y H:i') }}
                                    </p>

                                    <a href="{{ asset('storage/' . $entrega->archivo) }}" class="btn btn-outline-primary btn-sm mb-2" target="_blank">
                                        <i class="bi bi-file-earmark-arrow-down me-1"></i> Ver entrega
                                    </a>

                                    <div class="text-muted small">
                                        @if ($entrega->comentario)
                                            <i class="bi bi-chat-left-text me-1"></i> {{ $entrega->comentario }}
                                        @else
                                            <i class="bi bi-hourglass-split me-1"></i> En espera de corrección
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-5 d-flex justify-content-between">
            <a href="{{ route('asignaturas.show', $tarea->asignatura->slug) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a mis tareas
            </a>
            @if($tarea->tipo === 'cuestionario')
            <a href="{{ route('cuestionarios.resultado', $tarea->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right me-1"></i> Ver resultados
            </a>
            @endif
        </div>
    </div>
@endsection
