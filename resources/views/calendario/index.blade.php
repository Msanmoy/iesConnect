@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Calendario</h1>

        <div id="calendar"></div>
    </div>
@endsection

@vite('resources/js/calendar.js')
@vite('resources/css/calendar.css')


