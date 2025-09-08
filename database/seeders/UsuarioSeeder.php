<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::firstOrCreate(
            ['nombre' => 'admin'],
            [
                'nombre' => 'admin',
                'apellido' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
                'habilitado' => true,
                'tipo_id' => 1
            ]
        );
    }
}
