<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profesor;
use App\Curso;
use App\User;
use App\InstanciaCurso;
use App\MatriculaInstanciaCurso;
use App\Alumno;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Support\Facades\Auth;

class ProfesorController extends Controller
{
    public function adminProfesores()
    {
        $profesores_almacenados = Profesor::orderBy('rut')->get();
        return view('admin.administracion_profesores', ['profesores_almacenados' => $profesores_almacenados]);
    }
    public function profesorControl($data)
    {
        if($data == 'undefined')
            return redirect()->route('adminAlumnos');

        $profesor = Profesor::find($data);
        $profesor_curso_data = Curso::orderBy('anyo', 'desc')
                            ->orderBy('sigla')
                            ->join('instancia_cursos', 'instancia_cursos.curso_id_curso', '=', 'cursos.id_curso')
                            ->join('profesores', 'profesores.id_profesor', '=', 'instancia_cursos.id_profesor_fk')
                            ->where('id_profesor', '=', $data)
                            ->get();
        return view('admin.profesores.profesor_control')
                                ->with('profesor', $profesor)
                                ->with('cursos_profesor', $profesor_curso_data);
    }
    public function updateProfesor(Request $request)
    {
        $response = json_decode($request->DATA);
        $id = $response[4];

        if((Rut::parse($response[3])->validate()) == false)
            return "RUT no válido. Los cambios no fueron guardados.";

        Profesor::find($id)->update(['nombres' => $response[0]]);
        Profesor::find($id)->update(['apellido_paterno' => $response[1]]);
        Profesor::find($id)->update(['apellido_materno' => $response[2]]);
        Profesor::find($id)->update(['rut' => $response[3]]);

        return "Edicion realizada con éxito.";
    }

    public function addProfesor(Request $data)
    {
        return view('admin.profesores.addProfesor');
    }

    public function insertProfesor(Request $data)
    {
        if($data->statusUser == 0)
        {
            $newUserProfesor = new User;
            $newUserProfesor->name = $data->username;
            $newUserProfesor->email = $data->email;
            $newUserProfesor->password = bcrypt($data->password);
            $newUserProfesor->type = 'profesor';

            $verify = User::where('email', '=', $data->email)
                                    ->get();

            if(count($verify))
                return "Correo ya registrado en el sistema, ingrese otro correo.";

            if($newUserProfesor->validateData($data->username) == false)
                return "Tiene que ingresar un nombre de usuario para el profesor.";
            if($newUserProfesor->validateData($data->email) == false)
                return "Tiene que ingresar un email para el profesor.";
            if($newUserProfesor->validateData($data->password) == false)
                return "Tiene que ingresar o generar una contraseña para el profesor.";
        }
        else if($data->statusUser == 1)
        {
            $newUserProfesor = User::where('email', '=', $data->email)
                                ->first();
            if($newUserProfesor->alumno != null)
                return 'El profesor no puede ser un alumno.';
            $newUserProfesor->update(['type' => 'profesor']);
        }
        else
            return 'Opción de usuario no válida. Intente nuevamente';

        $newProfesor = new Profesor;
        $newProfesor->nombres = $data->nameProfesor;
        $newProfesor->apellido_paterno = $data->paternoProfesor;
        $newProfesor->apellido_materno = $data->maternoProfesor;
        $newProfesor->rut = $data->rutProfesor;

        if($newProfesor->validateData($data->nameProfesor) == false)
            return "Tiene que ingresar el nombre para el profesor.";
        if($newProfesor->validateData($data->paternoProfesor) == false)
            return "Tiene que ingresar el apellido paterno del profesor.";
        if($newProfesor->validateData($data->maternoProfesor) == false)
            return "Tiene que ingresar el apellido materno del profesor.";
        if($newProfesor->validateData($data->rutProfesor) == false)
            return "Tiene que ingresar el RUT del profesor.";
        if((Rut::parse($data->rutProfesor)->validate()) == false)
            return "RUT no válido.";

        if($data->statusUser == 0)
            $newUserProfesor->save();
        $newUserProfesor->profesor()->save($newProfesor);

        return 1;
    }

