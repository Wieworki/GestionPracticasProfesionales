<?php

namespace Database\Seeders;

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
        DB::table('tipo_usuario')->insert([
            [
                'nombre' => 'administrativo',
            ],
            [
                'nombre' => 'empresa',
            ],
            [
                'nombre' => 'estudiante',
            ],
        ]);
    }
}
