<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\TipoUsuario;
use App\Models\Usuario;

class EmpresaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_tipo_empresa_puede_ver_el_formulario_de_edicion()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id ]);
        $empresa = Empresa::factory()->create(['usuario_id' => $user->id ]);

        // Act
        $response = $this->actingAs($user)->get(route('empresa.edit', $empresa));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('empresa/Edit')
                 ->has('empresa.nombre')
                 ->has('empresa.cuit')
                 ->has('empresa.sitio_web')
                 ->has('empresa.descripcion')
                 ->has('empresa.email')
                 ->has('empresa.telefono')
        );
    }

    /** @test */
    public function un_usuario_no_autenticado_no_puede_ver_el_formulario_de_edicion()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id ]);
        $empresa = Empresa::factory()->create(['usuario_id' => $user->id ]);

        $response = $this->get(route('empresa.edit', $empresa));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function un_usuario_tipo_empresa_puede_actualizar_sus_datos()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $user = Usuario::factory()->create([
            'tipo_id' => $tipoEmpresa->id,
            'nombre' => 'Juan',
            'email' => 'juan@oldmail.com',
            'telefono' => '123456',
        ]);
        $empresa = Empresa::factory()->create(['usuario_id' => $user->id ]);

        // Act
        $response = $this->actingAs($user)->patch(route('empresa.update', $empresa), [
            'nombre' => 'Juan Actualizado',
            'email' => 'juan@newmail.com',
            'telefono' => '987654',
            'sitio_web' => 'www.test.com',
            'cuit' => '20309991234',
            'descripcion' => 'test descripcion',
        ]);

        // Assert
        $response->assertRedirect(route('empresa.perfil'));
        $this->assertDatabaseHas('usuario', [
            'id' => $user->id,
            'nombre' => 'Juan Actualizado',
            'email' => 'juan@newmail.com',
            'telefono' => '987654',
        ]);

        $this->assertDatabaseHas('empresa', [
            'sitio_web' => 'www.test.com',
            'cuit' => '20309991234',
            'descripcion' => 'test descripcion',
        ]);
    }

    /** @test */
    public function un_usuario_no_empresa_no_puede_actualizar_datos_de_otro_tipo()
    {

        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $userAdministrativo = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id ]);
        $empresa = Empresa::factory()->create(['usuario_id' => $userEmpresa->id ]);

        // Act
        $response = $this->actingAs($userAdministrativo)->patch(route('empresa.update', $empresa), [
            'nombre' => 'Intento inválido',
        ]);

        // Assert
        $response->assertForbidden();
    }

    /** @test */
    public function la_pantalla_de_perfil_muestra_los_datos_del_usuario_logueado()
    {

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $user = Usuario::factory()->create([
            'tipo_id' => $tipoEmpresa->id,
            'nombre' => 'Carla',
            'email' => 'carla@example.com',
            'telefono' => '555-555',
        ]);
        $empresa = Empresa::factory()->create(['usuario_id' => $user->id ]);

        // Act
        $response = $this->actingAs($user)->get(route('empresa.perfil'));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('empresa/Perfil')
                 ->where('empresa.nombre', 'Carla')
                 ->where('empresa.email', 'carla@example.com')
        );
    }
}
