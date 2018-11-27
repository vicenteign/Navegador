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
                                <label class="input-group-text" for="inputGroupSelect01">Filtros</label>
                            </div>
                            <select class="custom-select" id="inputYear" onchange="filterYear()"></select>
                            <a class="btn btn-secondary btn-sm" role="button" href="{{url('addCurso')}}"><b>Agregar Curso</b></a>
                        </div>
                        @if(($cursos_almacenados != NULL) && (count($cursos_almacenados)>0))
                        <table id="tablaAdministracion" style="width:90%; margin:20px;" align="center">
                            <tr>
                                <th>Sigla</th>
                                <th>Nombre</th>
                                <th>Año</th>
                                <th>Semestre</th>
                                <th>Opciones</th>
                            </tr>
                            @foreach($cursos_almacenados as $curso)
                            <tr id="idCurso_{{ $curso->id_curso }}">
                                <td scope="col">{{ $curso->sigla }}</td>
                                <td scope="col">{{ $curso->nombre }}</td>
                                <td scope="col">{{ $curso->anyo }}</td>
                                @if($curso->semestre == 1)
                                    <td scope="col" value = 1>Primero</td>
                                @else
                                    <td scope="col" value = 2>Segundo</td>
                                @endif
                                <td><a class="btn btn-outline-success my-2 my-sm-0" href="{{url('cursoControl', [$curso->id_curso])}}" role="button" style="cursor: pointer;">Acceder</a></td>
                            </tr>
                            @endforeach
                        </table>
                        @else
                            </br>
                            <h4 align="center">No hay cursos registrados en el sistema</h4>
                            </br>
                        @endif
                    </div>
            </div>
        </br>
            <a class="btn btn-primary btn-lg" role="button" href="{{url('admin')}}"><b>Volver</b></a>
        </div>
    </div>
</div>
<script type="text/javascript">
    window.onload = function loadYears()
    {
        var start = 1980;
        var end = new Date().getFullYear();
        var options = "";
        var flag = 0;
        options += "<option value=0 >Año...</option>";
        for(var year = end ; year >= start; year--)
        {
            options += "<option value" + year + ">"+ year +"</option>";
        }
        document.getElementById("inputYear").innerHTML = options;
    }
    function filterYear()
    {
        var input, table, tr, tdYear, i;
        inputYear = document.getElementById("inputYear").value;
        table = document.getElementById("tablaAdministracion");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++)
        {
            tdYear = tr[i].getElementsByTagName("td")[2];
            if(tdYear)
            {
                if ((tdYear.innerHTML.indexOf(inputYear) > -1))
                    tr[i].style.display = "";
                else
                    tr[i].style.display = "none";
            }
        }
    }
</script>
@endsection
