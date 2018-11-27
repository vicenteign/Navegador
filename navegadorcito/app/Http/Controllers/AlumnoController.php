<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alumno;
use App\Curso;
use App\User;
use App\InstanciaCurso;
use App\MatriculaInstanciaCurso;
use App\Profesor;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Support\Facades\Auth;

class AlumnoController extends Controller
{
    public function adminAlumnos()
    {
        $alumnos_registrados = Alumno::select('nombres', 'apellido_materno', 'apellido_paterno', 'rut', 'id_alumno')
                                    ->groupBy('nombres', 'apellido_materno', 'apellido_paterno', 'rut', 'id_alumno')
                                    ->orderBy('rut')
                                    ->get();
        return view('admin.administracion_alumnos', ['alumnos_almacenados' => $alumnos_registrados]);
    }
    public function alumnoControl($data)
    {
        if($data == 'undefined')
            return redirect()->route('adminAlumnos');

        $alumno = Alumno::join('matriculainstanciacurso', 'matriculainstanciacurso.alumnos_id_alumno', '=', 'alumnos.id_alumno')
                            ->join('users', 'users_id_user', '=', 'id')
                            ->where('id_alumno', '=', $data)
                            ->get();
        $alumnos_curso_data = Curso::join('instancia_cursos', 'instancia_cursos.curso_id_curso', '=', 'cursos.id_curso')
                            ->join('matriculainstanciacurso', 'matriculainstanciacurso.instancia_cursos_id_instcurso', '=', 'instancia_cursos.id_instcurso')
                            ->join('alumnos', 'matriculainstanciacurso.alumnos_id_alumno', '=', 'alumnos.id_alumno')
                            ->where('id_alumno', '=', $data)
                            ->get();
        return view('admin.alumnos.alumno_control')
                                ->with('alumno', $alumno)
                                ->with('cursos_alumno', $alumnos_curso_data);
    }
    public function updateAlumno(Request $request)
    {
        $response = json_decode($request->DATA);
        $id = $response[5];

        if((Rut::parse($response[3])->validate()) == false)
            return "RUT no válido. Los cambios no fueron guardados.";

        Alumno::find($id)->update(['nombres' => $response[0]]);
        Alumno::find($id)->update(['apellido_paterno' => $response[1]]);
        Alumno::find($id)->update(['apellido_materno' => $response[2]]);
        Alumno::find($id)->update(['rut' => $response[3]]);

        $data = Alumno::find($id);
        User::find($data->users_id_user)
            ->update(['email' => $response[4]]);
        foreach($data->matriculasInstanciasCursos as $ultradata)
        {
            MatriculaInstanciaCurso::find($ultradata->id_matrinstcurso)->update(['rut' => $response[3]]);
            break;
        }
        return "Edicion realizada con éxito.";
    }

    public function addAlumno(Request $data)
    {
        return view('admin.alumnos.addAlumno');
    }

    public function deleteAlumno(Request $request)
    {
        $alumno = Alumno::find($request->DATA);
        $alumno->user->update(['type' => 'user']);
        $alumno->matriculasInstanciasCursos()->delete();
        $alumno->delete();
        return 1;
    }

