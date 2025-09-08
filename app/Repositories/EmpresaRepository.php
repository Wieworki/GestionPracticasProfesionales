<?php

namespace App\Repositories;

use App\Models\Empresa;

class EmpresaRepository
{
    public function create(array $data): Empresa
    {
        return Empresa::create($data);
    }
}