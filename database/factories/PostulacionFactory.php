<?php

namespace Database\Factories;

use App\Models\Oferta;
use App\Models\Estudiante;
use App\Models\Postulacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Postulacion>
 */
class PostulacionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'oferta_id' => Oferta::factory(),
            'estudiante_id' => Estudiante::factory(),
            'fecha_creacion' => now(),
            'estado' => $this->faker->randomElement([
                Postulacion::ESTADO_ACTIVA, 
                Postulacion::ESTADO_ANULADA,
                Postulacion::ESTADO_CONFIRMADA,
                Postulacion::ESTADO_RECHAZADA,
                Postulacion::ESTADO_SELECCIONADA,
            ]),
        ];
    }
}
