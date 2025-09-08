<?php

namespace App\Services;

use App\Repositories\UsuarioRepository;
use App\Repositories\TipoUsuarioRepository;
use App\Repositories\EstudianteRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstudianteService
{
    protected $usuarioRepository;
    protected $tipoUsuarioRepository;
    protected $estudianteRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository,
        TipoUsuarioRepository $tipoUsuarioRepository, 
        EstudianteRepository $estudianteRepository
        )
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->tipoUsuarioRepository = $tipoUsuarioRepository;
        $this->estudianteRepository = $estudianteRepository;
    }

    public function createEstudianteWithUser(array $userData, array $estudianteData)
    {
        return DB::transaction(function () use ($userData, $estudianteData) {
            $userData['tipo_id'] = $this->tipoUsuarioRepository->getTipoEstudiante()->id;
            $user = $this->usuarioRepository->create($userData);

            $estudianteData['usuario_id'] = $user->id;
            $estudiante = $this->estudianteRepository->create($estudianteData);

            return $estudiante;
        });
    }
}
