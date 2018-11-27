<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    public $primaryKey = 'id_profesor';
    protected $table = 'profesores';
    protected $fillable = ['nombres', 'apellido_paterno', 'apellido_materno', 'rut'];

    public function instanciaCurso()
    {
        return $this->hasMany('App\InstanciaCurso');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function validateData($var)
    {
        if($var == NULL)
            return false;
        return true;
    }
}
