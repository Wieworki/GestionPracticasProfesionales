<?php

namespace App\Helper;
use App\Models\Postulacion;

class PostulacionHelper
{
    public static function getMensajePostulacion(?Postulacion $postulacion)
    {
        $mensaje = '';
        if ($postulacion) {
            if ($postulacion->isAnulada()) {
                $mensaje = "Used anulo su postulacion en la fecha: " . $postulacion?->fecha_creacion->format('d/m/Y');
            } else {
                $mensaje = "Used se postuló a la oferta en la fecha: " . $postulacion?->fecha_creacion->format('d/m/Y');
            }
        }

        return $mensaje;
    }
}