    public function insertAlumno(Request $data)
    {
        if($data->statusUser == 0)
        {
            $newUserAlumno = new User;
            $newUserAlumno->name = $data->username;
            $newUserAlumno->email = $data->email;
            $newUserAlumno->password = bcrypt($data->password);
            $newUserAlumno->type = 'estudiante';

            $verify = User::where('email', '=', $data->email)
                                    ->get();

            if(count($verify))
                return "Correo ya registrado en el sistema, ingrese otro correo.";

            if($newUserAlumno->validateData($data->username) == false)
                return "Tiene que ingresar un nombre de usuario para el alumno.";
            if($newUserAlumno->validateData($data->email) == false)
                return "Tiene que ingresar un email para el usuario.";
            if($newUserAlumno->validateData($data->password) == false)
                return "Tiene que ingresar o generar una contraseña para el usuario.";
        }
        else
        {
            $newUserAlumno = User::where('email', '=', $data->email)
                                ->first();
            if($newUserAlumno->profesor != null)
                return 'El alumno no puede ser profesor';
            $newUserProfesor->update(['type' => 'estudiante']);
        }

        $newAlumno = new Alumno;
        $newAlumno->nombres = $data->nameAlumno;
        $newAlumno->apellido_paterno = $data->paternoAlumno;
        $newAlumno->apellido_materno = $data->maternoAlumno;
        $newAlumno->rut = $data->rutAlumno;

        $newMIC = new MatriculaInstanciaCurso;
        $newMIC->anyo = $data->yearAlumno;
        $newMIC->semestre = $data->semestreAlumno;
        $newMIC->rut = $data->rutAlumno;

        if($newAlumno->validateData($data->nameAlumno) == false)
            return "Tiene que ingresar el nombre para el alumno.";
        if($newAlumno->validateData($data->paternoAlumno) == false)
            return "Tiene que ingresar el apellido paterno del alumno.";
        if($newAlumno->validateData($data->maternoAlumno) == false)
            return "Tiene que ingresar el apellido materno del alumno.";
        if($newMIC->validateData($data->rutAlumno) == false)
            return "Tiene que ingresar el RUT del alumno.";
        if((Rut::parse($data->rutAlumno)->validate()) == false)
            return "RUT no válido.";

        if($data->statusUser == 0)
            $newUserAlumno->save();
        $newUserAlumno->alumno()->save($newAlumno);
        $newAlumno->matriculasInstanciasCursos()->save($newMIC);

        return 1;
    }

    public function removeCurso(Request $data)
    {
        $response = json_decode($data->DATA);

        $alumno = Alumno::find($response[0]);
        $curso = InstanciaCurso::where('curso_id_curso', '=', $response[1])->first();

        $instMatr = $alumno->matriculasInstanciasCursos;
        foreach($instMatr as $matriculas)
        {
            if($matriculas->instancia_cursos_id_instcurso == $curso->id_instcurso)
            {
                $matriculas->delete();
                break;
            }
        }
        return 1;
    }

    public function fichaAlumno()
    {
        $usuarioActual = Auth::user();

        if($usuarioActual->alumno == NULL)
            return redirect()->route('/home');
        $alumno = $usuarioActual->alumno;
        $cursos_alumno = Curso::orderBy('instancia_cursos.anyo', 'desc')
                            ->orderBy('instancia_cursos.semestre', 'desc')
                            ->join('instancia_cursos', 'instancia_cursos.curso_id_curso', '=', 'cursos.id_curso')
                            ->join('matriculainstanciacurso', 'matriculainstanciacurso.instancia_cursos_id_instcurso', '=', 'instancia_cursos.id_instcurso')
                            ->join('alumnos', 'matriculainstanciacurso.alumnos_id_alumno', '=', 'alumnos.id_alumno')
                            ->where('id_alumno', '=', $alumno->id_alumno)
                            ->get();

        return view('fichaAlumno')
                ->with('alumno', $alumno)
                ->with('cursos_alumno', $cursos_alumno);
    }

    public function detalleCurso($id)
    {
        $detalle_curso = Curso::find($id);
        $detalle_instancia = InstanciaCurso::where('curso_id_curso', '=', $id)->first();
        $detalle_matricula = MatriculaInstanciaCurso::where('instancia_cursos_id_instcurso', '=', $detalle_instancia->id_instcurso)->first();
        if($detalle_instancia->id_profesor_fk != null)
            $detalle_profesor = Profesor::find($detalle_instancia->id_profesor_fk)->select('nombres', 'apellido_paterno', 'apellido_materno')->first();
        else
            $detalle_profesor = null;

        return view('main.alumnos.detalleCurso')
                ->with('curso', $detalle_curso)
                ->with('detalle_instancia', $detalle_instancia)
                ->with('detalle_matricula', $detalle_matricula)
                ->with('profesor', $detalle_profesor);
    }
}
