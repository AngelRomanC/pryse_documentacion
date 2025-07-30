<?php

namespace App\Http\Controllers;


use App\Models\InventarioEquipo;
use App\Models\Sistema;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;


class DashboardController extends Controller
{
    // public function index(): Response
    // {
    //       $user = Auth::user();

    //     if ($user->hasRole('Admin')) {
    //         return Inertia::render('Dashboard/Admin');
    //     } else {
    //         return Inertia::render('Dashboard/Usuario');
    //     }
    // }
    public function index()
{
    return Inertia::render('Dashboard/Admin', [
        'sistemasCount' => Sistema::count(),
        'equiposCount' => InventarioEquipo::count(),
        'equiposPorTipo' => InventarioEquipo::groupBy('tipo_pc')->selectRaw('tipo_pc, count(*) as total')->pluck('total', 'tipo_pc'),
        'sistemasPorEstatus' => Sistema::groupBy('estatus')->selectRaw('estatus, count(*) as total')->pluck('total', 'estatus'),
        'ultimosEquipos' => InventarioEquipo::with('departamento')->latest()->take(5)->get(),
        'sistemasRecientes' => Sistema::with('departamento')->latest()->take(5)->get()
    ]);
}
}
