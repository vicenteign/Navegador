<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')
        ->name('home');
Route::get('/admin', 'AdminController@admin')
        ->middleware('is_admin')
        ->name('admin');

/* Cursos */

    /* GET */
    Route::get('/adminCursos', 'CursoController@adminCursos')
            ->middleware('is_admin')
            ->name('adminCursos');
    Route::get('/addCurso', 'CursoController@addCurso')
            ->middleware('is_admin')
            ->name('curso/addCurso');
    Route::get('/cursoControl/{id}', ['uses' => 'CursoController@cursoControl'])
            ->middleware('is_admin')
            ->name('cursoControl');
    Route::get('addAlumnoToCurso/{id}', ['uses' => 'CursoController@showAlumnos'])
            ->middleware('is_admin')
            ->name('cursos/alumnosDisponibles');

    /* POST */
    Route::post('/cursoControl/updateCurso', ['uses' => 'CursoController@updateCurso'])
            ->middleware('is_admin')
            ->name('curso/updateCurso');
    Route::post('/cursoControl/deleteCurso', ['uses' => 'CursoController@deleteCurso'])
            ->middleware('is_admin')
            ->name('curso/deleteCurso');
    Route::post('/cursoControl/addCurso', ['uses' => 'CursoController@insertCurso'])
            ->middleware('is_admin')
            ->name('curso/storeCurso');
    Route::post('/cursoControl/addAlumno', ['uses' => 'CursoController@insertStudent'])
            ->middleware('is_admin')
            ->name('curso/addNewAlumno');
    Route::post('/alumnoControl/deleteInscripcion', ['uses' => 'CursoController@removeStudent'])
            ->middleware('is_admin')
            ->name('curso/deleteInscripcion');

/* Alumnos */

    /* GET */
    Route::get('/adminAlumnos', 'AlumnoController@adminAlumnos')
            ->middleware('is_admin')
            ->name('adminAlumnos');
    Route::get('/alumnoControl/{id}', ['uses' => 'AlumnoController@alumnoControl'])
            ->middleware('is_admin')
            ->name('alumnoControl');
    Route::get('/addAlumno', 'AlumnoController@addAlumno')
            ->middleware('is_admin')
            ->name('alumno/addAlumno');
    Route::get('/fichaAlumno', 'AlumnoController@fichaAlumno')
            ->middleware('is_student')
            ->name('/fichaAlumno');
    Route::get('/detalleCursoAlumno/{id}',  ['uses' => 'AlumnoController@detalleCurso'])
            ->middleware('is_student')
            ->name('/detalleCurso');

    /* POST */
    Route::post('/alumnoControl/updateAlumno', ['uses' => 'AlumnoController@updateAlumno'])
            ->middleware('is_admin')
            ->name('alumno/updateAlumno');
    Route::post('/alumnoControl/deleteAlumno', ['uses' => 'AlumnoController@deleteAlumno'])
            ->middleware('is_admin')
            ->name('alumno/deleteAlumno');
    Route::post('/alumnoControl/addAlumno', ['uses' => 'AlumnoController@insertAlumno'])
            ->middleware('is_admin')
            ->name('alumno/storeAlumno');
    Route::post('/cursoControl/deleteInscripcion', ['uses' => 'AlumnoController@removeCurso'])
            ->middleware('is_admin')
            ->name('alumno/deleteInscripcion');

/* Profesores */

    /* GET */
    Route::get('/adminProfesores', 'ProfesorController@adminProfesores')
            ->middleware('is_admin')
            ->name('adminProfesores');
    Route::get('/profesorControl/{id}', ['uses' => 'ProfesorController@profesorControl'])
            ->middleware('is_admin')
            ->name('profesorControl');
    Route::get('/addProfesor', 'ProfesorController@addProfesor')
            ->middleware('is_admin')
            ->name('profesor/addProfesor');
    Route::get('/fichaProfesor', 'ProfesorController@fichaProfesor')
            ->middleware('is_profesor')
            ->name('/fichaProfesor');
    Route::get('/detalleCursoProfesor/{id}',  ['uses' => 'ProfesorController@detalleCurso'])
            ->middleware('is_profesor')
            ->name('/detalleCurso');
    Route::get('/fichaAlumnoProfesor/{id}',  ['uses' => 'ProfesorController@fichaAlumnoProfesor'])
            ->middleware('is_profesor')
            ->name('/fichaAlumnoProfesor');
    Route::get('/addProfesorToCurso/{id}',  ['uses' => 'ProfesorController@showCursos'])
            ->middleware('is_admin')
            ->name('/cursosDisponibles');
    /* POST */
    Route::post('/profesorControl/updateProfesor', ['uses' => 'ProfesorController@updateProfesor'])
            ->middleware('is_admin')
            ->name('profesor/updateProfesor');
    Route::post('/profesorControl/addProfesor', ['uses' => 'ProfesorController@insertProfesor'])
            ->middleware('is_admin')
            ->name('profesor/storeProfesor');
    Route::post('/profesorControl/deleteProfesor', ['uses' => 'ProfesorController@deleteProfesor'])
            ->middleware('is_admin')
            ->name('profesor/deleteProfesor');
    Route::post('/profesorControl/addCurso', ['uses' => 'ProfesorController@insertCurso'])
            ->middleware('is_admin')
            ->name('profesor/addNewCurso');
    Route::post('/profesorControl/deleteCurso', ['uses' => 'ProfesorController@removeCurso'])
            ->middleware('is_admin')
            ->name('profesor/deleteCurso');
