@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Información del Alumno</div>
                <div class="card-body">
                    <h5>
                        <b>Nombres:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="nombreAlumno" class="form-control-plaintext" value="{{$alumno[0]->nombres}}">
                        </div>
                        <b>Apellido Paterno:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellidoPaternoAlumno" class="form-control-plaintext" value="{{$alumno[0]->apellido_paterno}}">
                        </div>
                        <b>Apellido Materno:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellidoMaternoAlumno" class="form-control-plaintext" value="{{$alumno[0]->apellido_materno}}">
                        </div>
                        <b>RUT:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="rutAlumno" class="form-control-plaintext" value="{{$alumno[0]->rut}}">
                        </div>
                    </h5>
                </div>
                <div class="card-body">
                    <h5>
                        <b>Correo:</b>
                        <div class="col-sm-10">
                            <input type="email" class="form-control-plaintext" readonly id="correoAlumno" value="{{$alumno[0]->email}}">
                        </div>
                        <b>Año Matricula:</b>
                        <div class="col-sm-10">
                            <select class="custom-select" id="inputYear" disabled style="width:150px; margin:8px" onload="loadYears()">
                                <option selected value="{{$alumno[0]->anyo}}">{{$alumno[0]->anyo}}</option>
                            </select>
                        </div>
                    </h5>
                    <br>
                    <a class="btn btn-primary btn-md" id='changesButton' role="button" onclick="saveChanges()" hidden>Guardar Cambios</a>
                </div>
            </div>
        </br>
            <div class="card">
                <div class="card-header">Cursos del Alumno</div>
                <div class="card-body">

                @if(($cursos_alumno != NULL) && (count($cursos_alumno)>0))
                <table id="tablaCursosAlumno" style="width:90%; margin:20px;">
                    <tr>
                        <th>Sigla</th>
                        <th>Nombre</th>
                        <th>Semestre</th>
                        <th>Año</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                    @foreach($cursos_alumno as $key => $cursos)
                    <tr id="id_cursoAlumno{{ $cursos->id_curso }}">
                        <td scope="col">{{ $cursos->sigla_curso }}</td>
                        <td scope="col">{{ $cursos->nombre }}</td>
                        @if($cursos->semestre == 1)
                            <td scope="col" value = 1>Primero</td>
                        @else
                            <td scope="col" value = 2>Segundo</td>
                        @endif
                        <td scope="col">{{ $cursos->anyo }}</td>
                        <td scope="col">{{ $cursos->estado }}</td>
                        <td scope="col"><a class="btn btn-secondary btn-sm" role="button" onclick="removeCurso({{ $alumno[0]->id_alumno }}, {{ $cursos->id_curso }})"><b>Eliminar</b></a>
                    </tr>
                    @endforeach
                </table>
                @else
                </br>
                    <h4 align="center">El alumno no tiene cursos inscritos.</h4>
                </br>
                @endif
                </div>
            </div>
        </br>
            <a class="btn btn-primary btn-lg" role="button" href="{{url('adminAlumnos')}}"><b>Volver</b></a>
        </div>
        <div class="card">
            <div class="card-header">Opciones de Administración</div>
            <div class="card-body">
                <h6>
                    Edicion de datos:
                </br>
                    <a class="btn btn-outline-primary btn-sm" id="enableChangesButton" role="button" onclick="changeStatus()">Habilitar/Deshabilitar</a>
                </h6>
            <br>
                <h6>
                    Borrar Alumno:
                </br>
                    <a class="btn btn-outline-primary btn-sm" role="button" onclick="deleteAlumno({{$alumno[0]->id_alumno}})">Borrar</a>
                </h6>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    window.onload = function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    window.onload = function loadYears()
    {
        var start = 1980;
        var end = new Date().getFullYear();
        var options = "";
        var flag = 0;
        for(var year = end ; year >= start; year--)
        {
            if((year == {{$alumno[0]->anyo}} && flag == 0))
            {
                options += "<option selected value='{{$alumno[0]->anyo}}'>"+ year +"</option>";
                flag = 1;
            }
            options += "<option value" + year + ">"+ year +"</option>";
        }
        document.getElementById("inputYear").innerHTML = options;
    }

    function changeStatus()
    {
        if(document.getElementById("nombreAlumno").readOnly)
        {
            document.getElementById("nombreAlumno").readOnly = false;
            document.getElementById("apellidoPaternoAlumno").readOnly = false;
            document.getElementById("apellidoMaternoAlumno").readOnly = false;
            document.getElementById("rutAlumno").readOnly = false;
            document.getElementById("correoAlumno").readOnly = false;
            document.getElementById("changesButton").hidden = false;
            document.getElementById("enableChangesButton").hidden = true;
            return;
        }
        document.getElementById("nombreAlumno").readOnly = true;
        document.getElementById("apellidoPaternoAlumno").readOnly = true;
        document.getElementById("apellidoMaternoAlumno").readOnly = true;
        document.getElementById("rutAlumno").readOnly = true;
        document.getElementById("correoAlumno").readOnly = true;
        document.getElementById("enableChangesButton").hidden = false;
        document.getElementById("changesButton").hidden = true;
        return;
    }

    function saveChanges()
    {
        var updatedCurso, json_text;

        updatedCurso = Array();
        updatedCurso[0] = document.getElementById("nombreAlumno").value;
        updatedCurso[1] = document.getElementById("apellidoPaternoAlumno").value;
        updatedCurso[2] = document.getElementById("apellidoMaternoAlumno").value;
        updatedCurso[3] = document.getElementById("rutAlumno").value;
        updatedCurso[4] = document.getElementById("correoAlumno").value;
        updatedCurso[5] = "{{$alumno[0]->id_alumno}}";

        json_text = JSON.stringify(updatedCurso);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: {DATA:json_text},
            url: "{{url('/alumnoControl/updateAlumno')}}",
            success: function(msg){
                alert(msg);
            }
        });
        changeStatus();
        return;
    }

    function deleteAlumno(data)
    {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: {DATA:data},
            url: "{{url('/alumnoControl/deleteAlumno')}}",
            success: function(response){
                console.log(response);
                window.location.href = response.redirect;
            }
        });
    }

    function removeCurso(data1, data2)
    {
        var data, json_text;

        data = Array();
        data[0] = data1;
        data[1] = data2;
        json_text = JSON.stringify(data);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: {DATA:json_text},
            url: "{{url('/cursoControl/deleteInscripcion')}}",
            success: function(response){
                console.log(response);
                window.location.href = "{{url('/alumnoControl', [$alumno[0]->id_alumno])}}";
            }
        });
    }
</script>
@endsection
