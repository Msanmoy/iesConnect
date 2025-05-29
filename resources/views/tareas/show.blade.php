@extends('layouts.app')

@section('title', $tarea->titulo)

@section('content')
    <div class="container-xl">

        @php
            $esGenerica = $tarea->archivos->whereNull('nivel')->isNotEmpty();
        @endphp


        {{-- Pestañas --}}
        <ul class="nav nav-tabs mb-4" id="tareaTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="instrucciones-tab" data-bs-toggle="tab" data-bs-target="#instrucciones" type="button" role="tab">Instrucciones</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="trabajo-tab" data-bs-toggle="tab" data-bs-target="#trabajo" type="button" role="tab">Trabajo de los alumnos</button>
            </li>
            @if (!$esGenerica)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="asignar-tab" data-bs-toggle="tab" data-bs-target="#asignar" type="button" role="tab">Asignar niveles</button>
                </li>
            @endif
        </ul>

        <div class="tab-content" id="tareaTabsContent">
            {{-- Instrucciones --}}
            <div class="tab-pane fade show active" id="instrucciones" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3><i class="bi bi-clipboard me-2 text-primary"></i>{{ $tarea->titulo }}</h3>

                    @if($tarea->tipo === 'cuestionario')
                        @if(auth()->user()->rol === 'PROFESOR')
                            <div class="dropdown">
                                <button class="btn btn-light border-0" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a href="{{ route('cuestionarios.build', $tarea) }}" class="dropdown-item">✏️ Editar</a></li>
                                    <li>
                                        <form action="{{ route('tareas.destroy', $tarea) }}" method="POST" onsubmit="return confirm('¿Eliminar esta tarea?');">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger">🗑️ Eliminar</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                </div>

                <p class="text-muted mb-1">{{ $tarea->fecha_limite ?? 'Sin fecha limite' }} — {{ $tarea->puntos ?? '10' }} puntos</p>
                <p>{{ $tarea->descripcion }}</p>
                @else
                    @if(auth()->user()->rol === 'PROFESOR')
                        <div class="dropdown">
                            <button class="btn btn-light border-0" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a href="{{ route('tareas.edit', $tarea) }}" class="dropdown-item">✏️ Editar</a></li>
                                <li>
                                    <form action="{{ route('tareas.destroy', $tarea) }}" method="POST" onsubmit="return confirm('¿Eliminar esta tarea?');">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger">🗑️ Eliminar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                    </div>

                    <p class="text-muted mb-1">{{ $tarea->fecha_limite ?? 'Sin fecha limite' }} — {{ $tarea->puntos ?? '10' }} puntos</p>
                    <p>{{ $tarea->descripcion }}</p>


                    @php
                        $archivosAgrupados = $tarea->archivos->groupBy('nivel');
                    @endphp

                    @if ($archivosAgrupados->count())
                        @if ($archivosAgrupados->has(null))
                            {{-- Tarea genérica (sin niveles) --}}
                            <h5 class="mt-4">Archivos para todos los estudiantes</h5>
                            <ul class="list-group mb-3">
                                @foreach ($archivosAgrupados->get(null) as $archivo)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $archivo->nombre_archivo }}</span>
                                        <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" class="btn btn-sm btn-outline-primary" target="_blank">📄 Ver</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            {{-- Tarea con niveles --}}
                            @foreach(['sencillo', 'intermedio', 'avanzado'] as $nivel)
                                @if ($archivosAgrupados->has($nivel))
                                    <h5 class="mt-4 text-capitalize">{{ $nivel }}</h5>
                                    <ul class="list-group mb-3">
                                        @foreach ($archivosAgrupados->get($nivel) as $archivo)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ $archivo->nombre_archivo }}</span>
                                                <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" class="btn btn-sm btn-outline-primary" target="_blank">📄 Ver</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endif
                <hr>
            </div>

            {{-- Trabajo de los alumnos --}}
            <div class="tab-pane fade" id="trabajo" role="tabpanel">
                @php
                    $esGenerica = $tarea->archivos->whereNull('nivel')->isNotEmpty();
                    $totalAsignados = $tarea->progresos->count();
                    $totalEntregadas = $tarea->progresos->flatMap->entregas->count();
                    $totalPendientes = $totalAsignados - $totalEntregadas;
                @endphp

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold">{{ $tarea->titulo }}</h5>
                    <div class="text-end text-muted">
            <span class="me-3">
                <i class="bi bi-check-circle text-success me-1"></i>
                Entregadas: <strong>{{ $totalEntregadas }}</strong>
            </span>
                        <span>
                <i class="bi bi-person text-primary me-1"></i>
                Asignadas: <strong>{{ $totalAsignados }}</strong>
            </span>
                    </div>
                </div>

                @if ($esGenerica)
                    {{-- TAREA GENÉRICA – Tabla de alumnos y estado --}}
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Estudiante</th>
                                <th>Entrega</th>
                                <th>Comentario</th>
                                <th>Nota</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tarea->progresos as $progreso)
                                @php
                                    $entrega = $progreso->entregas->first();
                                @endphp
                                <tr>
                                    <td>{{ $progreso->estudiante->nombre }}</td>

                                    @if ($entrega)
                                        <td>
                                            <a href="{{ asset('storage/' . $entrega->archivo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                📄 Ver archivo
                                            </a>
                                            <div class="text-muted small mt-1">
                                                {{ $entrega->fecha_entrega->format('d/m/Y H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <form action="{{ route('entregas.feedback', $entrega) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <textarea name="comentario" class="form-control form-control-sm" rows="2">{{ $entrega->comentario }}</textarea>
                                        </td>
                                        <td>
                                            <input type="number" name="nota" class="form-control form-control-sm" value="{{ $entrega->nota }}" step="0.1" min="0" max="10">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-success btn-sm">
                                                💾 Guardar
                                            </button>
                                            </form>
                                        </td>
                                    @else
                                        <td colspan="4">
                                            <div class="text-muted fst-italic">Sin entrega</div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <ul class="nav nav-pills mb-4" id="nivelesTabs" role="tablist">
                        @foreach(['sencillo', 'intermedio', 'avanzado'] as $nivel)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                        id="nivel-{{ $nivel }}-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#nivel-{{ $nivel }}"
                                        type="button"
                                        role="tab">
                                    {{ ucfirst($nivel) }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content" id="nivelesTabsContent">
                        @foreach(['sencillo', 'intermedio', 'avanzado'] as $nivel)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="nivel-{{ $nivel }}" role="tabpanel">
                                @php
                                    $progresosNivel = $tarea->progresos->filter(function ($progreso) use ($nivel) {
                                        return $progreso->entregas->contains('nivel', $nivel);
                                    });
                                @endphp
                                @if ($progresosNivel->isEmpty())
                                    <div class="alert alert-light border">No hay estudiantes que hayan entregado en este nivel.</div>
                                @else
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                                        @foreach ($progresosNivel as $progreso)
                                            @php
                                                $entrega = $progreso->entregas->firstWhere('nivel', $nivel);
                                                $estaEnOtroNivel = $progreso->nivel_asignado->value !== $nivel;
                                            @endphp

                                            <div class="col">
                                                <div class="card shadow-sm border-0 h-100">
                                                    <div class="card-body d-flex flex-column">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h5 class="card-title mb-0">{{ $progreso->estudiante->nombre }}</h5>
                                                            <span class="badge bg-secondary text-uppercase">{{ $nivel }}</span>
                                                        </div>

                                                        @if ($estaEnOtroNivel)
                                                            <div class="mb-2 text-info small">
                                                                <i class="bi bi-arrow-right-circle me-1"></i>
                                                                Actualmente en nivel <strong>{{ ucfirst($progreso->nivel_asignado->value) }}</strong>
                                                            </div>
                                                        @endif

                                                        <p class="text-success mb-2"><i class="bi bi-check-circle me-1"></i> Ha entregado</p>
                                                        <p class="mb-2 text-muted">Entregado el {{ $entrega->fecha_entrega->format('d/m/Y H:i') }}</p>

                                                        <a href="{{ asset('storage/' . $entrega->archivo) }}" class="btn btn-sm btn-outline-primary mb-3" target="_blank">
                                                            Ver entrega
                                                        </a>

                                                        <form action="{{ route('entregas.feedback', $entrega) }}" method="POST" class="mt-auto">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="mb-2">
                                                                <label class="form-label">Comentario</label>
                                                                <textarea name="comentario" class="form-control" rows="2">{{ $entrega->comentario }}</textarea>
                                                            </div>

                                                            <div class="mb-2">
                                                                <label class="form-label">Nota</label>
                                                                <input type="number" name="nota" class="form-control" value="{{ $entrega->nota }}" step="0.1" min="0" max="10">
                                                            </div>

                                                            <div class="text-end">
                                                                <button class="btn btn-success btn-sm">
                                                                    <i class="bi bi-check2-circle me-1"></i> Guardar feedback
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>


        @if(auth()->user()->rol === 'PROFESOR')
                <div class="tab-pane fade" id="asignar" role="tabpanel">
                    <h4 class="mb-4">Asignar niveles a estudiantes – <span class="text-muted">{{ $tarea->titulo }}</span></h4>

                    @if ($estudiantes->isEmpty())
                        <div class="alert alert-warning">No hay estudiantes asignados a esta asignatura.</div>
                    @else
                        <form action="{{ route('progreso.store', $tarea) }}" method="POST">
                            @csrf

                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Nivel asignado</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($estudiantes as $estudiante)
                                    @php
                                        $progreso = $tarea->progresos->firstWhere('usuario_id', $estudiante->id);
                                    @endphp
                                    <tr>
                                        <td>{{ $estudiante->nombre }}</td>
                                        <td>
                                            <input type="hidden" name="usuario_id[]" value="{{ $estudiante->id }}">

                                            @if ($progreso)
                                                <input type="hidden" name="progreso_id[{{ $estudiante->id }}]" value="{{ $progreso->id }}">
                                                <select name="nivel_asignado[{{ $estudiante->id }}]" class="form-select form-select-sm w-auto d-inline-block">
                                                    <option value="sencillo" {{ $progreso->nivel_asignado->value === 'sencillo' ? 'selected' : '' }}>Sencillo</option>
                                                    <option value="intermedio" {{ $progreso->nivel_asignado->value === 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                                                    <option value="avanzado" {{ $progreso->nivel_asignado->value === 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                                                </select>
                                            @else
                                                <select name="nivel_asignado[{{ $estudiante->id }}]" class="form-select form-select-sm w-auto d-inline-block">
                                                    <option value="sencillo">Sencillo</option>
                                                    <option value="intermedio">Intermedio</option>
                                                    <option value="avanzado">Avanzado</option>
                                                </select>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Asignar niveles
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif




            <div class="mt-4">
            <a href="{{ route('asignaturas.trabajo', $tarea->asignatura->slug) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver al tablón
            </a>
        </div>
    </div>
@endsection
