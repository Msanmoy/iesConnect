@extends('layouts.app') {{-- Asegúrate de tener un layout base llamado app.blade.php --}}

@section('content')
    <div class="container-xl py-5">
        <h2 class="fw-bold mb-4">Crear Nueva Tarea</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('tareas.store') }}" method="POST">
                    @csrf

                    <!-- Nombre de la tarea -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Tarea</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selección de Tema -->
                    <div class="mb-3">
                        <label for="tema_id" class="form-label">Tema</label>
                        <select class="form-select @error('tema_id') is-invalid @enderror" id="tema_id" name="tema_id" required>
                            <option value="">Seleccione un tema...</option>
                            @foreach($temas as $tema)
                                <option value="{{ $tema->id }}" {{ old('tema_id') == $tema->id ? 'selected' : '' }}>
                                    {{ $tema->nombre }} ({{ $tema->aula->nombre ?? 'Sin aula' }})
                                </option>
                            @endforeach
                        </select>
                        @error('tema_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Tarea
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
