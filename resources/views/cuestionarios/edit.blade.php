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

            <input type="hidden" name="respuestas[0][es_correcta]" value="false">
            <input type="hidden" name="respuestas[1][es_correcta]" value="false">

            <button type="submit" class="btn btn-primary">Guardar pregunta</button>
        </form>

        <hr class="my-4">

        <h4 class="mb-3">Preguntas actuales</h4>
        @foreach($tarea->preguntas as $pregunta)
            <div class="mb-3">
                <strong>{{ $loop->iteration }}. {{ $pregunta->enunciado }}</strong>
                <ul>
                    @foreach($pregunta->respuestas as $respuesta)
                        <li>
                            {{ $respuesta->texto }}
                            @if($respuesta->es_correcta)
                                <span class="badge bg-success ms-2">Correcta</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let index = 2;

            document.getElementById('add-respuesta').addEventListener('click', () => {
                const container = document.getElementById('respuestas-container');

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

                radioDiv.appendChild(radio);
                div.appendChild(input);
                div.appendChild(radioDiv);
                container.appendChild(div);

                index++;
            });

            // On form submit, mark selected radio as 'es_correcta'
            document.querySelector('form').addEventListener('submit', function (e) {
                const radios = document.querySelectorAll('input[name="respuestas_correcta"]');
                radios.forEach(radio => {
                    const i = radio.value;
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `respuestas[${i}][es_correcta]`;
                    input.value = radio.checked ? '1' : '0';
                    this.appendChild(input);
                });
            });
        });
    </script>
@endpush
