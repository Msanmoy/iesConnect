<div class="modal fade" id="editarPreguntaModal{{ $pregunta->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('cuestionarios.preguntas.update', $pregunta) }}">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar pregunta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Tipo de pregunta</label>
                        <select name="tipo" class="form-select tipo-pregunta-select" data-id="{{ $pregunta->id }}">
                            <option value="test" @if($pregunta->tipo === 'test') selected @endif>Test (opciones)</option>
                            <option value="abierta" @if($pregunta->tipo === 'abierta') selected @endif>Abierta (respuesta escrita)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Enunciado</label>
                        <input type="text" name="enunciado" value="{{ $pregunta->enunciado }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Puntos</label>
                        <input type="number" name="puntos" value="{{ $pregunta->puntos }}" class="form-control" min="0" step="0.1" required>
                    </div>

                    <div class="bloque-respuestas-editar" id="bloque-respuestas-{{ $pregunta->id }}" @if($pregunta->tipo === 'abierta') style="display:none;" @endif>
                        <label>Respuestas</label>
                        @foreach ($pregunta->respuestas as $i => $respuesta)
                            <div class="input-group mb-2">
                                <input type="hidden" name="respuestas[{{ $i }}][id]" value="{{ $respuesta->id }}">
                                <input type="text" class="form-control" name="respuestas[{{ $i }}][texto]" value="{{ $respuesta->texto }}" required>
                                <div class="input-group-text">
                                    <input type="radio" name="respuesta_correcta" value="{{ $i }}" {{ $respuesta->es_correcta ? 'checked' : '' }}>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">💾 Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
