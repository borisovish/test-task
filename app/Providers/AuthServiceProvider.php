<?php

namespace App\Providers;

use App\Permission;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
        $gate->define('super', function (User $user) {
            if ($user->id == 1) return true;
            return false;
        });
        if(! \Schema::hasTable('permissions')) return;
        foreach (Permission::all() as $permission) {
            $gate->define($permission->alias, function (User $user) use ($permission) {
                if ($user->roles->intersect($permission->roles)->count()) return true;
            });
        }
    }
}
