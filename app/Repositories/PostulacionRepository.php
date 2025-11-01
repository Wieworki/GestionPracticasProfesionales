<?php

namespace App\Repositories;

use App\Models\Oferta;
use App\Models\Postulacion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PostulacionRepository
{   
    public function getPostulacionesOferta(?string $search = null, int $perPage = 10, ?int $ofertaId = null): LengthAwarePaginator
    {
        $query = DB::table('oferta')
        ->join('empresa', 'oferta.empresa_id', '=', 'empresa.id')
        ->join('usuario AS usuarioEmpresa', 'empresa.usuario_id', '=', 'usuarioEmpresa.id')
        ->join('postulacion', 'postulacion.oferta_id', '=', 'oferta.id')
        ->join('estudiante', 'postulacion.estudiante_id', '=', 'estudiante.id')
        ->join('usuario AS usuarioEstudiante', 'estudiante.usuario_id', '=', 'usuarioEstudiante.id')
        ->select(
            'postulacion.id',
            'postulacion.oferta_id',
            'usuarioEmpresa.nombre AS empresa',
            'usuarioEstudiante.nombre AS estudiante',
            'oferta.titulo',
            'postulacion.fecha_creacion',
            'postulacion.estado'
        )
        ->where('postulacion.estado', '!=', Postulacion::ESTADO_ANULADA)
        ->where('oferta.id', $ofertaId)
        ->orderBy('postulacion.created_at', 'desc');

        if ($search) {
            $likeOperator = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $query->where('usuarioEstudiante.nombre', $likeOperator, "%{$search}%");
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getPostulacion($ofertaId, $estudianteId)
    {
        return Postulacion::where(function ($query) use ($ofertaId, $estudianteId) {
            $query->where('oferta_id', $ofertaId)
                    ->Where('estudiante_id', $estudianteId);
        })->first();
    }

    public function getPostulacionesEstudiante(?string $search = null, int $perPage = 10, ?int $estudianteId = null): LengthAwarePaginator
    {
        $query = DB::table('postulacion')
        ->join('oferta', 'oferta.id', '=', 'postulacion.oferta_id')
        ->join('empresa', 'oferta.empresa_id', '=', 'empresa.id')
        ->join('usuario AS usuarioEmpresa', 'empresa.usuario_id', '=', 'usuarioEmpresa.id')
        ->join('estudiante', 'postulacion.estudiante_id', '=', 'estudiante.id')
        ->join('usuario AS usuarioEstudiante', 'estudiante.usuario_id', '=', 'usuarioEstudiante.id')
        ->select(
            'postulacion.id',
            'postulacion.oferta_id',
            'usuarioEmpresa.nombre AS empresa',
            'oferta.titulo',
            'postulacion.fecha_creacion',
            'postulacion.estado'
        )
        ->where('postulacion.estudiante_id', $estudianteId)
        ->orderBy('postulacion.created_at', 'desc');

        if ($search) {
            $likeOperator = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $query->where('oferta.titulo', $likeOperator, "%{$search}%");
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
