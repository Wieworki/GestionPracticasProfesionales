<?php

namespace App\Services;

use App\Repositories\UsuarioRepository;
use App\Repositories\TipoUsuarioRepository;
use App\Repositories\EmpresaRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmpresaService
{
    protected $usuarioRepository;
    protected $tipoUsuarioRepository;
    protected $empresaRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository,
        TipoUsuarioRepository $tipoUsuarioRepository, 
        EmpresaRepository $empresaRepository
        )
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->tipoUsuarioRepository = $tipoUsuarioRepository;
        $this->empresaRepository = $empresaRepository;
    }

    public function createEmpresaWithUser(array $userData, array $empresaData)
    {
        return DB::transaction(function () use ($userData, $empresaData) {
            $userData['tipo_id'] = $this->tipoUsuarioRepository->getTipoEmpresa()->id;
            $user = $this->usuarioRepository->create($userData);

            $empresaData['usuario_id'] = $user->id;
            $empresa = $this->empresaRepository->create($empresaData);

            return $empresa;
        });
    }
}
