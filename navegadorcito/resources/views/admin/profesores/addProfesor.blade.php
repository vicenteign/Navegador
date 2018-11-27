@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Agregar nuevo profesor</div>
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
                    <form method="POST" name="nuevoProfesor" id="nuevoProfesorForm">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Usuario') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" aria-describedby="email" placeholder="Email del Profesor" name="email" value="{{ old('email') }}" required>
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
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" aria-describedby="username" placeholder="Nombre de Usuario del Profesor" name="username" value="{{ old('name') }}" required autofocus>
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
                                    <input id="password" type="password" class="form-control" aria-describedby="password" placeholder="Contraseña del Profesor" name="password" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Nombres del Profesor:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" aria-describedby="nameProfesor" placeholder="Nombre del Profesor" name="nameProfesor" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Apellido Paterno del Profesor:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" aria-describedby="paternoProfesor" placeholder="Apellido Paterno del Profesor" name="paternoProfesor" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Apellido Materno del Profesor:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" aria-describedby="maternoProfesor" placeholder="Apellido Materno del Profesor" name="maternoProfesor" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">RUT del Profesor:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" aria-describedby="rutProfesor" placeholder="RUT del Profesor" name="rutProfesor" required>
                            </div>
                        </div>
                        <input id="statusUser" aria-describedby="password" name="statusUser" value="0" hidden>
                    </form>
                    <div class="col-md-6">
                        <button class="btn btn-primary mb-2" id='saveCurso' onclick="saveProfesor()">Registrar Cambios</a>
                    </div>
                </div>
            </div>
        </br>
            <a class="btn btn-primary btn-lg" role="button" href="{{url('adminProfesores')}}"><b>Volver</b></a>
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
    function saveProfesor()
    {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: $('#nuevoProfesorForm').serialize(),
            url: "{{url('profesorControl/addProfesor')}}",
            success: function(response){
                if(response != 1)
                {
                    alert(response);
                    console.log(response);
                }
                else
                    window.location.href = "{{url('adminProfesores')}}";
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
