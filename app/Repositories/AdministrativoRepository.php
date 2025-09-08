<?php

namespace App\Repositories;

use App\Models\Administrativo;

class AdministrativoRepository
{
    public function create(array $data): Administrativo
    {
        return Administrativo::create($data);
    }
}