<?php

namespace App\Http\Controllers;

use App\Models\InventarioEquipo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InventarioEquipoImport;




class InventarioEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private string $routeName;
    protected string $module = 'inventario';
    public function __construct()
    {
        $this->middleware('auth');
        $this->routeName = 'inventario.';
        // $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
        // $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
        // $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        // $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = InventarioEquipo::query();

        if ($request->filled('search')) {
            $query->where('nombre_persona', 'like', '%' . $request->search . '%')
                ->orWhere('area', 'like', '%' . $request->search . '%')
                ->orWhere('tipo_pc', 'like', '%' . $request->search . '%')
                ->orWhere('marca_equipo', 'like', '%' . $request->search . '%')
                ->orWhere('sistema_operativo', 'like', '%' . $request->search . '%')

                ->orWhere('procesador', 'like', '%' . $request->search . '%')
                ->orWhere('capacidad_ram', 'like', '%' . $request->search . '%')
                ->orWhere('tipo_disco', 'like', '%' . $request->search . '%')
                ->orWhere('capacidad_disco', 'like', '%' . $request->search . '%')
                ->orWhere('nombre_arqueo', 'like', '%' . $request->search . '%');







        }

        $inventarios = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Inventario/Index', [
            'titulo' => 'Inventario de Equipos',
            'inventarios' => $inventarios,
            'routeName' =>  $this->routeName, 
            'filters' => $request->only('search'),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InventarioEquipo $inventarioEquipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventarioEquipo $inventarioEquipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventarioEquipo $inventarioEquipo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventarioEquipo $inventarioEquipo)
    {
        //
    }

    public function importarExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new InventarioEquipoImport, $request->file('archivo'));


        return redirect()->back()->with('success', 'Archivo importado correctamente.');
    }
    public function mostrarFormularioImportacion()
    {
        return Inertia::render('Inventario/Importar');
    }
}
