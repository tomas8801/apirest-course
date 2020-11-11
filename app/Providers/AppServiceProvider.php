<?php

namespace App\Providers;

use App\User;
use App\Product;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Cuando un usuario sea creado se disparara un evento
        // donde enviaremos un correo a la direccion registrada.
        User::created(function($user){
            # El metodo retry() lo que hace es es reintentar una accion en caso
            # de que esta fallé
            retry(5, function() use ($user){
                Mail::to($user)->send(new UserCreated($user));
            }, 100);
        });

        User::updated(function($user){
            # Verificamos si hubo cambios en el email con el metodo isDirty()
            if($user->isDirty('email')) {
                retry(5, function() use ($user){
                    Mail::to($user)->send(new UserMailChanged($user));
                }, 100);
            }
        });

        // Cuando un producto se actualize debemos asegurarnos que si
        // cantidad disponible es 0 , su estado sea NO DISPONIBLE.
        Product::updated(function($product){
            if($product->quantity == 0 && $product->estaDisponible()) {
                $product->status = Product::PRODUCTO_NO_DISPONIBLE;

                $product->save();
            }
        });
    }
}
