@extends('layouts.app')

@section('title', $asignatura->nombre)

@section('content')
    <div class="container-xl mt-4">
        @include('asignaturas.partials.navegacion', ['asignatura' => $asignatura])

        <div class="card mb-4 shadow-sm border-0 text-white"
             style="background: url('{{ $asignatura->imagen ? asset('storage/' . $asignatura->imagen) : asset('images/default.jpg') }}') center/cover no-repeat; border-radius: 10px; height: 180px;">
            <div class="card-body d-flex justify-content-between align-items-center h-100">
                <div>
                    <h2 class="fw-bold">{{ $asignatura->nombre }}</h2>
                </div>
                @if(auth()->user()->rol === 'PROFESOR')
                    <div>
                        <a href="#" class="btn btn-outline-light btn-sm bg-secondary" data-bs-toggle="modal" data-bs-target="#personalizarModal">
                            Personalizar
                        </a>
                    </div>
                @endif
            </div>
        </div>


        <div class="row">
            <div class="col-md-4">
                @if(auth()->user()->rol === 'PROFESOR')
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Código de clase</h6>
                        <h4 class="text-primary">{{ $asignatura->codigo }}</h4>
                            <form action="{{ route('asignaturas.regenerar-codigo', $asignatura) }}" method="POST" class="mt-2">
                                @csrf
                                <button class="btn btn-sm btn-outline-primary" type="submit">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Generar nuevo código
                                </button>
                            </form>
                    </div>
                </div>
                @endif

                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Próximas entregas</h6>
                        @if($asignatura->tareas->isEmpty())
                            <p class="small text-muted mb-0">No tienes ninguna tarea para esta semana</p>
                        @else
                            <ul class="list-unstyled mb-2">
                                @foreach($asignatura->tareas->take(2) as $tarea)
                                    <li class="mb-2">
                                        <i class="bi bi-clipboard-check"></i>
                                        <strong>{{ $tarea->titulo }}</strong>
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($tarea->fecha_limite)->diffForHumans() }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('asignaturas.trabajo', $asignatura->slug) }}" class="small">Ver todo</a>
                        @endif


                    </div>
                </div>
            </div>

            <div class="col-md-8">

                <div class="card mb-4 shadow-sm">
                    <div class="card-body pb-3">
                        <form action="{{ route('publicaciones.store', $asignatura) }}" method="POST">
                            @csrf
                            <div class="d-flex align-items-start mb-3">
                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-3" style="width: 45px; height: 45px; font-weight: bold; font-size: 18px;">
                                    {{ strtoupper(Str::substr(auth()->user()->nombre, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                        <textarea name="contenido"
                                  class="form-control border-0 shadow-sm"
                                  rows="3"
                                  placeholder="Anuncia algo a tu clase..."
                                  style="resize: none; background-color: #f8f9fa;"
                                  required></textarea>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary rounded-2 px-4">
                                    <i class="bi bi-megaphone me-1"></i> Publicar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @foreach($asignatura->publicaciones->sortByDesc('created_at') as $pub)
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-body pb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                        <span class="fw-bold">{{ strtoupper(substr($pub->usuario->nombre, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $pub->usuario->nombre }}</strong>
                                        <small class="text-muted">{{ $pub->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>

                                @if(auth()->id() === $pub->usuario_id)
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editarPublicacionModal{{ $pub->id }}">
                                                    <i class="bi bi-pencil me-1"></i> Editar
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('publicaciones.destroy', $pub) }}" method="POST" onsubmit="return confirm('¿Eliminar esta publicación?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-1"></i> Eliminar
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <p class="mt-2 mb-0 fs-6">{{ $pub->contenido }}</p>
                        </div>
                    </div>

                    <div class="modal fade" id="editarPublicacionModal{{ $pub->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="{{ route('publicaciones.update', $pub) }}" method="POST" class="modal-content">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar publicación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <textarea name="contenido" class="form-control" rows="4" required>{{ $pub->contenido }}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach

                @foreach($asignatura->tareas->sortByDesc('created_at') as $tarea)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">
                                    <i class="bi bi-clipboard"></i>
                                    {{ $tarea->profesor->nombre ?? 'Profesor' }} ha publicado una nueva tarea: <strong>{{ $tarea->titulo }}</strong>
                                </h6>
                                <small class="text-muted">{{ $tarea->created_at->format('H:i') }}</small>
                            </div>
                            <div>
                                @if($tarea->tipo === 'cuestionario')
                                    @if(auth()->user()->rol === 'ESTUDIANTE')
                                        <a href="{{ route('cuestionarios.responder', $tarea) }}" class="btn btn-sm btn-primary">Ver cuestionario</a>
                                    @else
                                        <a href="{{ route('cuestionarios.estadisticas', $tarea) }}" class="btn btn-sm btn-outline-primary">Ver cuestionario</a>
                                    @endif

                                @else
                                    @if(auth()->user()->rol === 'ESTUDIANTE')
                                        <a href="{{ route('tareas.ver.estudiante', $tarea) }}" class="btn btn-sm btn-primary">Ver tarea</a>
                                    @else
                                        <a href="{{ route('tareas.show', $tarea) }}" class="btn btn-sm btn-outline-primary">Detalles</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($asignatura->tareas->isEmpty())
                    <div class="alert alert-light text-muted">No hay tareas todavía en esta clase.</div>
                @endif

            </div>


@if(auth()->user()->rol === 'PROFESOR')

    <div class="modal fade" id="personalizarModal" tabindex="-1" aria-labelledby="personalizarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('asignaturas.personalizar', $asignatura) }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="personalizarModalLabel">Personalizar clase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Subir nueva imagen de fondo</label>
                        <input class="form-control" type="file" id="imagen" name="imagen" accept="image/*">
                        <small class="text-muted">Formato recomendado: JPG, PNG. Máx. 2MB.</small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endif
@endsection

