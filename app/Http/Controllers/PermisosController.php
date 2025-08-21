<?php

namespace App\Http\Controllers;

use App\Models\Permisos;
use App\Http\Requests\StorePermisosRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Module;

class PermisosController extends Controller
{
    protected string $routeName = 'permisos.';
    protected string $source = 'Seguridad/Permisos/';

    public function index()
    {
        //$permisos = Permisos::all();
        $permisos = Permisos::orderBy('id', 'desc')
            ->paginate(8)
            ->withQueryString();

        return Inertia::render("{$this->source}Index", [
            'permisos' => $permisos,
            'titulo' => 'Permisos del Sistema',
            'routeName' => $this->routeName
        ]);
    }

    public function create()
    {
        $modules = Module::all();
        return Inertia::render("{$this->source}Create", [
            'modules' => $modules,
            'titulo' => 'Crear Permiso',
            'routeName' => $this->routeName
        ]);
    }

    public function store(StorePermisosRequest $request)
    {
        $permiso = Permisos::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'description' => $request->description,
            'module_key' => $request->module_key
        ]);

        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Permiso creado exitosamente');
    }

    public function edit(Permisos $permiso)
    {
        $modules = Module::all();
        return Inertia::render("{$this->source}Edit", [
            'permiso' => $permiso,
            'modules' => $modules,
            'titulo' => 'Editar Permiso',
            'routeName' => $this->routeName
        ]);
    }

    public function update(StorePermisosRequest $request, Permisos $permiso)
    {
        $permiso->update([
            'name' => $request->name,
            'description' => $request->description,
            'module_key' => $request->module_key
        ]);

        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Permiso actualizado exitosamente');
    }

    public function destroy(Permisos $permiso)
    {
        $permiso->delete();
        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Permiso eliminado exitosamente');
    }
}