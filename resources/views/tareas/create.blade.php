

<form action="{{ route('tareas.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Otros campos de la tarea -->

    <label>Archivos:</label>
    <input type="file" name="archivos[]" multiple class="form-control">

    <button type="submit">Guardar</button>
</form>
