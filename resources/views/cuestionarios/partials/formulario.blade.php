<form id="formulario-pregunta-{{ $pregunta->id }}" action="{{ route('cuestionarios.preguntas.store', $cuestionario) }}" method="POST" class="mb-4">
    @csrf
    @if ($nivel)
        <input type="hidden" name="nivel" value="{{ $nivel }}">
        <h5 class="mb-3">Preguntas del nivel: <strong>{{ ucfirst($nivel) }}</strong></h5>
    @else
        <h5 class="mb-3">Preguntas para tarea genérica</h5>
    @endif

    <div class="mb-3">
        <label>Tipo de pregunta</label>
        <select name="tipo" class="form-select tipo-pregunta-select" data-nivel="{{ $nivel ?? 'generico' }}">
            <option value="test">Test (opciones)</option>
            <option value="abierta">Abierta (respuesta escrita)</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Enunciado de la pregunta</label>
        <input type="text" name="enunciado" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Puntos</label>
        <input type="number" name="puntos" class="form-control" value="1" min="0" step="0.1" required>
    </div>

    <div class="bloque-respuestas" id="bloque-respuestas-{{ $nivel ?? 'generico' }}">
        <div id="respuestas-container-{{ $pregunta->id }}">
            @foreach ($pregunta->respuestas as $respuesta)
                <div class="respuesta-item">
                    <input type="text" name="respuestas[{{ $loop->index }}][texto]" value="{{ $respuesta->texto }}">
                    <button type="button" class="btn-eliminar-respuesta">Eliminar</button>
                </div>
            @endforeach
        </div>

    <button type="button" class="btn btn-sm btn-outline-secondary mb-3 add-respuesta" data-nivel="{{ $nivel ?? 'generico' }}">
        <i class="bi bi-plus"></i> Añadir respuesta
    </button>
    </div>


    <button type="submit" class="btn btn-primary">Guardar pregunta</button>
</form>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formulario-pregunta-{{ $pregunta->id }}');
            const container = document.getElementById('respuestas-container-{{ $pregunta->id }}');
            const btnAgregar = document.getElementById('btn-agregar-respuesta-{{ $pregunta->id }}');

            form.addEventListener('submit', e => {
                const respuestas = container.querySelectorAll('.respuesta-item');
                if (respuestas.length < 2) {
                    e.preventDefault();
                    alert('Debes añadir al menos dos respuestas.');
                }
            });

            btnAgregar.addEventListener('click', () => {
                const index = container.querySelectorAll('.respuesta-item').length;
                const wrapper = document.createElement('div');
                wrapper.classList.add('respuesta-item');
                wrapper.innerHTML = `
      <input type="text" name="respuestas[${index}][texto]" placeholder="Texto de la respuesta">
      <button type="button" class="btn-eliminar-respuesta">Eliminar</button>
    `;
                container.appendChild(wrapper);
            });

            container.addEventListener('click', e => {
                if (e.target.matches('.btn-eliminar-respuesta')) {
                    e.target.closest('.respuesta-item').remove();
                }
            });
        });
    </script>
@endpush
