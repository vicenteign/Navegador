<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    public $primaryKey = 'id_curso';
    protected $fillable = ['nombre', 'sigla', 'descripcion'];

    public function instanciasCurso()
    {
        return $this->hasMany('App\InstanciaCurso');
    }
    
    public function verificarSiglaCurso($sigla)
    {
        $regex = '/^([A-Z]{3}[0-9]{4}-[0-9]{1})$/';

        if (preg_match($regex, $sigla) == 1)
            return true;
        else
            return false;
    }
}
