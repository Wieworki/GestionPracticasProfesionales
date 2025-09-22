<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class EmpresaDashboardController extends Controller
{

    public function index()
    {
        return Inertia::render('dashboard/empresa');
    }
}
