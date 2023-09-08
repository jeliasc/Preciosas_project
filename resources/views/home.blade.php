@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

{{-- Notificaciones --}}

    @if(Session::has('home'))
        <p class="bg-primary">
            {{session('home')}}
        </p>
    @endif
    @if(Session::has('eAuth'))
        <p class="bg-primary">
            {{session('eAuth')}}
        </p>
    @endif

{{-- Fin Notificaciones --}}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop