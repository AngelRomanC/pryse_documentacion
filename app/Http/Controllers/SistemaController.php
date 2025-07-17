<?php

namespace App\Http\Controllers;

use App\Models\Sistema;
use App\Http\Requests\StoreSistemaRequest;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SistemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private string $routeName;
    protected string $module = 'sistema';


    public function __construct()
    {
        $this->middleware('auth');
        $this->routeName = 'sistema.';
        // $this->middleware("permission:{$this->module}.index")->only(['index', 'show']); // 
        // $this->middleware("permission:{$this->module}.store")->only(['store', 'create']); // 
        // $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        // $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Sistema::query();

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }
        $sistemas = $query->with('departamento')  
            ->orderBy('id', 'desc')
            ->paginate(8)
            ->withQueryString();



        return Inertia::render("Sistema/Index", [
            'titulo' => 'Lista de sistemas',
            'sistemas' => $sistemas,
            'routeName' => $this->routeName,
            'filters' => $request->only('search'), // ← Esto mantiene el valor en el buscador

        ]);
    }

    public function create()
    {
        $departamentos = Departamento::select('id', 'nombre as name')->get();

        return Inertia::render('Sistema/Create', [
            'titulo' => 'Crear Registro de Sistema',
            'routeName' => $this->routeName,
            'departamentos' => $departamentos,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSistemaRequest $request)
    {

        // dd($request->all()); // Debugging: Verifica los datos recibidos

        DB::beginTransaction();

        try {
            $folder = 'documentos_sistema';

            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }

            $Sistema = Sistema::create($request->validated());

            foreach ($request->file('ruta_documento') as $file) {
                $Sistema->archivos()->create([
                    'ruta_archivo' => $this->storeFile($file, $folder),
                    'nombre_original' => $file->getClientOriginalName(),
                ]);
            }

            DB::commit();

            return redirect()->route($this->routeName . 'index')->with('success', 'Registro creado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al guardar el sistema: ' . $e->getMessage()]);
        }
    }
    
    private function storeFile($file, $folder) {
        // return $file ? $file->storeAs($folder, time() . '_' . $file->getClientOriginalName(), 'public') : null;
        $originalName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '_' . $originalName . '.' . $extension;

        return $file->storeAs($folder, $fileName, 'public');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sistema $sistema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sistema $sistema)
    {
        $departamentos = Departamento::select('id', 'nombre as name')->get();        
        $sistema->load('archivos');

        return Inertia::render('Documento/Edit', [
            'titulo' => 'Editar Registro de Sistema',
            'sistema' => $sistema,
            'routeName' => $this->routeName,
            'departamentos' => $departamentos,
            'archivosPrincipales' => $sistema->archivos,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sistema $sistema)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sistema $sistema) {
        DB::beginTransaction();

        try {
        // Eliminar archivos asociados (principal y anexo)
        foreach ($sistema->archivos as $archivo) {
            if ($archivo->ruta_archivo && Storage::disk('public')->exists($archivo->ruta_archivo)) {
                Storage::disk('public')->delete($archivo->ruta_archivo);
            }
            
            // Eliminar el registro del archivo en la base de datos
            $archivo->delete();
        }

            // Eliminar el documento en sí
            $sistema->delete();

            DB::commit();

            return redirect()->route($this->routeName . 'index')->with('success', 'Registro eliminado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al eliminar el sistema: ' . $e->getMessage()]);
        }
    }
}
