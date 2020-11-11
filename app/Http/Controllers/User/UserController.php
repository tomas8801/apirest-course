<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        #creamos reglas de validacion
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];
        # ejecutamos la validacion con el metodo validate
        $this->validate($request, $rules);
        # obtenemos los campos recibidos por el usuario
        $data = $request->all();
        # modificamos algunos campos
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::USUARIO_NO_VERIFICADO;
        $data['verification_token'] = User::generarVerificationToken();
        $data['admin'] = User::USUARIO_REGULAR;

        # creamos la instancia con una asignacion masiva atraves de un array
        $user = User::create($data);
        # retornamos una respuesta 201 indicando que se realizo la operacion de almacenamientos
        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        #creamos reglas de validacion
        $rules = [
            'email' => 'email|unique:users,email,'.$user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:'.User::USUARIO_ADMINISTRADOR.','.User::USUARIO_REGULAR,
        ];
        # ejecutamos la validacion con el metodo validate
        $this->validate($request, $rules);
        # verificamos si tenemos estos campos y los asignaremos
        if($request->has('name')){
            $user->name = $request->name;
        }
        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }
        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }
        if($request->has('admin')){
            if(!$user->esVerificado()){
                return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador', 409);
            }
            $user->admin = $request->admin;
        }
        # verificamos si hubo cambios en los valores originales de la ddbb con el metodo isDirty()
        if(!$user->isDirty()){
            # si no hubo cambios retornamos un error
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }
        # guardamos
        $user->save();
        # retornamos una respuesta con el modelo y sus modificaciones realizadas
        return $this->showOne($user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne($user);

    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::USUARIO_VERIFICADO;
        $user->verification_token = null;
        $user->save();

        return $this->showMessage('El usuario ha sido verificado');
    }

    public function resend(User $user)
    {
        if($user->esVerificado()) {
            return $this->errorResponse('Este usuario ya ha sido verificado', 409);
        }

        # El metodo retry() lo que hace es es reintentar una accion en caso
        # de que esta fallé
        retry(5, function() use ($user){
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('El correo de verificacion se ha reenviado');
    }
}
