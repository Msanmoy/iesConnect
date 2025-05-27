<div class="mb-4">
    <h5 class="fw-semibold mb-3">
        @if($nivel === 'genérico') Preguntas del cuestionario @else Nivel: {{ ucfirst($nivel) }} @endif
    </h5>

    @foreach($preguntas as $pregunta)
        @include('cuestionarios.partials.pregunta-card', ['pregunta' => $pregunta])
    @endforeach

    @include('cuestionarios.partials.pregunta-form', ['nivel' => $nivel, 'cuestionario' => $cuestionario])
</div>
