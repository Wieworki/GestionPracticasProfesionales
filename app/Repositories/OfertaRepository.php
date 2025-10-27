<?php

namespace App\Repositories;

use App\Models\Oferta;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OfertaRepository
{
    public function listarPorEmpresa(int $empresaId, ?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = DB::table('oferta')
            ->join('empresa', 'oferta.empresa_id', '=', 'empresa.id')
            ->select(
                'oferta.id',
                'oferta.titulo',
                'oferta.descripcion',
                'oferta.fecha_creacion',
                'oferta.fecha_cierre',
                'oferta.estado',
                'oferta.modalidad',
                'oferta.empresa_id',
            )
            ->where('empresa_id', $empresaId)
            ->orderBy('oferta.fecha_creacion', 'desc');

        if ($search) {
            $likeOperator = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $query->where('titulo', $likeOperator, "%{$search}%");
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getAll(?string $search = null, int $perPage = 10, ?int $empresaId = null): LengthAwarePaginator
    {
        $query = DB::table('oferta')
            ->join('empresa', 'oferta.empresa_id', '=', 'empresa.id')
            ->join('usuario', 'empresa.usuario_id', '=', 'usuario.id')
            ->select(
                'oferta.id',
                'oferta.titulo',
                'oferta.descripcion',
                'oferta.fecha_creacion',
                'oferta.fecha_cierre',
                'oferta.estado',
                'oferta.modalidad',
                'oferta.empresa_id',
                'usuario.nombre AS empresa'
            )
            ->orderBy('oferta.fecha_creacion', 'desc');

        if ($empresaId) {
            $query->where('empresa_id', $empresaId);
        }

        if ($search) {
            $likeOperator = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $query->where('titulo', $likeOperator, "%{$search}%");
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Busca todas las ofertas que pueden ser visibles (no fueron eliminadas, ni estan pendientes)
     */
    public function getOfertasVisibles(?string $search = null, int $perPage = 10, ?int $empresaId = null): LengthAwarePaginator
    {
        $query = DB::table('oferta')
            ->join('empresa', 'oferta.empresa_id', '=', 'empresa.id')
            ->join('usuario', 'empresa.usuario_id', '=', 'usuario.id')
            ->select(
                'oferta.id',
                'oferta.titulo',
                'oferta.descripcion',
                'oferta.fecha_creacion',
                'oferta.fecha_cierre',
                'oferta.estado',
                'oferta.modalidad',
                'oferta.empresa_id',
                'usuario.nombre AS empresa'
            )
            ->where(function ($query) {
                $query->where('oferta.estado', Oferta::ESTADO_ACTIVA)
                    ->orWhere('oferta.estado', Oferta::ESTADO_FINALIZADA);
            })
            
            ->orderBy('oferta.fecha_creacion', 'desc');

        if ($empresaId) {
            $query->where('empresa_id', $empresaId);
        }

        if ($search) {
            $likeOperator = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $query->where('titulo', $likeOperator, "%{$search}%");
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
