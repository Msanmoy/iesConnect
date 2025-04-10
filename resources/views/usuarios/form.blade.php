<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="email" class="form-label">Correo</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="rol" class="form-label">Rol</label>
    <select name="rol" class="form-select" required>
        <option value="">Seleccione un rol</option>
        <option value="ESTUDIANTE" @selected(old('rol', $usuario->rol ?? '') === 'ESTUDIANTE')>Estudiante</option>
        <option value="PROFESOR" @selected(old('rol', $usuario->rol ?? '') === 'PROFESOR')>Profesor</option>
    </select>
</div>

@if (!isset($usuario))
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>
@endif
