<?php

namespace App\Repositories;

use App\Models\TipoUsuario;

class TipoUsuarioRepository
{

    public function getTipoEmpresa(): ?TipoUsuario
    {
        return TipoUsuario::where('nombre', 'empresa')->first();
    }

    public function getTipoEstudiante(): ?TipoUsuario
    {
        return TipoUsuario::where('nombre', 'estudiante')->first();
    }

    public function getTipoAdministrativo(): ?TipoUsuario
    {
        return TipoUsuario::where('nombre', 'administrativo')->first();
    }
}