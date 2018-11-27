@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detalle de la Asignatura</div>
                <div class="card-body">
                    <h8>
                        <b>Asignatura:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="rut" class="form-control-plaintext" value="{{$curso->sigla}} {{$curso->nombre}}">
                        </div>
                        <b>Descripcion:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="nombres" class="form-control-plaintext" value="{{$curso->descripcion}}">
                        </div>
                        <b>Profesor:</b>
                        <div class="col-sm-10">
                            @if($profesor != null)
                                <input type="text" readonly id="apellido_paterno" class="form-control-plaintext" value="{{$profesor->nombres}} {{$profesor->apellido_paterno}} {{$profesor->apellido_materno}}">
                            @else
                                <input type="text" readonly id="apellido_paterno" class="form-control-plaintext" value="No hay profesor asignado.">
                            @endif
                        </div>
                        <b>Cursada en:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellido_paterno" class="form-control-plaintext" value="{{$detalle_instancia->anyo}} / @if($detalle_instancia->semestre == 1)Primer Semestre @else Segundo Semestre @endif">
                        </div>
                        <b>Estado:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellido_paterno" class="form-control-plaintext" value="{{$detalle_matricula->estado}}">
                        </div>
                        <b>Nota Final:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellido_materno" class="form-control-plaintext" value="(aun no disponible)">
                        </div>
                    </h8>
                </div>
            </div>
        </br>
            <a class="btn btn-primary btn-lg" role="button" href="{{url('fichaAlumno')}}"><b>Volver</b></a>
        </div>
    </div>
</div>
@endsection
