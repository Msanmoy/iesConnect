<div id="pregunta-{{ $pregunta->id }}" data-nivel="{{ $pregunta->nivel ?? 'genérico' }}" class="card mb-4 shadow-sm border-0">
<div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="fw-bold mb-1">📝 {{ $pregunta->enunciado }}</h6>
                <p class="mb-1 text-muted small">
                    Tipo: {{ ucfirst($pregunta->tipo) }} | Puntos: {{ $pregunta->puntos }}
                </p>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-light border" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button type="button" class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#editarPregunta{{ $pregunta->id }}">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </button>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('cuestionarios.preguntas.destroy', $pregunta) }}" onsubmit="return confirm('¿Eliminar esta pregunta?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-trash me-1"></i> Eliminar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        @if($pregunta->tipo === 'test')
            <ul class="list-group list-group-flush mt-3">
                @foreach($pregunta->respuestas as $respuesta)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        {{ $respuesta->texto }}
                        @if($respuesta->es_correcta)
                            <span class="badge bg-success">Correcta</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p class="mt-3 text-muted fst-italic">Pregunta abierta</p>
        @endif
    </div>
    <div class="collapse mt-3" id="editarPregunta{{ $pregunta->id }}">
        <form action="{{ route('cuestionarios.preguntas.update', $pregunta->id) }}" method="POST" class="mb-4">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Enunciado</label>
                <input type="text" name="enunciado" class="form-control" value="{{ old('enunciado', $pregunta->enunciado) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Puntos</label>
                <input type="number" name="puntos" class="form-control" value="{{ old('puntos', $pregunta->puntos) }}" min="0">
            </div>

            <input type="hidden" name="tipo" value="test">

            <div class="mb-3">
                <label class="form-label">Respuestas</label>
                <ul class="list-group" id="lista-preguntas-{{ $pregunta->id }}">
                    @foreach ($pregunta->respuestas as $i => $respuesta)
                        <li class="list-group-item d-flex align-items-center gap-2" data-id="{{ $respuesta->id }}">
                            {{-- Radio para marcar la correcta --}}
                            <input type="radio" name="respuesta_correcta" value="{{ $respuesta->id }}" @checked($respuesta->es_correcta)>

                            {{-- Texto de la respuesta --}}
                            <input type="hidden" name="respuestas[{{ $i }}][id]" value="{{ $respuesta->id }}">
                            <input type="text" name="respuestas[{{ $i }}][texto]" class="form-control" value="{{ $respuesta->texto }}">
                        </li>
                    @endforeach
                </ul>
            </div>

            <button type="submit" class="btn btn-primary">💾 Guardar cambios</button>
        </form>

    </div>

</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const enlaces = document.querySelectorAll('a[href^="#pregunta-"]');

            enlaces.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();

                    const destino = document.querySelector(this.getAttribute('href'));
                    if (!destino) return;

                    const nivel = destino.dataset.nivel;
                    if (nivel) {
                        const tabBtn = document.querySelector(`[data-bs-target="#contenido-${nivel}"]`);
                        if (tabBtn) tabBtn.click();
                    }

                    setTimeout(() => {
                        destino.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 300);
                });
            });
        });
    </script>
@endpush

