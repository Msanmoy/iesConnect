@extends('layouts.app')

@section('title', 'Mis Tareas')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">Tareas de mis clases</h2>

        @forelse ($tareas as $tarea)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h4>{{ $tarea->titulo }}</h4>
                    <p class="text-muted">{{ $tarea->asignatura->nombre }}</p>
                    <p>{{ Str::limit($tarea->descripcion, 150) }}</p>
                    <p><strong>Fecha límite:</strong> {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}</p>

                    <div class="d-flex gap-2">
                        <a href="{{ route('tareas.show', $tarea) }}" class="btn btn-outline-primary btn-sm">
                            Ver entregas
                        </a>
                        <a href="{{ route('tareas.edit', $tarea) }}" class="btn btn-outline-secondary btn-sm">
                            Editar
                        </a>
                        <form action="{{ route('tareas.destroy', $tarea) }}" method="POST" onsubmit="return confirm('¿Eliminar esta tarea?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No has creado tareas aún.</div>
        @endforelse

        <div class="mt-4">
            <a href="{{ route('tareas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Crear nueva tarea
            </a>
        </div>
    </div>
@endsection
