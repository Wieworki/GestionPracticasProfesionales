<?php

namespace Database\Seeders;

use App\Models\Administrativo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            TipoUsuarioSeeder::class,
            UsuarioSeeder::class,
            EmpresaSeeder::class,
            EstudianteSeeder::class,
            AdministrativoSeeder::class,
            FacultadSeeder::class,
            CarreraSeeder::class,
        ]);
    }
}
