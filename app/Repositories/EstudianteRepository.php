<?php

namespace App\Repositories;

use App\Models\Estudiante;

class EstudianteRepository
{
    public function create(array $data): Estudiante
    {
        return Estudiante::create($data);
    }
}