<form action="{{ route('cuestionarios.preguntas.store', $cuestionario) }}" method="POST" class="mb-4">
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
    <div id="respuestas-container-{{ $nivel ?? 'generico' }}">
        @for ($i = 0; $i < 2; $i++)
            <div class="input-group mb-2">
                <input type="text" name="respuestas[{{ $i }}][texto]" class="form-control" placeholder="Respuesta {{ $i + 1 }}" required>
                <div class="input-group-text">
                    <input type="radio" name="respuestas_correcta" value="{{ $i }}" required>
                </div>
            </div>
        @endfor
    </div>

    <button type="button" class="btn btn-sm btn-outline-secondary mb-3 add-respuesta" data-nivel="{{ $nivel ?? 'generico' }}">
        <i class="bi bi-plus"></i> Añadir respuesta
    </button>
    </div>


    <button type="submit" class="btn btn-primary">Guardar pregunta</button>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.add-respuesta').forEach(btn => {
                btn.addEventListener('click', () => {
                    const nivel = btn.dataset.nivel;
                    const container = document.getElementById(`respuestas-container-${nivel}`);
                    const index = container.querySelectorAll('input[type="text"]').length;

                    const div = document.createElement('div');
                    div.className = 'input-group mb-2';
                    div.innerHTML = `
                <input type="text" name="respuestas[${index}][texto]" class="form-control" placeholder="Respuesta ${index + 1}" required>
                <div class="input-group-text">
                    <input type="radio" name="respuestas_correcta" value="${index}" required>
                </div>
            `;
                    container.appendChild(div);
                    div.classList.add('fade-slide-enter');
                    setTimeout(() => {
                        div.classList.remove('fade-slide-enter');
                        div.classList.add('fade-slide-enter-active');
                    }, 10);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.tipo-pregunta-select').forEach(select => {
                select.addEventListener('change', () => {
                    const nivel = select.dataset.nivel;
                    const bloque = document.getElementById(`bloque-respuestas-${nivel}`);
                    if (select.value === 'abierta') {
                        bloque.style.display = 'none';
                    } else {
                        bloque.style.display = 'block';
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const lista = document.getElementById('lista-preguntas');
        Sortable.create(lista, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: () => {
                const orden = [...lista.children].map((li, index) => ({
                    id: li.dataset.id,
                    posicion: index + 1
                }));

                fetch('{{ route("cuestionarios.preguntas.reordenar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ orden })
                });
            }
        });
    </script>

@endpush
