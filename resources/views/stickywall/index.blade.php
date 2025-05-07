@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        #stickyWall {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .sticky-note {
            width: 220px;
            min-height: 200px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            background-color: #fff; /* Se sobrescribirá con clases de color */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease;
        }

        .sticky-note:hover {
            transform: scale(1.03);
        }

        .add-note {
            width: 220px;
            min-height: 200px;
            background-color: #e9ecef;
            border: 2px dashed #ced4da;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #6c757d;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .add-note:hover {
            background-color: #dee2e6;
        }

        .note-yellow { background-color: #fff9c4; }
        .note-blue { background-color: #bbdefb; }
        .note-pink { background-color: #ffcdd2; }
        .note-orange { background-color: #ffe0b2; }
        .note-green { background-color: #c8e6c9; }
    </style>

    <div class="container py-5">
        <h1 class="mb-4 text-center">Sticky Wall</h1>
        <div id="stickyWall" class="d-flex flex-wrap gap-3 justify-content-center">

            @php
                $colors = ['note-yellow', 'note-blue', 'note-pink', 'note-orange', 'note-green'];
            @endphp

            @foreach($notes as $note)
                <div class="sticky-note card {{ $colors[$loop->index % count($colors)] }}" data-id="{{ $note->id }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $note->title }}</h5>
                        <p class="card-text">{!! nl2br(e($note->content)) !!}</p>
                    </div>
                </div>
            @endforeach

            <!-- Botón para añadir nueva nota -->
            <div id="addNote" class="sticky-note add-note">
                <span>+</span>
            </div>

        </div>
    </div>
@endsection


