@extends('layouts.app')

@section('title', 'Construir cuestionario')

@section('content')
    <div class="container-xl py-4">
        <h2 class="mb-4">Construir cuestionario: {{ $tarea->titulo }}</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($tarea->es_generica)
            @include('cuestionarios.partials.formulario', ['nivel' => null, 'cuestionario' => $cuestionario])
            @include('cuestionarios.partials.lista', ['nivel' => null, 'preguntas' => $cuestionario->preguntas->sortBy('orden')])
        @else
            <ul class="nav nav-tabs mb-4" role="tablist">
                @foreach (['sencillo', 'intermedio', 'avanzado'] as $nivel)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif" data-bs-toggle="tab" data-bs-target="#nivel-{{ $nivel }}" type="button" role="tab">
                            {{ ucfirst($nivel) }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach (['sencillo', 'intermedio', 'avanzado'] as $nivel)
                    <div class="tab-pane fade @if($loop->first) show active @endif" id="nivel-{{ $nivel }}" role="tabpanel">
                        @include('cuestionarios.partials.formulario', ['nivel' => $nivel, 'cuestionario' => $cuestionario])
                        @include('cuestionarios.partials.lista', [
                            'nivel' => $nivel,
                            'preguntas' => $cuestionario->preguntas->where('nivel', $nivel)->sortBy('orden')
                        ])
                    </div>
                @endforeach
            </div>
        @endif
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
