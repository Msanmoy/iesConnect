@extends('layouts.app')

@section('content')
    @push('styles')
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
                border-radius: 16px;
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
                padding: 1rem;
                background-color: #fff;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
                animation: fadeIn 0.4s ease;
            }

            .sticky-note:hover {
                transform: scale(1.04);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
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
                border-radius: 16px;
                cursor: pointer;
                transition: background-color 0.2s ease, transform 0.2s ease;
            }

            .add-note:hover {
                background-color: #dee2e6;
                transform: scale(1.05);
            }

            .note-yellow { background-color: #fff9c4; }
            .note-blue { background-color: #bbdefb; }
            .note-pink { background-color: #ffcdd2; }
            .note-orange { background-color: #ffe0b2; }
            .note-green { background-color: #c8e6c9; }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .trash-zone {
                width: 100%;
                padding: 20px;
                text-align: center;
                color: #fff;
                background-color: #dc3545;
                font-weight: bold;
                font-size: 1.2rem;
                position: fixed;
                bottom: 0;
                left: 0;
                z-index: 999;
                transition: transform 0.3s ease, opacity 0.3s ease;
            }

            .trash-zone.active {
                opacity: 1;
                transform: translateY(0%);
            }

            .trash-zone.d-none {
                opacity: 0;
                transform: translateY(100%);
            }

        </style>
    @endpush

    <div class="container py-5">
        <h1 class="mb-4 text-center">Sticky Wall</h1>
        <div id="stickyWall" class="d-flex flex-wrap gap-3 justify-content-center">

            @php
                $colors = ['note-yellow', 'note-blue', 'note-pink', 'note-orange', 'note-green'];
            @endphp

            @foreach($notes as $note)
                <div class="sticky-note {{ $colors[$loop->index % count($colors)] }}" data-id="{{ $note->id }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $note->title }}</h5>
                        <p class="card-text">{!! nl2br(e($note->content)) !!}</p>
                    </div>
                </div>
            @endforeach

            <div id="addNote" class="sticky-note add-note">
                <span>+</span>
            </div>

        </div>

        <div id="trashZone" class="trash-zone d-none">
            <i class="bi bi-trash3"></i> Arrastra aquí para eliminar
        </div>
    </div>
@endsection
