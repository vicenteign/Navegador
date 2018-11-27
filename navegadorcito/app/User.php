<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const ADMIN_TYPE = 'admin';
    const STUDENT_TYPE = 'estudiante';
    const PROFESOR_TYPE = 'profesor';
    const DEFAULT_TYPE = 'user';

    public function isAdmin()
    {
        return $this->type === self::ADMIN_TYPE;
    }

    public function isStudent()
    {
        return $this->type === self::STUDENT_TYPE;
    }

    public function isProfesor()
    {
        return $this->type === self::PROFESOR_TYPE;
    }

    public function alumno()
    {
       return $this->hasOne('App\Alumno', 'users_id_user');
    }

    public function profesor()
    {
        return $this->hasOne('App\Profesor');
    }

    public function validateData($var)
    {
        if($var == NULL)
            return false;
        return true;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
