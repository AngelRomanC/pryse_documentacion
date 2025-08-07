<?php

namespace App\Http\Controllers;

use App\Models\Proceso;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProcesoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected string $routeName;
    protected string $module = 'proceso'; //Nombre en singular buena práctica

    public function __construct()
    {
        $this->middleware('auth');
        $this->routeName = 'procesos.';
        // $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
        // $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
        // $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        // $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }
    public function index(Request $request)
    {
        //
        $query = Proceso::with('departamento'); // Cargar la relación

        if (!auth()->user()->hasRole('Admin')) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%')               
                ->orWhereHas('departamento', function ($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->search . '%');
                });
        }

        $procesos = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Proceso/Index', [
            'titulo' => 'Lista de Procesos',
            'routeName' => $this->routeName,
            'filters' => $request->only('search'),
            'procesos' => $procesos,
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
    public function show(Proceso $proceso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proceso $proceso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proceso $proceso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proceso $proceso)
    {
        //
    }
}
