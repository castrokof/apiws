<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('is_active', true)->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'drogueria' => 'nullable|string|max:255',
            'rol' => 'nullable|string|max:255',
            'roles' => 'array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'drogueria' => $request->drogueria,
            'rol' => $request->rol,
            'email_verified_at' => now(),
        ]);

        // Asignar roles
        if ($request->has('roles')) {
            $roles = Role::whereIn('id', $request->roles)->pluck('slug')->toArray();
            $user->assignRole(...$roles);
        }

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::where('is_active', true)->get();
        $userRoles = $user->roles->pluck('id')->toArray();
        $userPermissions = $user->permissions->pluck('id')->toArray();
        $allPermissions = Permission::all()->groupBy('module');

        return view('admin.users.edit', compact('user', 'roles', 'userRoles', 'userPermissions', 'allPermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'drogueria' => 'nullable|string|max:255',
            'rol' => 'nullable|string|max:255',
            'roles' => 'array',
            'permissions' => 'array',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'drogueria' => $request->drogueria,
            'rol' => $request->rol,
        ]);

        // Actualizar contraseña solo si se proporciona
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Sincronizar roles
        if ($request->has('roles')) {
            $roles = Role::whereIn('id', $request->roles)->pluck('slug')->toArray();
            $user->roles()->sync(Role::whereIn('slug', $roles)->pluck('id'));
        } else {
            $user->roles()->sync([]);
        }

        // Sincronizar permisos directos
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('slug')->toArray();
            $user->permissions()->sync(Permission::whereIn('slug', $permissions)->pluck('id'));
        } else {
            $user->permissions()->sync([]);
        }

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // No permitir eliminar el propio usuario
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Cambiar estado activo/inactivo
     */
    public function toggleStatus(User $user)
    {
        // Aquí podrías agregar un campo 'is_active' a la tabla users si lo necesitas
        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado'
        ]);
    }
}
