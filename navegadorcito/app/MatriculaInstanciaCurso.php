<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatriculaInstanciaCurso extends Model
{
    public $primaryKey = 'id_matrinstcurso';
    protected $table = 'MatriculaInstanciaCurso';
    protected $fillable = ['rut', 'anyo', 'semestre'];

    public function alumno()
    {
        return $this->belongsTo('App\Alumno', 'alumnos_id_alumno');
    }

    public function estadoMatricula()
    {
        return $this->belongsTo('App\EstadoMatricula', 'estadomatricula_id');
    }

    public function instanciaCurso()
    {
        return $this->belongsTo('App\InstanciaCurso', 'instancia_cursos_id_instcurso');
    }

    public function validateData($var)
    {
        if($var == NULL)
            return false;
        return true;
    }
}
