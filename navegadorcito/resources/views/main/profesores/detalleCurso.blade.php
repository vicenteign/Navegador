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
                        <b>Cursada en:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellido_paterno" class="form-control-plaintext" value="{{$detalle_instancia->anyo}} / @if($detalle_instancia->semestre == 1)Primer Semestre @else Segundo Semestre @endif">
                        </div>
                        <b>Estado:</b>
                        <div class="col-sm-10">
                            @if($detalle_matricula != NULL)
                                <input type="text" readonly id="apellido_paterno" class="form-control-plaintext" value="{{$detalle_matricula->estado}}">
                            @else
                                <input type="text" readonly id="apellido_paterno" class="form-control-plaintext" value="No Disponible">
                            @endif
                        </div>
                    </h8>
                </div>
            </div>
        </br>
            <div class="card">
                <div class="card-header">Alumnos Matriculados</div>
                <div class="card-body">

                @if(($alumnos != NULL) && (count($alumnos)>0))
                <table id="tablaAlumnosCurso" style="width:90%; margin:20px;">
                    <tr>
                        <th>RUT</th>
                        <th>Nombres</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Ficha</th>
                    </tr>
                    @foreach($alumnos as $key => $alumno)
                    <tr id="id_alumnoCurso{{ $alumno->id_alumno }}">
                        <td scope="col">{{ $alumno->rut }}</td>
                        <td scope="col">{{ $alumno->nombres }}</td>
                        <td scope="col">{{ $alumno->apellido_paterno }}</td>
                        <td scope="col">{{ $alumno->apellido_materno }}</td>
                        <td scope="col"><a class="btn btn-secondary btn-sm" role="button" href="{{url('/fichaAlumnoProfesor', [$alumno->id_alumno])}}"><b>Ver Ficha</b></a>
                    </tr>
                    @endforeach
                </table>
                @else
                </br>
                    <h4 align="center">El curso no tiene alumnos inscritos.</h4>
                </br>
                @endif

                </div>
            </div>
        </br>
            <a class="btn btn-primary btn-lg" role="button" href="{{url('fichaProfesor')}}"><b>Volver</b></a>
        </div>
    </div>
</div>
@endsection
