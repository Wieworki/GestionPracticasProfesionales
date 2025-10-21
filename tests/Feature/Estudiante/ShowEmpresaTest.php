<?php

namespace Tests\Feature\Estudiante;

use App\Models\Usuario;
use App\Models\TipoUsuario;
use App\Models\Estudiante;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowEmpresaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function estudiante_puede_ver_detalle_de_empresa_habilitada()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $estudiante = Usuario::factory()->habilitado()->create(
            ['tipo_id' => $tipoEstudiante->id]
        );

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuarioEmpresa = Usuario::factory()->habilitado()->create(
            ['tipo_id' => $tipoEmpresa->id]
        );
        $empresa = Empresa::factory()->create([
            'usuario_id' => $usuarioEmpresa->id,
            'descripcion' => 'Software a medida',
            'sitio_web' => 'https://tech.com',
        ]);

        $response = $this->actingAs($estudiante)->get("/estudiante/empresas/show/{$empresa->id}");

        $response->assertStatus(200)
                 ->assertInertia(fn ($page) => $page
                     ->component('estudiante/ShowEmpresa')
                     ->where('empresa.id', $empresa->id)
                 );
    }

    /** @test */
    public function estudiante_no_puede_ver_empresa_no_habilitada()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $estudiante = Usuario::factory()->habilitado()->create(
            ['tipo_id' => $tipoEstudiante->id]
        );

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuarioEmpresa = Usuario::factory()->deshabilitado()->create(
            ['tipo_id' => $tipoEmpresa->id]
        );
        $empresa = Empresa::factory()->create([
            'usuario_id' => $usuarioEmpresa->id,
        ]);

        $response = $this->actingAs($estudiante)->get("/estudiante/empresas/show/{$empresa->id}");

        $response->assertRedirect('/estudiante/dashboard');
    }
}
