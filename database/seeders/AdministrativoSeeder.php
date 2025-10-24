<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Administrativo;
use App\Models\TipoUsuario;

class AdministrativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habilitado = true;
        $idAdministrativo = TipoUsuario::where('nombre', 'administrativo')->first()->id;
        for ($i = 0; $i < 15; $i++) {
            $user = Usuario::factory()->changePassword('administrativo')->Create(
                [
                    'email' => 'administrativo' . $i . '@unl.com',
                    'habilitado' => $habilitado,
                    'tipo_id' => $idAdministrativo
                ]
            );
            Administrativo::factory()->create(['usuario_id' => $user->id ]);
            $habilitado = !$habilitado;
        }
    }
}
