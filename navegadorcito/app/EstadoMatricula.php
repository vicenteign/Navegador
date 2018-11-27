<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoMatricula extends Model
{
    protected $table = 'estadomatricula';

    public function matriculasInstanciasCursos()
    {
        return $this->hasMany('App\MatriculaInstaciaCurso');
    }
}
