<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => fake()->numerify('###'),
            'cuit' => fake()->numerify('######'),
            'sitio_web' => fake()->name(),
            'descripcion' => fake()->name(),
        ];
    }
}
