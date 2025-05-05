@extends('layouts.app')

@section('title', $tarea->titulo)

@section('content')
    <div class="container-xl">
        <h2 class="mb-3">{{ $tarea->titulo }}</h2>
        <p>{{ $tarea->descripcion }}</p>
        <p>
            <strong>Asignatura:</strong> {{ $tarea->asignatura->nombre }} <br>
            <strong>Fecha límite:</strong> {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}
        </p>
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
            <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a tareas
            </a>
        </div>
    </div>
@endsection
