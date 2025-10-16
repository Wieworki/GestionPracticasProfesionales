<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Estudiante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\TipoUsuario;
use App\Models\Usuario;

class EstudianteControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_tipo_estudiante_puede_ver_el_formulario_de_edicion()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id ]);

        // Act
        $response = $this->actingAs($user)->get(route('estudiante.edit', $estudiante));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('estudiante/Edit')
                 ->has('estudiante.nombre')
                 ->has('estudiante.apellido')
                 ->has('estudiante.dni')
                 ->has('estudiante.email')
                 ->has('estudiante.telefono')
        );
    }

    /** @test */
    public function un_usuario_no_autenticado_no_puede_ver_el_formulario_de_edicion()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id ]);

        $response = $this->get(route('estudiante.edit', $estudiante));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function un_usuario_tipo_estudiante_puede_actualizar_sus_datos()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create([
            'tipo_id' => $tipoEstudiante->id,
            'nombre' => 'Juan',
            'apellido' => 'Lopez',
            'email' => 'juan@oldmail.com',
            'telefono' => '123456',
        ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id ]);

        // Act
        $response = $this->actingAs($user)->patch(route('estudiante.update', $estudiante), [
            'nombre' => 'Juan Actualizado',
            'apellido' => 'Lopez Actualizado',
            'email' => 'juan@newmail.com',
            'telefono' => '987654',
            'dni' => '20309991234',
        ]);

        // Assert
        $response->assertRedirect(route('estudiante.perfil'));
        $this->assertDatabaseHas('usuario', [
            'id' => $user->id,
            'nombre' => 'Juan Actualizado',
            'apellido' => 'Lopez Actualizado',
            'email' => 'juan@newmail.com',
            'telefono' => '987654',
        ]);

        $this->assertDatabaseHas('estudiante', [
            'dni' => '20309991234',
        ]);
    }

    /** @test */
    public function un_usuario_no_estudiante_no_puede_actualizar_datos_de_otro_tipo()
    {

        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $userAdministrativo = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);

        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $userEstudiante = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $userEstudiante->id ]);

        // Act
        $response = $this->actingAs($userAdministrativo)->patch(route('estudiante.update', $estudiante), [
            'nombre' => 'Intento inválido',
        ]);

        // Assert
        $response->assertForbidden();
    }

    /** @test */
    public function la_pantalla_de_perfil_muestra_los_datos_del_usuario_logueado()
    {

        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create([
            'tipo_id' => $tipoEstudiante->id,
            'nombre' => 'Carla',
            'email' => 'carla@example.com',
            'telefono' => '555-555',
        ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id ]);

        // Act
        $response = $this->actingAs($user)->get(route('estudiante.perfil'));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('estudiante/Perfil')
                 ->where('estudiante.nombre', 'Carla')
                 ->where('estudiante.email', 'carla@example.com')
        );
    }
}
