<?php

namespace App\Services;

use App\Repositories\UsuarioRepository;
use App\Repositories\TipoUsuarioRepository;
use App\Repositories\AdministrativoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdministrativoService
{
    protected $usuarioRepository;
    protected $tipoUsuarioRepository;
    protected $administrativoRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository,
        TipoUsuarioRepository $tipoUsuarioRepository, 
        AdministrativoRepository $administrativoRepository
        )
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->tipoUsuarioRepository = $tipoUsuarioRepository;
        $this->administrativoRepository = $administrativoRepository;
    }

    public function createAdministrativoWithUser(array $userData, array $administrativoData)
    {
        return DB::transaction(function () use ($userData, $administrativoData) {
            $userData['tipo_id'] = $this->tipoUsuarioRepository->getTipoAdministrativo()->id;
            $user = $this->usuarioRepository->create($userData);

            $administrativoData['usuario_id'] = $user->id;
            $administrativo = $this->administrativoRepository->create($administrativoData);

            return $administrativo;
        });
    }
}
