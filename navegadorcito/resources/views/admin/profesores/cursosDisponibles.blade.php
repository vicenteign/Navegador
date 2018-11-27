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
                            <input class="form-control" type="text" id="inputSigla" onkeyup="filterSigla()" placeholder="Ingrese la Sigla del Curso" title="Sigla del Curso">
                        </div>
                        @if(($cursos_disponibles != null) && (sizeof($cursos_disponibles) > 0))
                        <table id="tablaAdministracion" style="width:90%; margin:20px;" align="center">
                            <tr>
                                <th>Sigla</th>
                                <th>Nombre</th>
                                <th>Año</th>
                                <th>Semestre</th>
                                <th>Opciones</th>
                            </tr>
                            @foreach($cursos_disponibles as $key => $cursos)
                                <tr id="id_alumnoSistema{{ $cursos->id_curso }}">
                                    <td scope="col">{{ $cursos->sigla }}</td>
                                    <td scope="col">{{ $cursos->nombre }}</td>
                                    <td scope="col">{{ $cursos->anyo }}</td>
                                    @if($cursos->semestre == 1)
                                        <td scope="col">Primer</td>
                                    @else
                                        <td scope="col">Segundo</td>
                                    @endif
                                    <td><a class="btn btn-outline-success my-2 my-sm-0" onclick="registerCurso({{ $cursos->id_instcurso }}, {{ $profesor->id_profesor }})" role="button" style="cursor: pointer;">Registrar</a></td>
                                </tr>
                            @endforeach
                        </table>
                        @else
                        <br>
                            <h4 align="center">No hay cursos disponibles.</h4>
                        <br>
                        @endif
                    </div>
            </div>
        </br>
            <a class="btn btn-primary btn-lg" role="button" href="{{url('profesorControl', $profesor->id_profesor)}}"><b>Volver</b></a>
        </div>
    </div>
</div>
<script type="text/javascript">
    function filterSigla()
    {
        var inputSigla, table, tr, tdSigla, i, filter;
        inputSigla = document.getElementById("inputSigla");
        filter = inputSigla.value.toUpperCase();
        table = document.getElementById("tablaAdministracion");
        tr = table.getElementsByTagName("tr");

        console.log(filter);

        for (i = 0; i < tr.length; i++)
        {
            tdSigla = tr[i].getElementsByTagName("td")[0];
            if(tdSigla)
            {
                if ((tdSigla.innerHTML.indexOf(filter) > -1))
                    tr[i].style.display = "";
                else
                    tr[i].style.display = "none";
            }
        }
    }
    function registerCurso(data1, data2)
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
            url: "{{url('/profesorControl/addCurso')}}",
            success: function(response){
                window.location.href = "{{url('adminProfesores')}}";
            }
        });
    }
</script>
@endsection
