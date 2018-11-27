<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstanciaCurso extends Model
{
    public $primaryKey = 'id_instcurso';
    protected $fillable = ['semestre', 'anyo', 'id_profesor_fk'];

    public function cursos()
    {
        return $this->belongsTo('App\Curso');
    }

    public function matriculasInstanciasCursos()
    {
        return $this->hasMany('App\MatriculaInstanciaCurso', 'instancia_cursos_id_instcurso');
    }

    public function profesor()
    {
        return $this->belongsTo('App\Profesor', 'id_profesor_fk');
    }
}
