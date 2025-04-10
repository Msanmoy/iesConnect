@extends('layouts.app')

@section('title', 'Mis Asignaturas')

@section('content')
    <!-- Botón solo visible en esta vista -->
    @push('header-actions')
        <button class="btn btn-outline-primary me-3" data-bs-toggle="modal" data-bs-target="#anadirAsignatura">
            <i class="bi bi-journal-plus me-1"></i> Unirse a una asignatura
        </button>
    @endpush

    <!-- Modal Añadir Asignatura -->
    <div class="modal fade" id="anadirAsignatura" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('asignaturas.unirse') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLabel">Unirse a Asignatura</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <label for="codigoClase" class="form-label">Código de Asignatura</label>
                    <input type="text" id="codigoClase" name="codigo_clase" class="form-control"
                           placeholder="Código de Asignatura" required>

                    <p class="mt-3 text-muted">
                        Para unirte a una asignatura:<br>
                        • Usa una cuenta válida.<br>
                        • Introduce el código proporcionado por tu profesor.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn border-black" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Unirme</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Vista principal de asignaturas -->
    <main class="d-flex justify-content-center mt-4">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 container-xxl">
            @forelse ($asignaturas as $asignatura)
                <div class="col">
                    <div class="card h-100">
                        <a href="{{ route('asignaturas.asignatura', ['slug' => $asignatura->slug]) }}" class="text-decoration-none">
                            <img src="{{ asset('images/' . $asignatura->imagen) }}"
                                 class="card-img-top w-100 object-fit-cover"
                                 alt="{{ $asignatura->nombre }}"
                                 style="height: 180px;">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $asignatura->nombre }}</h5>

                            <p class="card-text m-0">Tareas Pendientes:</p>
                            <ul class="list-unstyled text-info">
                                @forelse ($asignatura->tareas ?? [] as $tarea)
                                    <li>
                                        <a href="{{ route('asignaturas.tarea', ['id' => $tarea->id]) }}" class="text-decoration-none">
                                            {{ $tarea->titulo }}
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-muted">Sin tareas</li>
                                @endforelse
                            </ul>

                            @if(auth()->user()->rol === 'PROFESOR')
                                <a href="{{ route('tareas.create', ['asignatura_id' => $asignatura->id]) }}"
                                   class="btn btn-outline-primary btn-sm mt-auto">
                                    <i class="bi bi-plus-circle me-1"></i> Crear tarea
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center col-12">
                    <p class="text-muted">No estás inscrito en ninguna asignatura.</p>
                </div>
            @endforelse
        </div>
    </main>
@endsection
