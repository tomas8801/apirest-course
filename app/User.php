<?php

namespace App;

use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    const USUARIO_VERIFICADO = true;
    const USUARIO_NO_VERIFICADO = false;

    const USUARIO_ADMINISTRADOR = true;
    const USUARIO_REGULAR = false;

    public $transformer = UserTransformer::class;

    protected $table = 'users';
    protected $dates = ['deleted_at'];



    protected $fillable = [
        'name', 'email', 'password', 'verified', 'admin', 'verification_token'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    #Los mutadores nos permite setear y modificar un valor.
    public function setNameAttribute($valor)
    {
        # El atributo nombre estará siempre en minuscula al momento de establecerlo.
        $this->attributes['name'] = strtolower($valor);
    }

    #Los accesores nos permite obtener el valor de ese atributo.
    public function getNameAttribute($valor){
        # Retornamos el atributo transformado.
        return ucfirst($valor);
    }


    public function setEmailAttribute($valor)
    {
        # El atributo email estará siempre en minuscula al momento de establecerlo.
        $this->attributes['email'] = strtolower($valor);
    }



    public function esVerificado(){
        return $this->verified == User::USUARIO_VERIFICADO;
    }

    public function esAdministrador(){
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    }

    public static function  generarVerificationToken(){
        return Str::random(40);
    }
}
