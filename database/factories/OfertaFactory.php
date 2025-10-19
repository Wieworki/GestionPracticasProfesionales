<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Oferta>
 */
class OfertaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'empresa_id' => Empresa::factory(),
            'titulo' => $this->faker->sentence(6, true),
            'descripcion' => $this->faker->paragraph(4, true),
            'fecha_creacion' => now(),
            'fecha_cierre' => now()->addDays($this->faker->numberBetween(5, 30)),
            'estado' => $this->faker->randomElement(['activa', 'cerrada', 'pausada']),
            'modalidad' => $this->faker->randomElement(['presencial', 'remoto', 'híbrido']),
        ];
    }
}
