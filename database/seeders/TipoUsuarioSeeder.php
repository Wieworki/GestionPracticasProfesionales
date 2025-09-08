<?php

namespace Database\Seeders;

use App\Models\TipoUsuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoUsuario::firstOrCreate(
            ['nombre' => 'admin'],
            ['nombre' => 'admin'],
        );

        TipoUsuario::firstOrCreate(
            ['nombre' => 'administrativo'],
            ['nombre' => 'administrativo'],
        );

        TipoUsuario::firstOrCreate(
            ['nombre' => 'empresa'],
            ['nombre' => 'empresa'],
        );

        TipoUsuario::firstOrCreate(
            ['nombre' => 'estudiante'],
            ['nombre' => 'estudiante'],
        );
        
    }
}
