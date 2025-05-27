<div class="sticky-top pt-3">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h6 class="text-muted fw-semibold mb-3">📌 Índice de preguntas</h6>

            @if($esGenerica)
                <ul class="list-unstyled">
                    @foreach($preguntasPorNivel->get(null, collect()) as $i => $pregunta)
                        <li>
                            <a href="#pregunta-{{ $pregunta->id }}" class="d-block text-decoration-none py-1 small text-primary">
                                Pregunta {{ $i + 1 }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                @foreach(['sencillo', 'intermedio', 'avanzado'] as $nivel)
                    @php $nivelPregs = $preguntasPorNivel->get($nivel, collect()); @endphp
                    @if($nivelPregs->isNotEmpty())
                        <p class="text-muted mb-1 mt-3 small">{{ ucfirst($nivel) }}</p>
                        <ul class="list-unstyled">
                            @foreach($nivelPregs as $i => $pregunta)
                                <li>
                                    <a href="#pregunta-{{ $pregunta->id }}" class="d-block text-decoration-none py-1 small text-primary">
                                        Pregunta {{ $i + 1 }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
