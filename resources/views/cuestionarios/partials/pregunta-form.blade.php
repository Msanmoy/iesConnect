<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h6 class="fw-semibold mb-3">➕ Añadir nueva pregunta</h6>

        <form method="POST" action="{{ route('cuestionarios.preguntas.store', $cuestionario) }}">
            @csrf

            <input type="hidden" name="nivel" value="{{ $nivel !== 'genérico' ? $nivel : null }}">

            <div class="mb-3">
                <label class="form-label">Enunciado</label>
                <input type="text" name="enunciado" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de pregunta</label>
                <select name="tipo" class="form-select tipo-selector" required>
                    <option value="test">Test</option>
                    <option value="abierta">Abierta</option>
                </select>
            </div>

            <div class="mb-3 puntos-wrapper">
                <label class="form-label">Puntos</label>
                <input type="number" name="puntos" class="form-control" min="1" value="1" required>
            </div>

            <div class="respuestas-wrapper">
                <label class="form-label">Respuestas (test)</label>
                <div class="respuesta mb-2 input-group">
                    <input type="text" name="respuestas[0][texto]" class="form-control" placeholder="Respuesta 1" required>
                    <div class="input-group-text">
                        <input type="radio" name="respuestas_correcta" value="0" required title="Marcar como correcta">
                    </div>
                </div>
                <div class="respuesta mb-2 input-group">
                    <input type="text" name="respuestas[1][texto]" class="form-control" placeholder="Respuesta 2" required>
                    <div class="input-group-text">
                        <input type="radio" name="respuestas_correcta" value="1" title="Marcar como correcta">
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-secondary" id="add-respuesta">
                        <i class="bi bi-plus-circle me-1"></i> Añadir otra respuesta
                    </button>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-check-circle me-1"></i> Guardar pregunta
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.currentScript.closest('form');
            const addBtn = form.querySelector('#add-respuesta');
            const wrapper = form.querySelector('.respuestas-wrapper');
            const tipoSelector = form.querySelector('.tipo-selector');

            let index = 2;

            addBtn?.addEventListener('click', () => {
                const div = document.createElement('div');
                div.className = 'respuesta mb-2 input-group';

                div.innerHTML = `
                <input type="text" name="respuestas[${index}][texto]" class="form-control" placeholder="Respuesta ${index + 1}" required>
                <div class="input-group-text">
                    <input type="radio" name="respuestas_correcta" value="${index}" title="Marcar como correcta">
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger ms-2" title="Eliminar" onclick="this.closest('.respuesta').remove()">
                    <i class="bi bi-x-circle"></i>
                </button>
            `;

                wrapper.insertBefore(div, addBtn.closest('div'));
                index++;
            });

            tipoSelector?.addEventListener('change', (e) => {
                const tipo = e.target.value;
                if (tipo === 'test') {
                    wrapper.style.display = 'block';
                } else {
                    wrapper.style.display = 'none';
                }
            });

            tipoSelector.dispatchEvent(new Event('change'));
        });
    </script>
@endpush
