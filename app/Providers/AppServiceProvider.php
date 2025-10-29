<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

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

        View::share('theme', 'lte');

        // Blade Directives para Roles y Permisos

        // @role('admin') ... @endrole
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        // @hasrole('admin|super-admin') ... @endhasrole
        Blade::if('hasrole', function ($roles) {
            if (!auth()->check()) {
                return false;
            }
            $roleArray = is_array($roles) ? $roles : explode('|', $roles);
            return auth()->user()->hasAnyRole($roleArray);
        });

        // @permission('users.create') ... @endpermission
        Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        // @haspermission('users.create|users.edit') ... @endhaspermission
        Blade::if('haspermission', function ($permissions) {
            if (!auth()->check()) {
                return false;
            }
            $permArray = is_array($permissions) ? $permissions : explode('|', $permissions);
            return auth()->user()->hasAnyPermission($permArray);
        });

        // @unlessrole('admin') ... @endunlessrole
        Blade::if('unlessrole', function ($role) {
            return auth()->check() && !auth()->user()->hasRole($role);
        });

        // @unlesspermission('users.delete') ... @endunlesspermission
        Blade::if('unlesspermission', function ($permission) {
            return auth()->check() && !auth()->user()->hasPermission($permission);
        });
    }
}
