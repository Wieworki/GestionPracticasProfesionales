<?php

namespace App\Repositories;

use App\Models\Estudiante;
use Illuminate\Support\Facades\DB;

class EstudianteRepository
{
    public function create(array $data): Estudiante
    {
        return Estudiante::create($data);
    }

    public function listar(array $filters)
    {
        $query = Estudiante::query();
        $likeOperator = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';

        $query = DB::table('estudiante')
            ->join('usuario', 'estudiante.usuario_id', '=', 'usuario.id')
            ->select(
                'estudiante.id',
                'usuario.nombre',
                'usuario.email',
                'usuario.habilitado',
                'estudiante.dni',
                'estudiante.created_at'
            )
            ->orderBy('estudiante.created_at', 'desc');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('nombre', $likeOperator, "%{$search}%")
                ->orWhere('email', $likeOperator, "%{$search}%");
        }

        return $query->orderBy('nombre')->paginate(10)->withQueryString();
    }
}