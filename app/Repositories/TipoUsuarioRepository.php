<?php

namespace App\Repositories;

use App\Models\TipoUsuario;

class TipoUsuarioRepository
{

    public function getTipoEmpresa(): ?TipoUsuario
    {
        return TipoUsuario::where('nombre', 'empresa')->first();
    }
}