<?php

namespace App\Repositories;

use App\Models\Empresa;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EmpresaRepository
{
    public function create(array $data): Empresa
    {
        return Empresa::create($data);
    }

    /**
     * Devuelve todas las empresas (para administrativo),
     * con soporte de búsqueda y paginación.
     */
    public function getAll(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
    $query = DB::table('empresa')
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
        ->orderBy('empresa.created_at', 'desc');

    if (!empty($search)) {
        $likeOperator = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like'; //Laravel usa SQLite con refresh data base, que no usa ilike

        $query->where(function ($q) use ($search, $likeOperator) {
            $q->where('usuario.nombre', $likeOperator, "%{$search}%")
              ->orWhere('usuario.email', $likeOperator, "%{$search}%")
              ->orWhere('empresa.cuit', $likeOperator, "%{$search}%");
        });
    }

    return $query->paginate($perPage)->withQueryString();
}

    /**
     * Devuelve solo las empresas habilitadas (para estudiante),
     * con soporte de búsqueda y paginación.
     */
    public function getHabilitadas(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = DB::table('empresa')
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
            ->orderBy('empresa.created_at', 'desc');

        if (!empty($search)) {
            $likeOperator = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';

            $query->where(function ($q) use ($search, $likeOperator) {
                $q->where('usuario.nombre', $likeOperator, "%{$search}%")
                ->orWhere('usuario.email', $likeOperator, "%{$search}%")
                ->orWhere('empresa.cuit', $likeOperator, "%{$search}%");
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findHabilitadaById($id): ?Empresa
    {
        return Empresa::where('empresa.id', $id)
            ->whereHas('usuario', function ($q) {
                $q->where('habilitado', true);
            })
            ->first();
    }

    public function findById($id): ?Empresa
    {
        return Empresa::where('empresa.id', $id)
            ->first();
    }
}
