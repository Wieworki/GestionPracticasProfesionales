<?php

namespace App\Http\Controllers;

use App\Models\TipoUsuario;
use App\Http\Requests\StoreTipoUsuarioRequest;
use App\Http\Requests\UpdateTipoUsuarioRequest;
use Inertia\Inertia;

class TipoUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('tipoUsuario/home');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoUsuario $tipoUsuario)
    {
        return Inertia::render('tipoUsuario/show');
    }
}
