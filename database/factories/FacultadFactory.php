<?php

namespace Database\Factories;

use App\Models\Facultad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facultad>
 */
class FacultadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->sentence(6, true),
        ];
    }
}
