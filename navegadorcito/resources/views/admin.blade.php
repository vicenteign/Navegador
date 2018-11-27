@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Administracion</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Bienvenido, <?php echo Auth::user()->name?></br>
                    Correo actual: <?php echo Auth::user()->email?>
                </div>
            </div>
            </br>
            <div class="card">
                <div class="card-header">Administracion</div>
                <div class="card=body">
                    <table id="tablaAdministracion" style="width:50%; margin:15px;">
                        <tr>
                            <th>Cursos</th>
                            <td><a class="btn btn-outline-success my-2 my-sm-0" href="{{url('adminCursos')}}" role="button" style="cursor: pointer;">Ingresar</a></td>
                        </tr>
                        <tr>
                            <th>Alumnos</th>
                            <td><a class="btn btn-outline-success my-2 my-sm-0" href="{{url('adminAlumnos')}}" role="button" style="cursor: pointer;">Ingresar</a></td>
                        </tr>
                        <tr>
                            <th>Profesores</th>
                            <td><a class="btn btn-outline-success my-2 my-sm-0" href="{{url('adminProfesores')}}" role="button" style="cursor: pointer;">Ingresar</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
