<?php

namespace App\Repositories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EmpresaRepository
{
    public function create(array $data): Empresa
    {
        return Empresa::create($data);
    }

    public function getAll()
    {
        return DB::table('empresa')
            ->join('usuario', 'empresa.usuario_id', '=', 'usuario.id')
            ->select(
                'empresa.id',
                'usuario.nombre',
                'usuario.email',
                'usuario.habilitado',
                'empresa.cuit',
                'empresa.sitio_web',
                'empresa.descripcion',
                'empresa.created_at'
            )
            ->orderBy('empresa.created_at', 'desc')
            ->get();
    }

    public function getHabilitadas()
    {
        return DB::table('empresa')
            ->join('usuario', 'empresa.usuario_id', '=', 'usuario.id')
            ->where('usuario.habilitado', true)
            ->select(
                'empresa.id',
                'usuario.nombre',
                'usuario.email',
                'usuario.habilitado',
                'empresa.cuit',
                'empresa.sitio_web',
                'empresa.descripcion',
                'empresa.created_at'
            )
            ->orderBy('empresa.created_at', 'desc')
            ->get();
    }
}
