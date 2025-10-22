<?php

namespace Tests\Feature\Administrativo;

use App\Models\Administrativo;
use App\Models\Usuario;
use App\Models\TipoUsuario;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConvenioEmpresaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function administrativo_confirma_convenio_empresa()
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

        $response = $this->actingAs($usuarioAdministrativo)->patch(route('administrativo.empresas.convenio'), [
            'id' => $empresa->id,
        ]);

        $response->assertRedirect(route('administrativo.empresas.show', [
            'id' => $empresa->id,
        ], absolute: false));

        $this->assertDatabaseHas('usuario', [
            'id' => $usuarioEmpresa->id,
            'habilitado' => 1,
        ]);
    }
}
