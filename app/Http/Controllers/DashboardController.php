<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index(): Response
    {
          $user = Auth::user();

        if ($user->hasRole('Admin')) {
            return Inertia::render('Dashboard/Admin');
        } else {
            return Inertia::render('Dashboard/Usuario');
        }
    }
}
