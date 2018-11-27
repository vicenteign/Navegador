<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    public $primaryKey = 'id_alumno';
    protected $table = 'alumnos';
    protected $fillable = ['nombres', 'apellido_paterno', 'apellido_materno'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function matriculasInstanciasCursos()
    {
        return $this->hasMany('App\MatriculaInstanciaCurso', 'alumnos_id_alumno');
    }
    
    public function validateData($var)
    {
        if($var == NULL)
            return false;
        return true;
    }
}
