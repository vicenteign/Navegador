@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Agregar nuevo curso</div>
                <div class="card-body">
                    <form method="POST" name="nuevoCurso" id="nuevoCursoForm">
                        <div class="form-group">
                            <label>Ingrese el nombre:</label>
                            <input type="text" class="form-control" aria-describedby="nameCurso" placeholder="Nombre del Curso" name="nameCurso">
                        </div>
                        <div class="form-group">
                            <label>Ingrese la sigla del curso:</label>
                            <input type="text" class="form-control" aria-describedby="siglaCurso" placeholder="Sigla del Curso" name="siglaCurso">
                        </div>
                        <div class="form-group">
                            <label>Seleccione el semestre del curso:</label>
                            <select class="custom-select" id="inputSemestre" aria-describedby="semestreCurso" name="semestreCurso">
                                    <option selected value="1">Primer Semestre</option>
                                    <option value="2">Segundo Semestre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Seleccione el año del curso:</label>
                            <select class="custom-select" id="inputYear" aria-describedby="yearCurso" name="yearCurso">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Descripcion</label>
                            <textarea class="form-control" id="descripcionCurso" rows="3" placeholder="Descripcion del Curso" name="descripcionCurso"></textarea>
                        </div>
                    </form>
                    <a class="btn btn-primary btn-md" id='saveCurso' role="button" onclick="saveCurso()">Guardar Cambios</a>
                </div>
            </div>
        </br>
            <a class="btn btn-primary btn-lg" role="button" href="{{url('adminCursos')}}"><b>Volver</b></a>
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
        for(var year = end ; year >= start; year--)
        {
            options += "<option>"+ year +"</option>";
        }
        document.getElementById("inputYear").innerHTML = options;
    }
    function saveCurso()
    {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: $('#nuevoCursoForm').serialize(),
            url: "{{url('cursoControl/addCurso')}}",
            success: function(response){
                if(response != 1)
                    alert(response);
                else
                    window.location.href = "{{url('adminCursos')}}";
            }
        });
    }
</script>
@endsection
