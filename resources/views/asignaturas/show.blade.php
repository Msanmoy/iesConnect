@extends('layouts.app')

@section('title', $asignatura->nombre)

@section('content')
    <div class="container mt-4">
        <!-- Imagen tipo banner -->
        <div class="mb-4" style="height: 200px; background: url('{{ asset('images/' . strtolower($asignatura->nombre) . '.jpg') }}') center/cover; border-radius: 10px;"></div>

        <!-- Nombre de la asignatura -->
        <h1 class="mb-4">{{ $asignatura->nombre }}</h1>

        <!-- Recursos (si los hay) -->
        @if ($asignatura->recursos->count())
            <div class="mb-4">
                <h5>Recursos de la clase</h5>
                <ul class="list-group">
                    @foreach ($asignatura->recursos as $recurso)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $recurso->nombre }}</span>
                            <a href="{{ asset('storage/' . $recurso->archivo) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                Ver
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tareas -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Tareas Pendientes</h5>

                @forelse ($asignatura->tareas as $tarea)
                    <div class="border-bottom py-3">
                        <h6>
                            <a href="{{ route('asignaturas.tarea', ['id' => $tarea->id]) }}" class="text-decoration-none">
                                {{ $tarea->titulo }}
                            </a>
                        </h6>

                        <!-- Fases -->
                        @if ($tarea->fases->count())
                            <ul class="list-unstyled ms-3">
                                @foreach ($tarea->fases as $fase)
                                    <li class="text-muted">– {{ $fase->titulo }} (hasta {{ \Carbon\Carbon::parse($fase->fecha_entrega)->format('d/m/Y') }})</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @empty
                    <p class="text-muted">No hay tareas pendientes.</p>
                @endforelse
            </div>
        </div>

        @if(auth()->user()->rol === 'PROFESOR')
            <a href="{{ route('tareas.create', ['asignatura_id' => $asignatura->id]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Crear nueva tarea
            </a>
        @endif
    </div>
@endsection
