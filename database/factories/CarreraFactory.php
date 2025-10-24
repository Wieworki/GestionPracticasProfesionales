<?php

namespace Database\Factories;

use App\Models\Facultad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Carrera>
 */
class CarreraFactory extends Factory
{
    public function definition(): array
    {
        return [
            'facultad_id' => Facultad::factory(),
            'nombre' => $this->faker->sentence(6, true),
        ];
    }
}
