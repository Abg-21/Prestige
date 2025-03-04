<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrador');
    }

    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.rol', compact('roles')); // Cambiado a rol.blade.php
    }

    public function edit($role_id)
    {
        $role = Role::findOrFail($role_id); // Asegurar que el rol exista
        $permissions = Permission::all();

        return view('roles.edit_rol', compact('role', 'permissions'));
    }


    public function update(Request $request, $role_id)
    {
        $role = Role::findOrFail($role_id);

        // Verificar si 'permissions' está definido antes de actualizar
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Permisos actualizados.');
    }


}
