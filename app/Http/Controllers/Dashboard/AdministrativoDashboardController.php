<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class AdministrativoDashboardController extends Controller
{

    public function index()
    {
        return Inertia::render('dashboard/administrativo');
    }
}
