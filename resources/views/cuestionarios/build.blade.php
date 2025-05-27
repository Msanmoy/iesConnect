@extends('layouts.app')

@section('title', 'Construir cuestionario')

@section('content')
    <div class="container-xl py-4 row">

        <div class="col-md-3 d-none d-md-block">
            @include('cuestionarios.partials.sidebar', [
                'preguntasPorNivel' => $preguntasPorNivel,
                'esGenerica' => $esGenerica
            ])
        </div>
        <div class="col-md-9">
            <h2 class="mb-4">⚙️ Constructor: {{ $tarea->titulo }}</h2>

            @if(!$esGenerica)
                <ul class="nav nav-tabs mb-4" id="nivelTabs" role="tablist">
                    @foreach(['sencillo', 'intermedio', 'avanzado'] as $i => $nivel)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($i === 0) active @endif" id="tab-{{ $nivel }}"
                                    data-bs-toggle="tab" data-bs-target="#contenido-{{ $nivel }}"
                                    type="button" role="tab">
                                {{ ucfirst($nivel) }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach(['sencillo', 'intermedio', 'avanzado'] as $i => $nivel)
                        <div class="tab-pane fade @if($i === 0) show active @endif" id="contenido-{{ $nivel }}" role="tabpanel">
                            @include('cuestionarios.partials.nivel', [
                                'nivel' => $nivel,
                                'preguntas' => $preguntasPorNivel->get($nivel, collect()),
                                'cuestionario' => $cuestionario
                            ])
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Cuestionario genérico --}}
                @include('cuestionarios.partials.nivel', [
                    'nivel' => 'genérico',
                    'preguntas' => $cuestionario->preguntas,
                    'cuestionario' => $cuestionario
                ])
            @endif

            <a href="{{ route('asignaturas.show', $tarea->asignatura->slug) }}" class="btn btn-outline-secondary mt-3">
                <i class="bi bi-arrow-left-circle me-1"></i> Volver a tareas
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.querySelectorAll('[id^="lista-preguntas"]').forEach((ul) => {
            Sortable.create(ul, {
                animation: 150,
                onEnd: () => {
                    const orden = [...ul.children].map((li, index) => ({
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
        });
    </script>

    <script>
        document.querySelectorAll('.tipo-pregunta-select').forEach(select => {
            select.addEventListener('change', () => {
                const id = select.dataset.id;
                const bloque = document.getElementById(`bloque-respuestas-${id}`);
                if (select.value === 'abierta') {
                    bloque.style.display = 'none';
                } else {
                    bloque.style.display = 'block';
                }
            });
        });
    </script>
@endpush
