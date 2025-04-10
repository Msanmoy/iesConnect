@extends('layouts.app')

@section('title', 'Calendario')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center">Calendario</h1>

        {{-- Mensajes de sesión --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div id="calendar"></div>
    </div>
@endsection

@push('scripts')
    {{-- FullCalendar CSS y JS desde archivos locales --}}
    <link href="{{ asset('vendor/fullcalendar/index.global.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/fullcalendar/index.global.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: '{{ route('calendario.eventos') }}',
                eventClick: function (info) {
                    if (info.event.url) {
                        window.open(info.event.url, '_blank');
                        info.jsEvent.preventDefault();
                    }
                }
            });

            calendar.render();
        });
    </script>
@endpush
