<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define um Gate para verificar a role do usuário
        Gate::define('isAdmin', function ($user) {
            return $user->tipo === 'admin';
        });

        Gate::define('isEstudante', function ($user) {
            return $user->tipo === 'estudante';
        });

        Gate::define('isDocente', function ($user) {
            return $user->tipo === 'docente';
        });

        Gate::define('isSecretaria', function ($user) {
            return $user->tipo === 'secretaria';
        });

        Gate::define('isFinanceiro', function ($user) {
            return $user->tipo === 'financeiro';
        });
    }
}