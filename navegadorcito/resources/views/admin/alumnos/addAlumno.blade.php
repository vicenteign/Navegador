@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Agregar nuevo alumno</div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Usuario</label>
                        </div>
                        <select class="custom-select" id="verifyUser" onchange="validateStatus()">
                            <option default value="0">No Existente</option>
                            <option value="1">Existente</option>
                        </select>
                    </div>
                    <form method="POST" name="nuevoAlumno" id="nuevoAlumnoForm">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Usuario') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" aria-describedby="email" placeholder="Email del Alumno" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row" id="usernameForm">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre Usuario') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" aria-describedby="username" placeholder="Nombre de Usuario del Alumno" name="username" value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row" id="passwordForm">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <input id="password" type="password" class="form-control" aria-describedby="password" placeholder="Contraseña del Alumno" name="password" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Nombres del Alumno:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" aria-describedby="nameAlumno" placeholder="Nombre del Alumno" name="nameAlumno" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Apellido Paterno del Alumno:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" aria-describedby="paternoAlumno" placeholder="Apellido Paterno del Alumno" name="paternoAlumno" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Apellido Materno del Alumno:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" aria-describedby="maternoAlumno" placeholder="Apellido Materno del Alumno" name="maternoAlumno" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">RUT del alumno:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" aria-describedby="rutAlumno" placeholder="RUT del Alumno" name="rutAlumno" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Año:</label>
                            <div class="col-md-6">
                                <select class="custom-select" id="inputYear" aria-describedby="yearAlumno" name="yearAlumno"></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Semestre:</label>
                            <div class="col-md-6">
                                <select class="custom-select" id="inputSemestre" aria-describedby="semestreAlumno" name="semestreAlumno" required>
                                        <option selected value="1">Primer Semestre</option>
                                        <option value="2">Segundo Semestre</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Estado Matricula:</label>
                            <div class="col-md-6">
                                <select class="custom-select" id="inputSemestre" aria-describedby="matriculaAlumno" name="matriculaAlumno" required>
                                        <option selected value="1">Valida</option>
                                        <option value="2">Sancionada</option>
                                </select>
                            </div>
                        </div>
                        <input id="statusUser" aria-describedby="password" name="statusUser" value="0" hidden>
                    </form>
                    <div class="col-md-6">
                        <button class="btn btn-primary mb-2" id='saveCurso' onclick="saveAlumno()">Registrar Cambios</a>
                    </div>
                </div>
            </div>
        </br>
            <a class="btn btn-primary btn-lg" role="button" href="{{url('adminAlumnos')}}"><b>Volver</b></a>
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
    function saveAlumno()
    {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: $('#nuevoAlumnoForm').serialize(),
            url: "{{url('alumnoControl/addAlumno')}}",
            success: function(response){
                if(response != 1)
                {
                    alert(response);
                    console.log(response);
                }
                else
                    window.location.href = "{{url('adminAlumnos')}}";
            }
        });
    }
    function validateStatus()
    {
        var hiddenStatus;
        hiddenStatus = document.getElementById("verifyUser").value;
        if(hiddenStatus == 1)
        {
            document.getElementById("statusUser").value = 1;
            document.getElementById("usernameForm").hidden = true;
            document.getElementById("passwordForm").hidden = true;
            return;
        }
        document.getElementById("statusUser").value = 0;
        document.getElementById("usernameForm").hidden = false;
        document.getElementById("passwordForm").hidden = false;
        return;
    }
</script>
@endsection
