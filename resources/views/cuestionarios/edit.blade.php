@extends('layouts.app')

@section('title', 'Editar cuestionario')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">Editar cuestionario: {{ $tarea->titulo }}</h2>

        <div class="mb-3">
            <p><strong>Descripción:</strong> {{ $tarea->descripcion }}</p>
        </div>

        @if ($tarea->es_generica)
            @include('cuestionarios.partials.formulario', ['nivel' => null])
        @else
            <ul class="nav nav-tabs mb-4" id="nivelTabs" role="tablist">
                @foreach (['sencillo', 'intermedio', 'avanzado'] as $nivel)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif" id="{{ $nivel }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $nivel }}" type="button" role="tab">
                            {{ ucfirst($nivel) }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="nivelTabsContent">
                @foreach (['sencillo', 'intermedio', 'avanzado'] as $nivel)
                    <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $nivel }}" role="tabpanel">
                        @include('cuestionarios.partials.formulario', ['nivel' => $nivel])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
