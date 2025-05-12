@extends('layouts.app')

@section('title', 'Editar cuestionario')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">Editar cuestionario: {{ $tarea->titulo }}</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('cuestionarios.preguntas.store', $tarea) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="enunciado" class="form-label">Enunciado de la pregunta</label>
                <input type="text" name="enunciado" id="enunciado" class="form-control" required>
            </div>

            <div id="respuestas-container">
                <label class="form-label">Respuestas</label>

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
            </div>

            <div class="mb-3">
                <button type="button" id="add-respuesta" class="btn btn-secondary btn-sm">
                    <i class="bi bi-plus"></i> Añadir respuesta
                </button>
            </div>
            <button type="submit" class="btn btn-primary">Guardar pregunta</button>
        </form>

        <hr class="my-4">

        <h4 class="mb-3">Preguntas actuales</h4>

        @foreach($tarea->preguntas as $pregunta)
            <div class="mb-4 border rounded p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>{{ $loop->iteration }}. {{ $pregunta->enunciado }}</strong>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#editPregunta{{ $pregunta->id }}">
                        <i class="bi bi-pencil"></i> Editar
                    </button>
                </div>

                <div class="collapse mt-3" id="editPregunta{{ $pregunta->id }}">
                    <form method="POST" action="{{ route('cuestionarios.preguntas.update', $pregunta) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <label class="form-label">Enunciado</label>
                            <input type="text" name="enunciado" class="form-control" value="{{ $pregunta->enunciado }}" required>
                        </div>

                        <label class="form-label">Respuestas</label>
                        @foreach($pregunta->respuestas as $respuesta)
                            <div class="input-group mb-2">
                                <input type="hidden" name="respuestas[{{ $loop->index }}][id]" value="{{ $respuesta->id }}">
                                <input type="text" class="form-control" name="respuestas[{{ $loop->index }}][texto]" value="{{ $respuesta->texto }}" required>
                                <div class="input-group-text">
                                    <input type="radio" name="correcta_{{ $pregunta->id }}" value="{{ $loop->index }}" {{ $respuesta->es_correcta ? 'checked' : '' }}>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-end">
                            <button type="submit" class="btn btn-sm btn-success">💾 Guardar cambios</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('cuestionarios.preguntas.destroy', $pregunta) }}" class="mt-3" onsubmit="return confirm('¿Estás seguro de eliminar esta pregunta?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i> Eliminar pregunta
                        </button>
                    </form>

                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form[action*="cuestionarios/preguntas"]');
            const container = document.getElementById('respuestas-container');
            const addBtn = document.getElementById('add-respuesta');

            if (!form || !container || !addBtn) {
                console.warn('Formulario de preguntas no encontrado.');
                return;
            }

            let index = container.querySelectorAll('.respuesta').length;

            addBtn.addEventListener('click', () => {
                const div = document.createElement('div');
                div.className = 'respuesta mb-2 input-group';

                const input = document.createElement('input');
                input.type = 'text';
                input.name = `respuestas[${index}][texto]`;
                input.placeholder = `Respuesta ${index + 1}`;
                input.required = true;
                input.className = 'form-control';

                const radioDiv = document.createElement('div');
                radioDiv.className = 'input-group-text';

                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'respuestas_correcta';
                radio.value = index;
                radio.title = 'Marcar como correcta';

                radioDiv.appendChild(radio);

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-outline-danger btn-sm ms-2';
                removeBtn.innerHTML = '<i class="bi bi-trash"></i>';
                removeBtn.title = 'Eliminar respuesta';
                removeBtn.onclick = () => div.remove();

                div.appendChild(input);
                div.appendChild(radioDiv);
                div.appendChild(removeBtn);

                container.appendChild(div);
                index++;
            });

            form.addEventListener('submit', function (e) {
                const seleccionada = form.querySelector('input[name="respuestas_correcta"]:checked');
                if (!seleccionada) {
                    e.preventDefault();
                    alert('Debes marcar una respuesta como correcta.');
                    return;
                }

                const correctaIndex = parseInt(seleccionada.value);

                // Elimina anteriores
                form.querySelectorAll('input[name$="[es_correcta]"]').forEach(el => el.remove());

                const respuestas = form.querySelectorAll('#respuestas-container .respuesta');

                respuestas.forEach((div, i) => {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = `respuestas[${i}][es_correcta]`;
                    hidden.value = (i === correctaIndex) ? '1' : '0';
                    form.appendChild(hidden);
                });
            });
        });
    </script>
@endpush
