@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Cursos</div>
                    <div class="card=body">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Filtro</label>
                            </div>
                            <input class="form-control" type="text" id="inputRUT" onkeyup="filterRUT()" placeholder="Ingrese el RUT" title="RUT del Alumno">
                        </div>
                        @if(($alumnos_almacenados != null) && (sizeof($alumnos_almacenados) > 0))
                        <table id="tablaAdministracion" style="width:90%; margin:20px;" align="center">
                            <tr>
                                <th>RUT</th>
                                <th>Nombre</th>
                                <th>Apellido Paterno</th>
                                <th>Apellido Materno</th>
                                <th>Opciones</th>
                            </tr>
                            @foreach($alumnos_almacenados as $key => $alumno)
                                <tr id="id_alumnoSistema{{ $alumno->id_alumno }}">
                                    <td scope="col">{{ $alumno->rut }}</td>
                                    <td scope="col">{{ $alumno->nombres }}</td>
                                    <td scope="col">{{ $alumno->apellido_paterno }}</td>
                                    <td scope="col">{{ $alumno->apellido_materno }}</td>
                                    <td><a class="btn btn-outline-success my-2 my-sm-0" onclick="addToCurso({{ $alumno->id_alumno }}, {{ $curso_data->id_instcurso }})" role="button" style="cursor: pointer;">Agregar al Curso</a></td>
                                </tr>
                            @endforeach
                        </table>
                        @else
                        <br>
                            <h4 align="center">No hay alumnos disponibles para agregar al curso</h4>
                        <br>
                        @endif
                    </div>
            </div>
        </br>
            <a class="btn btn-primary btn-lg" role="button" href="{{url('cursoControl', $curso_data->curso_id_curso)}}"><b>Volver</b></a>
        </div>
    </div>
</div>
<script type="text/javascript">
    function filterRUT()
    {
        var input, table, tr, tdYear, i;
        inputRUT = document.getElementById("inputRUT").value;
        table = document.getElementById("tablaAdministracion");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++)
        {
            tdYear = tr[i].getElementsByTagName("td")[0];
            if(tdYear)
            {
                if ((tdYear.innerHTML.indexOf(inputRUT) > -1))
                    tr[i].style.display = "";
                else
                    tr[i].style.display = "none";
            }
        }
    }
    function addToCurso(data1, data2)
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
            url: "{{url('/cursoControl/addAlumno')}}",
            success: function(response){
                window.location.href = "{{url('adminCursos')}}";
            }
        });
    }
</script>
@endsection