    public function deleteProfesor(Request $request)
    {
        $profesor = Profesor::find($request->DATA);
        $profesor->user->update(['type' => 'user']);
        $profesor->delete();
        return 1;
    }

    public function fichaProfesor()
    {
        $usuarioActual = Auth::user();

        if($usuarioActual->profesor == NULL)
            return redirect()->route('/home');

        $profesor = $usuarioActual->profesor;
        $cursos_profesor = Curso::orderBy('instancia_cursos.anyo', 'desc')
                            ->orderBy('instancia_cursos.semestre', 'desc')
                            ->join('instancia_cursos', 'instancia_cursos.curso_id_curso', '=', 'cursos.id_curso')
                            ->where('id_profesor_fk', '=', $profesor->id_profesor)
                            ->get();

        return view('fichaProfesor')
                ->with('profesor', $profesor)
                ->with('cursos_profesor', $cursos_profesor);
    }

    public function fichaAlumnoProfesor($id_alumno)
    {
        $usuario = Auth::user();
        $profesor = $usuario->profesor;
        $alumno = Alumno::find($id_alumno);
        $cursos_alumno = Curso::orderBy('instancia_cursos.anyo', 'desc')
                            ->orderBy('instancia_cursos.semestre', 'desc')
                            ->join('instancia_cursos', 'instancia_cursos.curso_id_curso', '=', 'cursos.id_curso')
                            ->join('matriculainstanciacurso', 'matriculainstanciacurso.instancia_cursos_id_instcurso', '=', 'instancia_cursos.id_instcurso')
                            ->join('alumnos', 'matriculainstanciacurso.alumnos_id_alumno', '=', 'alumnos.id_alumno')
                            ->where('id_alumno', '=', $alumno->id_alumno)
                            ->get();

        return view('main.profesores.fichaAlumno')
                ->with('alumno', $alumno)
                ->with('cursos_alumno', $cursos_alumno)
                ->with('profesor', $profesor)
                ->render();
    }

    public function detalleCurso($id)
    {
        $detalle_curso = Curso::find($id);
        $detalle_instancia = InstanciaCurso::where('curso_id_curso', '=', $id)->first();
        $detalle_matricula = MatriculaInstanciaCurso::where('instancia_cursos_id_instcurso', '=', $detalle_instancia->id_instcurso)->first();

        $alumnos = Alumno::join('matriculaInstanciaCurso', 'alumnos_id_alumno', '=', 'id_alumno')
                        ->where('instancia_cursos_id_instcurso', '=', $detalle_instancia->id_instcurso)->get();

        return view('main.profesores.detalleCurso')
                ->with('curso', $detalle_curso)
                ->with('detalle_instancia', $detalle_instancia)
                ->with('detalle_matricula', $detalle_matricula)
                ->with('alumnos', $alumnos);
    }

    public function showCursos($data)
    {
        $profesor = Profesor::find($data);
        $cursos_disponibles = Curso::orderBy('sigla')
                                    ->join('instancia_cursos', 'curso_id_curso', 'id_curso')
                                    ->where('id_profesor_fk')
                                    ->orwhere('id_profesor_fk', '<>', $profesor->id_profesor)
                                    ->get();

        return view('admin.profesores.cursosDisponibles')
                ->with('cursos_disponibles', $cursos_disponibles)
                ->with('profesor', $profesor);
    }

    public function insertCurso(Request $request)
    {
        $response = json_decode($request->DATA);

        $curso = InstanciaCurso::find($response[0]);
        $profesor = Profesor::find($response[1]);
        $curso->profesor()->associate($profesor);
        $curso->save();

        return 1;
    }

    public function removeCurso(Request $data)
    {
        $response = json_decode($data->DATA);

        $curso = InstanciaCurso::find($response[1]);
        $profesor = Profesor::find($response[0]);

        $curso->profesor()->dissociate();
        $curso->save();

        return 1;
    }
}
