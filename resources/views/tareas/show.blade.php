@extends('layouts.app')

@section('title', $tarea->titulo)

@section('content')
    <div class="container-xl">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>{{ $tarea->titulo }}</h2>

            @if(auth()->user()->rol === 'PROFESOR')
                <div class="dropdown">
                    <button class="btn btn-light border-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li>
                            <a href="{{ route('tareas.edit', $tarea) }}" class="dropdown-item">
                                <i class="bi bi-pencil me-1"></i> Editar tarea
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('tareas.destroy', $tarea) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta tarea?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-trash me-1"></i> Eliminar tarea
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endif
        </div>

        <p>{{ $tarea->descripcion }}</p>
        <p>
            <strong>Asignatura:</strong> {{ $tarea->asignatura->nombre }} <br>
            <strong>Fecha límite:</strong> {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}
        </p>
        <hr>

        <!-- Archivos organizados por nivel -->
        <h4 class="mb-3">Materiales de la Tarea</h4>

        @php
            $niveles = ['sencillo', 'intermedio', 'avanzado'];
        @endphp

        @foreach($niveles as $nivel)
            @php
                $archivosNivel = $tarea->archivos->where('nivel', $nivel);
            @endphp

            @if($archivosNivel->isNotEmpty())
                <h5 class="mt-3">{{ ucfirst($nivel) }}</h5>
                <ul class="list-group mb-4">
                    @foreach($archivosNivel as $archivo)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $archivo->nombre_archivo }}
                            <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                Ver archivo
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        @endforeach

        <hr>

        <h4 class="mb-3">Entregas por Estudiante</h4>

        @auth
            @if(auth()->user()->rol === 'PROFESOR')
                <div class="mb-4 text-end">
                    <a href="{{ route('progreso.create', $tarea) }}" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus me-1"></i> Asignar niveles a estudiantes
                    </a>
                </div>
            @endif
        @endauth

        @if ($tarea->progresos->isEmpty())
            <div class="alert alert-info">No hay entregas asignadas o registradas para esta tarea.</div>
        @else
            <div class="accordion" id="accordionProgresos">
                @foreach ($tarea->progresos as $progreso)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $progreso->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $progreso->id }}" aria-expanded="false" aria-controls="collapse{{ $progreso->id }}">
                                {{ $progreso->estudiante->nombre }}
                                <span class="badge bg-secondary ms-2">
                                    Nivel asignado: {{ ucfirst($progreso->nivel_asignado->value) }}
                                </span>
                            </button>
                        </h2>
                        <div id="collapse{{ $progreso->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $progreso->id }}" data-bs-parent="#accordionProgresos">
                            <div class="accordion-body">
                                @if ($progreso->entregas->isNotEmpty())
                                    <ul class="list-group">
                                        @foreach ($progreso->entregas as $entrega)
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ ucfirst($entrega->nivel) }}</strong>
                                                        <span class="text-muted">– {{ $entrega->fecha_entrega->format('d/m/Y H:i') }}</span>
                                                    </div>
                                                    <a href="{{ asset('storage/' . $entrega->archivo) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        Ver archivo
                                                    </a>
                                                </div>

                                                <form action="{{ route('entregas.feedback', $entrega) }}" method="POST" class="mt-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-2">
                                                        <label class="form-label">Comentario</label>
                                                        <textarea name="comentario" class="form-control" rows="2">{{ $entrega->comentario }}</textarea>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label">Nota</label>
                                                        <input type="number" name="nota" class="form-control w-auto" value="{{ $entrega->nota }}" step="0.1" min="0" max="10">
                                                    </div>
                                                    <div class="text-end">
                                                        <button class="btn btn-sm btn-success" type="submit">
                                                            <i class="bi bi-save me-1"></i> Guardar feedback
                                                        </button>
                                                    </div>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        Este estudiante aún no ha entregado nada.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('asignaturas.trabajo', $tarea->asignatura->slug) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver al tablón
            </a>
        </div>

    </div>
@endsection
