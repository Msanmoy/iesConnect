@push('styles')
    <style>
        .fade-slide-enter {
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .fade-slide-enter-active {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-slide-exit {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .fade-slide-exit-active {
            opacity: 0;
            transform: translateY(10px);
        }

        .sortable-ghost {
            background-color: #f5f5f5;
            opacity: 0.5;
        }
    </style>
@endpush


<h5 class="mt-4">Preguntas @if($nivel) del nivel {{ ucfirst($nivel) }} @endif</h5>
<ul id="lista-preguntas{{ $nivel ?? 'generico' }}" class="list-group">
    @forelse ($preguntas as $pregunta)
        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $pregunta->id }}">
            <span>{{ $pregunta->enunciado }} ({{ $pregunta->puntos }} pts)</span>
            <div class="btn-group">
                <form method="POST" action="{{ route('cuestionarios.preguntas.destroy', $pregunta) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                </form>
                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" data-bs-toggle="modal" data-bs-target="#editarPreguntaModal{{ $pregunta->id }}">
                    ✏️
                </button>
            </div>
        </li>
        @include('cuestionarios.partials.modal-editar', ['pregunta' => $pregunta])
    @empty
        <li class="list-group-item">No hay preguntas aún.</li>
    @endforelse
</ul>
