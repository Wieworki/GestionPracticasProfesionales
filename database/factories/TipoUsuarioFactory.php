<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TipoUsuarioFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->name()
        ];
    }

    public function isEstudiante(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'estudiante',
        ]);
    }

    public function isEmpresa(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'empresa',
        ]);
    }

    public function isAdministrativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'administrativo',
        ]);
    }
}
