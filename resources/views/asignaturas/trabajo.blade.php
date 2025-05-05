@extends('layouts.app')

@section('title', 'Trabajo de clase - ' . $asignatura->nombre)

@section('content')
    <div class="container-xl mt-4">

        @include('asignaturas.partials.navegacion', ['asignatura' => $asignatura])
        @include('asignaturas.partials.banner', ['asignatura' => $asignatura])

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Tareas asignadas</h4>

            @if(auth()->user()->rol === 'PROFESOR')
                <a href="{{ route('tareas.create', ['asignatura_id' => $asignatura->id]) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nueva tarea
                </a>
            @endif
        </div>

        @forelse($asignatura->tareas as $tarea)
            <div class="card mb-3 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $tarea->titulo }}</h5>
                        <small class="text-muted">Fecha límite: {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}</small>
                    </div>

                    <div>
                        @if(auth()->user()->rol === 'ESTUDIANTE')
                            <a href="{{ route('tareas.ver.estudiante', $tarea) }}" class="btn btn-sm btn-primary">Ver tarea</a>
                        @else
                            <a href="{{ route('tareas.show', $tarea) }}" class="btn btn-sm btn-outline-primary">Detalles</a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-light text-muted">
                No hay tareas asignadas aún.
            </div>
        @endforelse
    </div>
@endsection
