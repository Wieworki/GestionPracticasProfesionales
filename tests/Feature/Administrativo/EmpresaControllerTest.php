<?php

namespace Tests\Feature\Administrativo;

use Tests\TestCase;
use App\Models\TipoUsuario;
use App\Models\Usuario;
use App\Models\Administrativo;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmpresaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_administrativo_puede_registrar_una_empresa()
    {
        // Arrange
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $user->id]);

        $payload = [
            'nombre' => 'TechCorp',
            'cuit' => '30-12345678-9',
            'email' => 'contacto@techcorp.com',
            'telefono' => '3415551122',
            'descripcion' => 'Descripcion empresa',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Act
        $response = $this->actingAs($user)->post(route('administrativo.empresas.store'), $payload);

        // Assert
        $response->assertRedirect(route('administrativo.empresas.index'));

        // Verificamos que se creó correctamente el usuario
        $this->assertDatabaseHas('usuario', [
            'nombre' => 'TechCorp',
            'email' => 'contacto@techcorp.com',
            'telefono' => '3415551122',
        ]);

        $this->assertDatabaseHas('empresa', [
            'cuit' => '30-12345678-9',
            'descripcion' => 'Descripcion empresa',
        ]);
    }


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
