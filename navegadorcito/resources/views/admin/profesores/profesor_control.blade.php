@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Información del Profesor</div>
                <div class="card-body">
                    <h5>
                        <b>Nombres:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="nombreProfesor" class="form-control-plaintext" value="{{$profesor->nombres}}">
                        </div>
                        <b>Apellido Paterno:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellidoPaternoProfesor" class="form-control-plaintext" value="{{$profesor->apellido_paterno}}">
                        </div>
                        <b>Apellido Materno:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="apellidoMaternoProfesor" class="form-control-plaintext" value="{{$profesor->apellido_materno}}">
                        </div>
                        <b>RUT:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="rutProfesor" class="form-control-plaintext" value="{{$profesor->rut}}">
                        </div>
                    </h5>
                    <a class="btn btn-primary btn-md" id='changesButton' role="button" onclick="saveChanges()" hidden>Guardar Cambios</a>
                </div>
            </div>
        </br>
            <div class="card">
                <div class="card-header">Cursos del Profesor</div>
                <div class="card-body">

                @if(($cursos_profesor != NULL) && (count($cursos_profesor)>0))
                <table id="tablaCursosAlumno" style="width:90%; margin:20px;">
                    <tr>
                        <th>Sigla</th>
                        <th>Nombre</th>
                        <th>Semestre</th>
                        <th>Año</th>
                        <th>Opciones</th>
                    </tr>
                    @foreach($cursos_profesor as $key => $cursos)
                    <tr id="id_cursoAlumno{{ $cursos->id_curso }}">
                        <td scope="col">{{ $cursos->sigla_curso }}</td>
                        <td scope="col">{{ $cursos->nombre }}</td>
                        @if($cursos->semestre == 1)
                            <td scope="col" value = 1>Primero</td>
                        @else
                            <td scope="col" value = 2>Segundo</td>
                        @endif
                        <td scope="col">{{ $cursos->anyo }}</td>
                        <td scope="col"><a class="btn btn-secondary btn-sm" role="button" onclick="removeCurso({{ $profesor->id_profesor }}, {{ $cursos->id_instcurso }})"><b>Eliminar</b></a>
                    </tr>
                    @endforeach
                </table>
                @else
                </br>
                    <h4 align="center">El profesor no tiene ningún curso.</h4>
                </br>
                @endif
                </div>
            </div>
        </br>
            <a class="btn btn-primary btn-lg" role="button" href="{{url('adminProfesores')}}"><b>Volver</b></a>
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
                    Agregar a Curso:
                </br>
                    <a class="btn btn-outline-primary btn-sm" id="enableChangesButton" role="button" href="{{url('/addProfesorToCurso', [$profesor->id_profesor])}}">Agregar</a>
                </h6>
            <br>
                <h6>
                    Borrar Profesor:
                </br>
                    <a class="btn btn-outline-primary btn-sm" role="button" onclick="deleteProfesor({{$profesor->id_profesor}})">Borrar</a>
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

    function changeStatus()
    {
        if(document.getElementById("nombreProfesor").readOnly)
        {
            document.getElementById("nombreProfesor").readOnly = false;
            document.getElementById("apellidoPaternoProfesor").readOnly = false;
            document.getElementById("apellidoMaternoProfesor").readOnly = false;
            document.getElementById("rutProfesor").readOnly = false;
            document.getElementById("changesButton").hidden = false;
            document.getElementById("enableChangesButton").hidden = true;
            return;
        }
        document.getElementById("nombreProfesor").readOnly = true;
        document.getElementById("apellidoPaternoProfesor").readOnly = true;
        document.getElementById("apellidoMaternoProfesor").readOnly = true;
        document.getElementById("rutProfesor").readOnly = true;
        document.getElementById("enableChangesButton").hidden = false;
        document.getElementById("changesButton").hidden = true;
        return;
    }

    function saveChanges()
    {
        var updatedCurso, json_text;

        updatedCurso = Array();
        updatedCurso[0] = document.getElementById("nombreProfesor").value;
        updatedCurso[1] = document.getElementById("apellidoPaternoProfesor").value;
        updatedCurso[2] = document.getElementById("apellidoMaternoProfesor").value;
        updatedCurso[3] = document.getElementById("rutProfesor").value;
        updatedCurso[4] = "{{$profesor->id_profesor}}";

        json_text = JSON.stringify(updatedCurso);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: {DATA:json_text},
            url: "{{url('/profesorControl/updateProfesor')}}",
            success: function(msg){
                alert(msg);
            }
        });
        changeStatus();
        return;
    }

    function deleteProfesor(data)
    {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: {DATA:data},
            url: "{{url('/profesorControl/deleteProfesor')}}",
            success: function(response){
                console.log(response);
                window.location.href = "{{url('/adminProfesores')}}";
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
            url: "{{url('/profesorControl/deleteCurso')}}",
            success: function(response){
                console.log(response);
                window.location.href = "{{url('/profesorControl', [$profesor->id_profesor])}}";
            }
        });
    }
</script>
@endsection
