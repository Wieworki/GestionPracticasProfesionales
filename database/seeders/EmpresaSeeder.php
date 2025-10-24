<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\TipoUsuario;
use Illuminate\Support\Facades\Hash;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habilitado = true;
        $idEmpresa = TipoUsuario::where('nombre', 'empresa')->first()->id;
        for ($i = 0; $i < 15; $i++) {
            $user = Usuario::Create(
                [
                    'nombre' => 'Empresa ' . $i,
                    'apellido' => '',
                    'email' => 'empresa' . $i . '@empresa.com',
                    'password' => Hash::make('empresa'),
                    'habilitado' => $habilitado,
                    'tipo_id' => $idEmpresa
                ]
            );
            Empresa::factory()->create(['usuario_id' => $user->id ]);
            $habilitado = !$habilitado;
        }
    }
}
