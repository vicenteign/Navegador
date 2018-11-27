<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alumno;
use App\Curso;
use App\User;
use App\InstanciaCurso;
use App\EstadoMatricula;
use App\MatriculaInstanciaCurso;

class CursoController extends Controller
{
    public function adminCursos()
    {
        $cursos_almacenados = Curso::orderBy('sigla')
                                    ->join('instancia_cursos', 'instancia_cursos.curso_id_curso', '=', 'cursos.id_curso')
                                    ->get();
        return view('admin.administracion_cursos', ['cursos_almacenados' => $cursos_almacenados]);
    }

    public function cursoControl($data)
    {
        if($data == 'undefined')
            return redirect()->route('adminCursos');

        $curso_data = Curso::join('instancia_cursos', 'instancia_cursos.curso_id_curso', '=', 'id_curso')
                            ->where('id_curso', '=', $data)
                            ->get();
        $alumnos_curso_data = Alumno::join('matriculainstanciacurso', 'matriculainstanciacurso.alumnos_id_alumno', '=', 'alumnos.id_alumno')
                            ->join('instancia_cursos', 'matriculainstanciacurso.instancia_cursos_id_instcurso', '=', 'instancia_cursos.id_instcurso')
                            ->join('cursos', 'cursos.id_curso', '=', 'instancia_cursos.curso_id_curso')
                            ->where('cursos.id_curso', '=', $data)
                            ->get();
        return view('admin.cursos.curso_control')
                                ->with ('curso_seleccionado', $curso_data)
                                ->with ('alumnos_curso', $alumnos_curso_data);
    }

    public function updateCurso(Request $request)
    {
        $response = json_decode($request->DATA);
        $id = $response[5];

        Curso::find($id)->update(['nombre' => $response[0]]);
        Curso::find($id)->update(['sigla' => $response[1]]);
        Curso::find($id)->update(['descripcion' => $response[3]]);

        $data = Curso::find($id);

        foreach($data->InstanciasCurso as $ultradata)
        {
            InstanciaCurso::find($ultradata->id_instcurso)->update(['semestre' => $response[2]]);
            InstanciaCurso::find($ultradata->id_instcurso)->update(['anyo' => $response[4]]);
            $matriculas = $ultradata->matriculasInstanciasCursos;
            foreach($matriculas as $megadata)
            {
                MatriculaInstanciaCurso::find($megadata->id_matrinstcurso)->update(['semestre' => $response[2]]);
                MatriculaInstanciaCurso::find($megadata->id_matrinstcurso)->update(['anyo' => $response[4]]);
            }
        }
        return 1;
    }

    public function deleteCurso(Request $data)
    {
        $curso = Curso::find($data->DATA);
        $curso->instanciasCurso()->delete();
        $curso->delete();
        return 1;
    }

    public function addCurso(Request $data)
    {
        return view('admin.cursos.addCurso');
    }

    public function insertCurso(Request $data)
    {
        $newInsCurso = new InstanciaCurso;
        $newInsCurso->anyo = $data->yearCurso;
        $newInsCurso->semestre = $data->semestreCurso;
        $newInsCurso->sigla_curso = $data->siglaCurso;

        $newCurso = new Curso;
        $newCurso->sigla = $data->siglaCurso;
        $newCurso->nombre = $data->nameCurso;
        $newCurso->descripcion = $data->descripcionCurso;

        $exist = Curso::where('cursos.sigla', '=', $data->siglaCurso)
                        ->get();

        if(count($exist) > 0)
            return "Sigla del curso ya existe en el sistema.";

        if($data->nameCurso == NULL)
            return "Tiene que ingresar un nombre para el curso.";

        if($newCurso->verificarSiglaCurso($data->siglaCurso) == true)
        {
            $newCurso->save();
            $newCurso->instanciasCurso()->save($newInsCurso);
        }
        else
            return "Sigla del curso invalida.";

        return 1;
    }

    public function showAlumnos($data)
    {
        $curso = InstanciaCurso::find($data);
        $alumnos = Alumno::orderBy('id_alumno')->get();
        $countMatricula = 0; $countNull = 0;
        $i = 0;
        $alumnos_disponibles = null;
        $allowed = false;
        $id_saved = null;

        foreach($alumnos as $alumno)
        {
            $id = $alumno->users_id_user;
            $user = User::find($id);
            if(($user != null) && ($user->type == 'estudiante'))
            {
                $matriculas = $alumno->matriculasInstanciasCursos;
                foreach($matriculas as $matriculaAlumno)
                {
                    if($matriculaAlumno->instancia_cursos_id_instcurso == $data)
                        $countMatricula++;
                    if($matriculaAlumno->instancia_cursos_id_instcurso == null)
                        $countNull++;
                }
                if($countMatricula == 0 && $countNull == 1)
                {
                    $allowed = true;
                }
                if($allowed == true && $id_saved != $alumno->id_alumno)
                {
                    $alumnos_disponibles[$i] = $alumno;
                    $i++;
                    $id_saved = $alumno->id_alumno;
                }
                $countMatricula = 0;
                $countNull = 0;
                $allowed = false;
            }
        }
        return view('admin.cursos.alumnosDisponibles', ['alumnos_almacenados' => $alumnos_disponibles],
                                                        ['curso_data' => $curso]);
    }
    public function insertStudent(Request $data)
    {
        $response = json_decode($data->DATA);

        $curso = InstanciaCurso::find($response[1]);
        $alumno = Alumno::find($response[0]);
        $matriculaAlumno = $curso->matriculasInstanciasCursos;
        $estMatricula = new EstadoMatricula;
        $estMatricula->estado = 'Valida';
        $estMatricula->save();

        $matricular = new MatriculaInstanciaCurso;
        $rutAlumno = MatriculaInstanciaCurso::where('alumnos_id_alumno', '=', $alumno->id_alumno)->first();
        $matricular->rut = $rutAlumno->rut;
        $matricular->anyo = $curso->anyo;
        $matricular->semestre = $curso->semestre;
        $matricular->estado = $estMatricula->estado;
        $matricular->sigla = $curso->sigla_curso;
        $matricular->alumno()->associate($alumno);
        $matricular->estadoMatricula()->associate($estMatricula);
        $matricular->instanciaCurso()->associate($curso);
        $matricular->save();

        return 1;
    }
    public function removeStudent(Request $data)
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
}
