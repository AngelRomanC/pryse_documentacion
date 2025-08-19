<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    protected string $routeName = 'roles.';
    protected string $source = 'Seguridad/Roles/';

    public function index()
    {
        $roles = Role::with('permissions')->get();
        return Inertia::render("{$this->source}Index", [
            'roles' => $roles,
            'titulo' => 'Roles y Permisos',
            'routeName' => $this->routeName
        ]);
    }

    public function create()
    {
        $permissions = Permission::all();
        return Inertia::render("{$this->source}Create", [
            'permissions' => $permissions,
            'titulo' => 'Crear Rol',
            'routeName' => $this->routeName
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Rol creado exitosamente');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return Inertia::render("{$this->source}Edit", [
            'role' => $role->load('permissions'),
            'permissions' => $permissions,
            'titulo' => 'Editar Rol',
            'routeName' => $this->routeName
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array'
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Rol actualizado exitosamente');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Rol eliminado exitosamente');
    }
}