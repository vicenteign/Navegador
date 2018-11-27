@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ficha Personal Profesor</div>
                <div class="card-body">
                    <h8>
                        <b>RUT:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="rut" class="form-control-plaintext" value="{{$profesor->rut}}">
                        </div>
                        <b>Nombres:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="nombres" class="form-control-plaintext" value="{{$profesor->nombres}}">
                        </div>
                        <b>Apellido Paterno:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellido_paterno" class="form-control-plaintext" value="{{$profesor->apellido_paterno}}">
                        </div>
                        <b>Apellido Materno:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellido_materno" class="form-control-plaintext" value="{{$profesor->apellido_materno}}">
                        </div>
                    </h8>
                </div>
            </div>
        </br>
            <div class="card">
                <div class="card-header">Asignaturas en Curso</div>
                <div class="card-body">

                @php $actualYear = date('Y'); $currentSemestre = -1; $count = 0; @endphp

                @if(($cursos_profesor != NULL) && (count($cursos_profesor)>0) && ($cursos_profesor[0]->anyo == $actualYear))
                    <h4>{{$actualYear}}</h4>
                    @foreach($cursos_profesor as $key => $cursos)
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
                            <div class="list-group">
                                <a href="{{url('/detalleCursoProfesor', [$cursos->id_curso])}}" class="list-group-item list-group-item-action" id="id_cursoProfesor{{ $cursos->id_curso }}" title="Presione para acceder al curso" style="width:50%; margin-left:3em">
                                        {{ $cursos->sigla_curso }}
                                        {{ $cursos->nombre }}</a>
                            </div>
                        @endif
                    @endforeach
                @else
                </br>
                    <h4 align="center">El profesor no tiene cursos activos.</h4>
                </br>
                @endif
                </div>
            </div>
        </br>
            <div class="card">
                <div class="card-header">Asignaturas Dictadas</div>
                <div class="card-body">

                @php $init = 0; $currentSemestre = -1 @endphp
                @if(($cursos_profesor != NULL) && (count($cursos_profesor)>0) && (count($cursos_profesor) != $count))
                    @php $currentYear = $actualYear @endphp
                    @foreach($cursos_profesor as $key => $cursos)
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
                                    <a href="{{url('/detalleCursoProfesor', [$cursos->id_curso])}}" class="list-group-item list-group-item-action" id="id_cursoProfesor{{ $cursos->id_curso }}" title="Presione para acceder al curso" style="width:50%; margin-left:3em">
                                            {{ $cursos->sigla_curso }}
                                            {{ $cursos->nombre }}</a>
                        @endif
                    @endforeach
                </table>
                @else
                </br>
                    <h4 align="center">El profesor no ha tenido cursos.</h4>
                </br>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
