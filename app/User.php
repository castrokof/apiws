<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use App\Models\Role;
use App\Models\Permission;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'drogueria', 'rol', 'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    
     public function setSession()
    {
        Session::put([
            'name' => $this->name,
            'email' => $this->email,
            'id' => $this->id,
            'drogueria' => $this->drogueria,
            'rol' => $this->rol,
            'email_verified_at' => $this->email_verified_at,
        ]);
    }

    /**
     * Relación muchos a muchos con roles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
    }

    /**
     * Relación muchos a muchos con permisos directos
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user')->withTimestamps();
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }

        return $this->roles->contains('id', $role->id);
    }

    /**
     * Verificar si el usuario tiene alguno de los roles especificados
     */
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     * Verifica tanto permisos directos como permisos heredados de roles
     */
    public function hasPermission($permission)
    {
        // Verificar permisos directos
        if (is_string($permission)) {
            if ($this->permissions->contains('slug', $permission)) {
                return true;
            }
        } else {
            if ($this->permissions->contains('id', $permission->id)) {
                return true;
            }
        }

        // Verificar permisos heredados de roles
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verificar si el usuario tiene alguno de los permisos especificados
     */
    public function hasAnyPermission($permissions)
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ($this->hasPermission($permission)) {
                    return true;
                }
            }
        } else {
            if ($this->hasPermission($permissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Asignar roles al usuario
     */
    public function assignRole(...$roles)
    {
        $roles = $this->getAllRoles($roles);

        if ($roles === null) {
            return $this;
        }

        $this->roles()->syncWithoutDetaching($roles);

        return $this;
    }

    /**
     * Remover roles del usuario
     */
    public function removeRole(...$roles)
    {
        $roles = $this->getAllRoles($roles);

        $this->roles()->detach($roles);

        return $this;
    }

    /**
     * Asignar permisos directos al usuario
     */
    public function givePermissionTo(...$permissions)
    {
        $permissions = $this->getPermissionsBySlugs($permissions);

        if ($permissions === null) {
            return $this;
        }

        $this->permissions()->syncWithoutDetaching($permissions);

        return $this;
    }

    /**
     * Remover permisos directos del usuario
     */
    public function revokePermissionTo(...$permissions)
    {
        $permissions = $this->getPermissionsBySlugs($permissions);

        $this->permissions()->detach($permissions);

        return $this;
    }

    /**
     * Obtener todos los roles
     */
    protected function getAllRoles($roles)
    {
        return Role::whereIn('slug', $roles)->get();
    }

    /**
     * Obtener permisos por slugs (helper interno)
     */
    protected function getPermissionsBySlugs($permissions)
    {
        return Permission::whereIn('slug', $permissions)->get();
    }

    /**
     * Obtener todos los permisos del usuario (directos + heredados de roles)
     */
    public function getAllPermissions()
    {
        $permissions = $this->permissions;

        foreach ($this->roles as $role) {
            $permissions = $permissions->merge($role->permissions);
        }

        return $permissions->unique('id');
    }

}
