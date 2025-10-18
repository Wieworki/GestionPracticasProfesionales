<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Estudiante;
use App\Models\TipoUsuario;

class EstudianteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habilitado = true;
        $idEstudiante = TipoUsuario::where('nombre', 'estudiante')->first()->id;
        for ($i = 0; $i < 15; $i++) {
            $user = Usuario::factory()->changePassword('estudiante')->Create(
                [
                    'email' => 'estudiante' . $i . '@unl.com',
                    'habilitado' => $habilitado,
                    'tipo_id' => $idEstudiante
                ]
            );
            Estudiante::factory()->create(['usuario_id' => $user->id ]);
            $habilitado = !$habilitado;
        }
    }
}
