<?php

namespace Tests\Feature\Administrativo;

use App\Models\Administrativo;
use App\Models\Usuario;
use App\Models\TipoUsuario;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowEmpresaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function administrativo_puede_ver_detalle_de_empresa_habilitada()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuarioEmpresa = Usuario::factory()->habilitado()->create(
            ['tipo_id' => $tipoEmpresa->id]
        );
        $empresa = Empresa::factory()->create([
            'usuario_id' => $usuarioEmpresa->id,
            'descripcion' => 'Software a medida',
            'sitio_web' => 'https://tech.com',
        ]);

        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $usuarioAdministrativo = Usuario::factory()->habilitado()->create(
            ['tipo_id' => $tipoAdministrativo->id]
        );
        $administrativo = Administrativo::factory()->create([
            'usuario_id' => $usuarioAdministrativo->id,
        ]);

        $response = $this->actingAs($usuarioAdministrativo)->get("/administrativo/empresas/show/{$empresa->id}");

        $response->assertStatus(200)
                 ->assertInertia(fn ($page) => $page
                     ->component('administrativo/ShowEmpresa')
                     ->where('empresa.id', $empresa->id)
                 );
    }

    /** @test */
    public function administrativo_puede_ver_detalle_de_empresa_no_habilitada()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $usuarioAdministrativo = Usuario::factory()->habilitado()->create(
            ['tipo_id' => $tipoAdministrativo->id]
        );
        $administrativo = Administrativo::factory()->create([
            'usuario_id' => $usuarioAdministrativo->id,
        ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuarioEmpresa = Usuario::factory()->deshabilitado()->create(
            ['tipo_id' => $tipoEmpresa->id]
        );
        $empresa = Empresa::factory()->create([
            'usuario_id' => $usuarioEmpresa->id,
        ]);

        $response = $this->actingAs($usuarioAdministrativo)->get("/administrativo/empresas/show/{$empresa->id}");

        $response->assertStatus(200)
                 ->assertInertia(fn ($page) => $page
                     ->component('administrativo/ShowEmpresa')
                     ->where('empresa.id', $empresa->id)
                 );
    }
}
