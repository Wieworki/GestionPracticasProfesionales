<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Administrativo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\TipoUsuario;
use App\Models\Usuario;

class AdministrativoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_tipo_administrativo_puede_ver_el_formulario_de_edicion()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $user->id ]);

        // Act
        $response = $this->actingAs($user)->get(route('administrativo.edit', $administrativo));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('administrativo/Edit')
                 ->has('administrativo.nombre')
                 ->has('administrativo.apellido')
                 ->has('administrativo.email')
                 ->has('administrativo.telefono')
        );
    }

    /** @test */
    public function un_usuario_no_autenticado_no_puede_ver_el_formulario_de_edicion()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $user->id ]);

        $response = $this->get(route('administrativo.edit', $administrativo));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function un_usuario_tipo_administrativo_puede_actualizar_sus_datos()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create([
            'tipo_id' => $tipoAdministrativo->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan@oldmail.com',
            'telefono' => '123456',
        ]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $user->id ]);

        // Act
        $response = $this->actingAs($user)->patch(route('administrativo.update', $administrativo), [
            'nombre' => 'Juan Actualizado',
            'apellido' => 'Pérez Gómez',
            'email' => 'juan@newmail.com',
            'telefono' => '987654',
        ]);

        // Assert
        $response->assertRedirect(route('administrativo.perfil'));
        $this->assertDatabaseHas('usuario', [
            'id' => $user->id,
            'nombre' => 'Juan Actualizado',
            'apellido' => 'Pérez Gómez',
            'email' => 'juan@newmail.com',
            'telefono' => '987654',
        ]);
    }

    /** @test */
    public function un_usuario_no_administrativo_no_puede_actualizar_datos_de_otro_tipo()
    {

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id ]);

        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $userAdministrativo = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $userAdministrativo->id ]);

        // Act
        $response = $this->actingAs($userEmpresa)->patch(route('administrativo.update', $administrativo), [
            'nombre' => 'Intento inválido',
        ]);

        // Assert
        $response->assertForbidden();
    }

    /** @test */
    public function la_pantalla_de_perfil_muestra_los_datos_del_usuario_logueado()
    {

        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create([
            'tipo_id' => $tipoAdministrativo->id,
            'nombre' => 'Carla',
            'apellido' => 'López',
            'email' => 'carla@example.com',
            'telefono' => '555-555',
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('administrativo.perfil'));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('administrativo/Perfil')
                 ->where('administrativo.nombre', 'Carla')
                 ->where('administrativo.email', 'carla@example.com')
        );
    }
}
