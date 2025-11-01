<?php

namespace App\Services;

use App\Models\Oferta;
use App\Models\Carrera;
use App\Models\Empresa;
use App\Models\Postulacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostulacionService
{

    public function nuevaPostulacion(Oferta $oferta, int $estudianteId)
    {
        return Postulacion::create([
            'oferta_id' => $oferta->id,
            'estudiante_id' => $estudianteId,
            'fecha_creacion' => now(),
            'estado' => Postulacion::ESTADO_ACTIVA
        ]);
    }

    public function seleccionarPostulacion(int $postulacionId)
    {
        $postulacion = Postulacion::where('postulacion.id', $postulacionId)->first();
        $postulacion->update(['estado' => Postulacion::ESTADO_SELECCIONADA]);
        return $postulacion;
    }

    public function confirmarPostulacion(int $postulacionId)
    {
        $postulacion = Postulacion::where('postulacion.id', $postulacionId)->first();
        $postulacion->update(['estado' => Postulacion::ESTADO_CONFIRMADA]);
        return $postulacion;
    }
}
