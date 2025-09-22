<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Repositories\TipoUsuarioRepository;

class DashboardRedirectController extends Controller
{

    public function __construct(
        protected TipoUsuarioRepository $tipoUsuarioRepository
        )
    {
        $this->tipoUsuarioRepository = $tipoUsuarioRepository;
    }

    public function __invoke()
    {
        $user = Auth::user();

        return match ($user->tipoUsuario->id) {
            $this->tipoUsuarioRepository->getTipoEmpresa()->id => redirect()->route('empresa.dashboard'),
            $this->tipoUsuarioRepository->getTipoEstudiante()->id => redirect()->route('estudiante.dashboard'),
            $this->tipoUsuarioRepository->getTipoAdministrativo()->id => redirect()->route('administrativo.dashboard'),
            default => redirect()->route('logout'),
        };
    }
}
