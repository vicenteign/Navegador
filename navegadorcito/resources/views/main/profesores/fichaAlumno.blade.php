@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ficha Personal Alumno</div>
                <div class="card-body">
                    <h8>
                        <b>RUT:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="rut" class="form-control-plaintext" value="{{$alumno->rut}}">
                        </div>
                        <b>Nombres:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="nombres" class="form-control-plaintext" value="{{$alumno->nombres}}">
                        </div>
                        <b>Apellido Paterno:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellido_paterno" class="form-control-plaintext" value="{{$alumno->apellido_paterno}}">
                        </div>
                        <b>Apellido Materno:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellido_materno" class="form-control-plaintext" value="{{$alumno->apellido_materno}}">
                        </div>
                    </h8>
                </div>
            </div>
        </br>
            <div class="card">
                <div class="card-header">Asignaturas en Curso</div>
                <div class="card-body">

                @php $actualYear = date('Y'); $currentSemestre = -1; $count = 0; @endphp

                @if(($cursos_alumno != NULL) && (count($cursos_alumno)>0) && ($cursos_alumno[0]->anyo == $actualYear))
                    <h4>{{$actualYear}}</h4>
                    @foreach($cursos_alumno as $key => $cursos)
                        @if($actualYear == $cursos->anyo)
                            @if($currentSemestre != $cursos->semestre)
                                @php $init = 1 @endphp
                                @if($cursos->semestre == 1)
                                    </br>
                                    <h5 style="margin-left:2em">Primer Semestre</h5>
                                @else
                                    <h5 style="margin-left:2em">Segundo Semestre</h5>
                                @endif
                                @php $currentSemestre = $cursos->semestre; @endphp
                            @endif
                            @php $count++ @endphp
                            @if($profesor->id_profesor == $cursos->id_profesor_fk)
                                <a href="{{url('/detalleCursoProfesor', [$cursos->id_curso])}}" class="list-group-item list-group-item-action" id="id_cursoAlumno{{ $cursos->id_curso }}" title="Presione para acceder al curso" style="width:50%; margin-left:3em">
                                        {{ $cursos->sigla_curso }}
                                        {{ $cursos->nombre }}</a>
                            @else
                                <a class="list-group-item list-group-item-action" id="id_cursoAlumno{{ $cursos->id_curso }}" style="width:50%; margin-left:3em">
                                        {{ $cursos->sigla_curso }}
                                        {{ $cursos->nombre }}</a>
                            @endif
                        @endif
                    @endforeach
                @else
                </br>
                    <h4 align="center">El alumno no tiene cursos activos.</h4>
                </br>
                @endif
                </div>
            </div>
        </br>
            <div class="card">
                <div class="card-header">Asignaturas Cursadas</div>
                <div class="card-body">

                @php $init = 0; $currentSemestre = -1 @endphp
                @if(($cursos_alumno != NULL) && (count($cursos_alumno)>0) &&  (count($cursos_alumno) != $count))
                    @php $currentYear = $actualYear @endphp
                    @foreach($cursos_alumno as $key => $cursos)
                        @if($actualYear != $cursos->anyo)
                            @if($init != 0)
                                </div>
                            @endif
                            @if($currentYear != $cursos->anyo)
                                </br>
                                <h4>{{$cursos->anyo}}</h4>
                                @php $currentYear = $cursos->anyo; @endphp
                            @endif
                            @if($currentSemestre != $cursos->semestre)
                                @php $init = 1 @endphp
                                @if($cursos->semestre == 1)
                                    </br>
                                    <h5 style="margin-left:2em">Primer Semestre</h5>
                                @else
                                    <h5 style="margin-left:2em">Segundo Semestre</h5>
                                @endif
                                @php $currentSemestre = $cursos->semestre; @endphp
                            @endif
                                <div class="list-group">
                                    @if($profesor->id_profesor == $cursos->id_profesor_fk)
                                        <a href="{{url('/detalleCursoProfesor', [$cursos->id_curso])}}" class="list-group-item list-group-item-action" id="id_cursoAlumno{{ $cursos->id_curso }}" title="Presione para acceder al curso" style="width:50%; margin-left:3em">
                                                {{ $cursos->sigla_curso }}
                                                {{ $cursos->nombre }}</a>
                                    @else
                                        <a class="list-group-item list-group-item-action" id="id_cursoAlumno{{ $cursos->id_curso }}" style="width:50%; margin-left:3em">
                                                {{ $cursos->sigla_curso }}
                                                {{ $cursos->nombre }}</a>
                                    @endif
                        @endif
                    @endforeach
                @else
                </br>
                    <h4 align="center">El alumno no ha tenido cursos.</h4>
                </br>
                @endif
                </div>
            </div>
        </div>
    </br>
        <a class="btn btn-primary btn-lg" role="button" href="{{url('fichaProfesor')}}"><b>Volver</b></a>
    </div>
</div>
@endsection
