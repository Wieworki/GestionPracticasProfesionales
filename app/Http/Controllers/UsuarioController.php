<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\TipoUsuario;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('usuario/index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposUsuario = TipoUsuario::all();
        return Inertia::render('usuario/create', ['tiposUsuario' => $tiposUsuario]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUsuarioRequest $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:255',
            'apellido' => 'max:255',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|max:255',
        ]);

        Log::error("TIPO USUARIO: " . $request->tipo_usuario);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipo_id' => $request->tipo_usuario,
        ]);

        return Inertia::render('usuario/show', ['usuario' => $usuario->id]);
    }

    public function show(Usuario $usuario)
    {
        return Inertia::render('usuario/show', ['usuario' => $usuario->id]);
    }

    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        //
    }
}
