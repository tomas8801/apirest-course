<?php

namespace App\Providers;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        # Definimos cuanto tiempo sera valido el token creado antes de que expire.
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        # Definimos durante cuanto tiempo sera permitido volver a refrescar el token original luego de que este haya expirado.
        # (Luego de esa fecha debe realizar el flujo de autorizacion para obtener un nuevo token por parte del usuario)
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));

    }
}
