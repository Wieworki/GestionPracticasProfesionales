<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facultad;
use App\Models\Carrera;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $idUNL = Facultad::where('nombre', 'UNL')->first()->id;
        Carrera::firstOrCreate(
            ['nombre' => Carrera::generic],
            [
                'nombre' =>  Carrera::generic,
                'facultad_id' => $idUNL
            ]
        );
    }
}
