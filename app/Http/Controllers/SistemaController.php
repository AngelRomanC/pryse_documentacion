<?php

namespace App\Http\Controllers;

use App\Models\Sistema;
use App\Http\Requests\StoreSistemaRequest;
use App\Http\Requests\UpdateSistemaRequest;
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

    public function create() {
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
    public function store(StoreSistemaRequest $request) {
        // dd($request->all()); // Debugging: Verifica los datos recibidos
        DB::beginTransaction();

        try {
            $folder = 'documentos_sistema'; // Define el folder donde se guardarán los archivos

            if (!Storage::disk('public')->exists($folder)) { // Verifica si el folder existe
                Storage::disk('public')->makeDirectory($folder); // Si no existe, crea el folder
            }

            $Sistema = Sistema::create($request->validated()); // Crea el registro del sistema con los datos validados

            foreach ($request->file('ruta_documento') as $file) { // Itera sobre los archivos enviados
                $Sistema->archivos()->create([ // Crea un registro de archivo asociado al sistema
                    'ruta_archivo' => $this->storeFile($file, $folder), // Guarda el archivo y obtiene su ruta
                    'nombre_original' => $file->getClientOriginalName(), // Guarda el nombre original del archivo
                ]);
            }

            DB::commit(); // Confirma la transacción exitosa

            // Redirige a la lista de sistemas con un mensaje de éxito
            return redirect()->route($this->routeName . 'index')->with('success', 'Registro creado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack(); // Revierte la transacción en caso de error
            // Redirige de vuelta con un mensaje de error
            return back()->withErrors(['error' => 'Ocurrió un error al guardar el sistema: ' . $e->getMessage()]);
        }
    }
    
    private function storeFile($file, $folder) {
        // Genera un nombre único para el archivo
        $originalName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension(); // Obtiene la extensión del archivo
        $fileName = time() . '_' . $originalName . '.' . $extension; // Combina el timestamp con el nombre original y la extensión
        return $file->storeAs($folder, $fileName, 'public'); // Guarda el archivo en el disco público y devuelve la ruta
    }

    /**
     * Display the specified resource.
     */
    public function show(Sistema $sistema) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sistema $sistema) {
        // Crea un array de departamentos para el select
        $departamentos = Departamento::select('id', 'nombre as name')->get();        
        $sistema->load('archivos'); // Carga los archivos asociados al sistema
        // Renderiza la vista de edición con los datos del sistema
        return Inertia::render('Sistema/Edit', [ 
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
    public function update(UpdateSistemaRequest $request, Sistema $sistema) {
        dd($request->all()); // Debugging: Verifica los datos recibidos
        DB::beginTransaction();
        try {
            // Actualizar datos básicos primero
            $sistema->update($request->only([
                'nombre', 'descripcion', 'departamento_id', 'url',
                'fecha_creacion', 'fecha_produccion', 'estatus',
                'numero_usuarios', 'nombre_servidor', 'ip_servidor',
                'sistema_operativo', 'nombre_servidor_bd', 'ip_servidor_bd',
                'lenguaje_desarrollo', 'version_lenguaje'
            ]));

            // Manejar archivos a eliminar (si existen)
            if ($request->has('archivos_a_eliminar') && is_array($request->archivos_a_eliminar)) {
                $archivosAEliminar = array_unique($request->archivos_a_eliminar);
                
                foreach ($archivosAEliminar as $archivoId) {
                    $archivo = $sistema->archivos()->find($archivoId);
                    
                    if ($archivo) {
                        // Eliminar físicamente el archivo
                        if (Storage::disk('public')->exists($archivo->ruta_archivo)) {
                            Storage::disk('public')->delete($archivo->ruta_archivo);
                        }
                        // Eliminar registro de la BD
                        $archivo->delete();
                    }
                }
            }

            // Manejar nuevos archivos (si existen)
            if ($request->hasFile('nuevos_documentos_principales')) {
                foreach ($request->file('nuevos_documentos_principales') as $file) {
                    $nombreOriginal = $file->getClientOriginalName();
                    $ruta = $file->store('documentos_sistema', 'public');
                    
                    $sistema->archivos()->create([
                        'ruta_archivo' => $ruta,
                        'nombre_original' => $nombreOriginal
                    ]);
                }
            }

            DB::commit(); // Confirma la transacción
            return redirect()->route($this->routeName . 'index')
                ->with('success', 'Sistema actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sistema $sistema) {
        DB::beginTransaction(); // Inicia una transacción para asegurar que todas las operaciones se completen correctamente

        try {
            foreach ($sistema->archivos as $archivo) { // Itera a través de los archivos asociados al sistema
                if ($archivo->ruta_archivo && Storage::disk('public')->exists($archivo->ruta_archivo)) { // Verifica si el archivo existe en el disco público
                    Storage::disk('public')->delete($archivo->ruta_archivo); // Elimina el archivo del disco
                }
                $archivo->delete(); // Eliminar el registro del archivo en la base de datos
            }

            $sistema->delete(); // Eliminar el documento en sí
            DB::commit(); // Confirma la transacción si todo ha ido bien

            // Redirige a la lista de sistemas
            return redirect()->route($this->routeName . 'index')->with('success', 'Registro eliminado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack(); // Si hay un error, deshace la transacción y muestra un mensaje de error
            // Redirige de vuelta con un mensaje de error
            return back()->withErrors(['error' => 'Ocurrió un error al eliminar el sistema: ' . $e->getMessage()]);
        }
    }
}
