<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\Departamento;
use App\Models\InventarioEquipo;
use App\Models\Proceso;
use App\Models\Sistema;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            return Inertia::render('Dashboard/Admin', [
                'sistemasCount' => Sistema::count(),
                'equiposCount' => InventarioEquipo::count(),
                'equiposPorTipo' => InventarioEquipo::groupBy('tipo_pc')->selectRaw('tipo_pc, count(*) as total')->pluck('total', 'tipo_pc'),
                'sistemasPorEstatus' => Sistema::groupBy('estatus')->selectRaw('estatus, count(*) as total')->pluck('total', 'estatus'),
                'ultimosEquipos' => InventarioEquipo::with('departamento')->latest()->take(5)->get(),
                'sistemasRecientes' => Sistema::with('departamento')->latest()->take(5)->get()
            ]);
        }

        if ($user->hasRole('Desarrollador')) {
            $sistemas = $user->sistemas()->with('departamento')->get();

            return Inertia::render('Dashboard/Desarrollador', [
                'sistemasAsignados' => $sistemas->count(),
                'sistemasEnDesarrollo' => $sistemas->where('estatus', 'Desarrollo')->count(),
                'sistemasAsignadosLista' => $sistemas->take(5),
            ]);
        }

        if ($user->hasRole('Procesos')) {
            // Obtener procesos del usuario (o todos si tiene permiso)
            $procesosQuery = $user->procesos();
            $certificacionesQuery = $user->certificaciones();

            if ($user->can('view_all_procesos')) {
                $procesosQuery = Proceso::query();
                $certificacionesQuery = Certificacion::query();
            }

            // Procesos con paginación
            $procesosPaginated = $procesosQuery->with('departamento')
                ->orderBy('created_at', 'desc')
                ->paginate(5, ['*'], 'procesos_page')
                ->withQueryString();


            $procesos = $procesosPaginated->getCollection();
            $procesosPorVencer = $procesos->filter(function ($proceso) {
                return Carbon::parse($proceso->fecha_fin_vigencia)->lte(Carbon::now()->addDays(30));
            })->count();
            $procesosPorEstatus = $procesos->groupBy('estatus')->map->count();

            // Certificaciones con paginación
            $certificacionesPaginated = $certificacionesQuery->with('departamento')
                ->orderBy('created_at', 'desc')
                ->paginate(5, ['*'], 'certificaciones_page')
                ->withQueryString();

            $certificaciones = $certificacionesPaginated->getCollection();
            $certificacionesPorVencer = $certificaciones->filter(function ($certificacion) {
                return Carbon::parse($certificacion->fecha_fin_vigencia)->lte(Carbon::now()->addDays(30));
            })->count();
            $certificacionesPorEstatus = $certificaciones->groupBy('estatus')->map->count();

            // Actividades recientes (para el dashboard)
            $actividadesRecientes = $procesos->concat($certificaciones)
                ->sortByDesc('created_at')
                ->take(5)
                ->map(function ($item) {
                    $item->__typename = $item instanceof Proceso ? 'Proceso' : 'Certificacion';
                    return $item;
                })
                ->values()
                ->all();

            return Inertia::render('Dashboard/Procesos', [
                'totalProcesos' => $procesosPaginated->total(),
                'procesosPorVencer' => $procesosPorVencer,
                'procesosPorEstatus' => $procesosPorEstatus,
                'procesosPaginated' => $procesosPaginated,

                'totalCertificaciones' => $certificacionesPaginated->total(),
                'certificacionesPorVencer' => $certificacionesPorVencer,
                'certificacionesPorEstatus' => $certificacionesPorEstatus,
                'certificacionesPaginated' => $certificacionesPaginated,

                'actividadesRecientes' => $actividadesRecientes
            ]);
        }

        if ($user->hasRole('Ejecutivo')) {
            $sistemas = $user->sistemas()->with('departamento')->get();

            return Inertia::render('Dashboard/Desarrollador', [
                'sistemasAsignados' => $sistemas->count(),
                'sistemasEnDesarrollo' => $sistemas->where('estatus', 'Desarrollo')->count(),
                'sistemasAsignadosLista' => $sistemas->take(5),
            ]);
        }
    }
    public function dashboardEjecutivo()
    {
        $user = auth()->user();

        // Métricas básicas
        $metrics = [
            'total_processes' => Proceso::count(),
            'in_progress' => Proceso::where('estatus', 'En Progreso')->count(),
            'completed' => Proceso::where('estatus', 'Completado')->count(),
            'overdue' => Proceso::where('fecha_fin_vigencia', '<', now())->count(),
            'by_department' => Departamento::withCount('procesos')->get()
        ];

        // Procesos recientes
        $recentProcesses = Proceso::with('departamento')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Próximos vencimientos (próximos 7 días)
        $upcomingDeadlines = Proceso::with('departamento')
            ->whereBetween('fecha_fin_vigencia', [now(), now()->addDays(7)])
            ->orderBy('fecha_fin_vigencia')
            ->get()
            ->map(function ($process) {
                $process->days_remaining = now()->diffInDays($process->fecha_fin_vigencia);
                return $process;
            });

        return Inertia::render('Dashboard/Ejecutivo', [
            'metrics' => $metrics,
            'recentProcesses' => $recentProcesses,
            'upcomingDeadlines' => $upcomingDeadlines,
            'statusSummary' => Proceso::groupBy('estatus')->select('estatus', DB::raw('count(*) as count'))->get()
        ]);
    }
}