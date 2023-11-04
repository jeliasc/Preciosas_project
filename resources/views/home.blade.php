@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div  class="text-right" style="margin-right: 2em">
    @if (Route::has('login'))
        <div class="row">
            @auth
                <a href="{{ url('home') }}" class="text-sm text-gray-700 dark:text-gray-500 underline"></a>
            @else
            <div  class="col-md-11">                
                <h6><a href="{{ route('login') }}" class="btn btn-outline-success" type="button">Login</a></h6>
            </div>
                @if (Route::has('register'))
            <div  class="col-md-1">
                    <h6><a href="{{ route('register') }}" class="btn btn-outline-danger" type="button">Register</a></h6>
            </div>
                @endif
            @endauth
        </div>
    @endif
</div>

@stop

@section('content')
<style>
    #img{
        float: center;
        margin-top: 0px;
    }
</style>
<div id="img">   
    <p align="center"><img src="vendor/adminlte/dist/img/preciosas6.jpg" width="100%"/></p>
</div>
@stop

@section('css')
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop