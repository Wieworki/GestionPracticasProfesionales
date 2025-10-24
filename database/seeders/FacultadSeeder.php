<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facultad;

class FacultadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Facultad::firstOrCreate(
            ['nombre' => 'UNL'],
            [
                'nombre' => 'UNL',
            ]
        );
    }
}
