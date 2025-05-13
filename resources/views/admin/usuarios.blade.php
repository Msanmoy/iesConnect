@extends('layouts.app')

@section('title', 'Crear usuarios')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">👤 Crear Usuario</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulario para crear usuario --}}
        <div class="card shadow-sm border-0 rounded-3 p-4 mb-5">
            <form action="{{ route('admin.usuarios.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej. Juan Pérez" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Rol</label>
                    <select name="rol" class="form-select" required>
                        <option value="PROFESOR">👨‍🏫 Profesor</option>
                        <option value="ADMINISTRADOR">🛠️ Administrador</option>
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-plus-circle me-1"></i> Crear usuario
                    </button>
                </div>
            </form>
        </div>

        {{-- Listado de profesores --}}
        <div class="card">
            <div class="card-header">
                👨‍🏫 Profesores actuales
            </div>
            <div class="card-body">
                @if ($profesores->isEmpty())
                    <p class="text-muted">No hay profesores registrados.</p>
                @else
                    <ul class="list-group">
                        @foreach ($profesores as $profesor)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $profesor->nombre }}</strong>
                                    <span class="text-muted"> – {{ $profesor->email }}</span>
                                </div>
                                <form action="{{ route('admin.usuarios.destroy', $profesor) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este profesor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
