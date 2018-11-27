@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Informacion del Curso</div>
                <div class="card-body">
                    <h5>
                        <b>Nombre:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="cursoNombre" class="form-control-plaintext" value="{{$curso_seleccionado[0]->nombre}}">
                        </div>
                        <b>Sigla:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="cursoSigla" class="form-control-plaintext" value="{{$curso_seleccionado[0]->sigla_curso}}">
                        </div>
                        <b>Descripcion:</b>
                        <div class="col-sm-10">
                            <input type="text" readonly id="cursoDescripcion" class="form-control-plaintext" value="{{$curso_seleccionado[0]->descripcion}}">
                        </div>
                    </h5>
                    </div>
                    <div class="card-body">
                    <h5>
                        <b>Semestre:</b>
                        <br>
                        <div class="col-sm-10">
                            <select class="custom-select" id="inputSemestre" disabled style="width:250px">
                                @if($curso_seleccionado[0]->semestre == 1)
                                    <option selected value="1">Primer Semestre</option>
                                    <option value="2">Segundo Semestre</option>
                                @else
                                    <option selected value="2">Segundo Semestre</option>
                                    <option value="1">Primer Semestre</option>
                                @endif
                            </select>
                        </div>
                        <b>Año:</b>
                        <div class="col-sm-10">
                            <select class="custom-select" id="inputYear" disabled style="width:250px" onload="loadYears()">
                                <option selected value="{{$curso_seleccionado[0]->anyo}}">{{$curso_seleccionado[0]->anyo}}</option>
                            </select>
                        </div>
                    </h5>
                    <br>
                    <a class="btn btn-primary btn-md" id='changesButton' role="button" onclick="saveChanges()" hidden>Guardar Cambios</a>
                </div>
            </div>
        </br>
            <div class="card">
                <div class="card-header">Alumnos Matriculados</div>
                <div class="card-body">

                @if(count($alumnos_curso)>0)
                <table id="tablaAlumnosCurso" style="width:90%; margin:20px;">
                    <tr>
                        <th>RUT</th>
                        <th>Nombre</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Opciones</th>
                    </tr>
                    @foreach($alumnos_curso as $key => $alumno)
                    <tr id="id_alumnoCurso{{ $alumno->id_alumno }}">
                        <td scope="col">{{ $alumno->rut }}</td>
                        <td scope="col">{{ $alumno->nombres }}</td>
                        <td scope="col">{{ $alumno->apellido_paterno }}</td>
                        <td scope="col">{{ $alumno->apellido_materno }}</td>
                        <td scope="col"><a class="btn btn-secondary btn-sm" role="button" onclick="removeStudent({{ $alumno->id_alumno }}, {{ $curso_seleccionado[0]->id_curso }})"><b>Eliminar</b></a>
                    </tr>
                    @endforeach
                </table>
                @else
                </br>
                    <h4 align="center">No hay alumnos inscritos en el curso.</h4>
                </br>
                @endif
                </div>
            </div>
        </br>
            <a class="btn btn-primary btn-lg" role="button" href="{{url('adminCursos')}}"><b>Volver</b></a>
        </div>
        <div class="card">
            <div class="card-header">Opciones de Administración</div>
            <div class="card-body">
                <h6>
                    Edicion de datos:
                </br>
                    <a class="btn btn-outline-primary btn-sm btn-sm" id="enableChangesButton" role="button" onclick="changeStatus()">Habilitar/Deshabilitar</a>
                </h6>
            </br>
                <h6>
                    Agregar Alumno:
                </br>
                    <a class="btn btn-outline-primary btn-sm btn-sm" role="button" href="{{url('/addAlumnoToCurso', [$curso_seleccionado[0]->id_instcurso])}}">Agregar</a>
                </h6>
            </br>
                <h6>
                    Borrar Curso:
                </br>
                    <a class="btn btn-outline-primary btn-sm btn-sm" role="button" onclick="deleteCurso({{$curso_seleccionado[0]->id_curso}})">Borrar</a>
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
            if((year == {{$curso_seleccionado[0]->anyo}} && flag == 0))
            {
                options += "<option selected value='{{$curso_seleccionado[0]->anyo}}'>"+ year +"</option>";
                flag = 1;
            }
            options += "<option value" + year + ">"+ year +"</option>";
        }
        document.getElementById("inputYear").innerHTML = options;
    }

    function changeStatus()
    {
        if(document.getElementById("cursoNombre").readOnly)
        {
            document.getElementById("cursoNombre").readOnly = false;
            document.getElementById("cursoSigla").readOnly = false;
            document.getElementById("inputSemestre").disabled = false;
            document.getElementById("cursoDescripcion").readOnly = false;
            document.getElementById("inputYear").disabled = false;
            document.getElementById("changesButton").hidden = false;
            document.getElementById("enableChangesButton").hidden = true;
            return;
        }
        document.getElementById("cursoNombre").readOnly = true;
        document.getElementById("cursoSigla").readOnly = true;
        document.getElementById("inputSemestre").disabled = true;
        document.getElementById("cursoDescripcion").readOnly = true;
        document.getElementById("inputYear").disabled = true;
        document.getElementById("changesButton").hidden = true;
        document.getElementById("enableChangesButton").hidden = false;
        return;
    }

    function saveChanges()
    {
        var updatedCurso, json_text;

        updatedCurso = Array();
        updatedCurso[0] = document.getElementById("cursoNombre").value;
        updatedCurso[1] = document.getElementById("cursoSigla").value;
        updatedCurso[2] = document.getElementById("inputSemestre").value;
        if(updatedCurso[2] == 'Segundo Semestre')
            updatedCurso[2] = 2;
        else if(updatedCurso[2] == 'Primer Semestre')
            updatedCurso[2] = 1;
        updatedCurso[3] = document.getElementById("cursoDescripcion").value;
        updatedCurso[4] = document.getElementById("inputYear").value;
        updatedCurso[5] = "{{$curso_seleccionado[0]->id_curso}}";

        json_text = JSON.stringify(updatedCurso);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: {DATA:json_text},
            url: "{{url('/cursoControl/updateCurso')}}",
            success: function(msg){
                console.log(msg);
            }
        });
        changeStatus();
    }

    function deleteCurso(data)
    {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: {DATA:data},
            url: "{{url('cursoControl/deleteCurso')}}",
            success: function(response){
                window.location.href = response.redirect;
            }
        });
    }

    function removeStudent(data1, data2)
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
            url: "{{url('/alumnoControl/deleteInscripcion')}}",
            success: function(response){
                console.log(response);
                window.location.href = "{{url('/cursoControl', [$curso_seleccionado[0]->id_curso])}}";
            }
        });
    }
</script>
@endsection
