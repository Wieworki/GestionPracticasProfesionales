<?php

namespace Tests\Feature\Administrativo;

use App\Models\Usuario;
use App\Models\Estudiante;
use App\Models\TipoUsuario;
use App\Models\Administrativo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class EstudianteControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_administrativo_puede_ver_el_listado_de_estudiantes()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $user->id ]);

        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        for ($i = 0; $i < 3; $i++) {
            $userEstudiante = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
            Estudiante::factory()->create(['usuario_id' => $userEstudiante->id ]);
        }

        $response = $this->actingAs($user)->get(route('administrativo.estudiantes.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('administrativo/VerEstudiantes')
                 ->has('estudiantes.data', 3)
        );
    }

    /** @test */
    public function se_pueden_filtrar_estudiantes_por_nombre()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $user->id ]);

        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $userEstudiante1 = Usuario::factory()->create([
            'tipo_id' => $tipoEstudiante->id,
            'nombre' => 'Juan Pérez'
        ]);
        $userEstudiante2 = Usuario::factory()->create([
            'tipo_id' => $tipoEstudiante->id,
            'nombre' => 'Ana Torres'
        ]);
        Estudiante::factory()->create(['usuario_id' => $userEstudiante1->id ]);
        Estudiante::factory()->create(['usuario_id' => $userEstudiante2->id ]);

        $response = $this->actingAs($user)->get(route('administrativo.estudiantes.index', [
            'search' => 'Juan'
        ]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->where('filters.search', 'Juan')
                 ->has('estudiantes.data', 1)
                 ->where('estudiantes.data.0.nombre', 'Juan Pérez')
        );
    }

    /** @test */
    public function usuarios_no_autorizados_no_pueden_acceder()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $estudiante = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);

        $response = $this->actingAs($estudiante)->get(route('administrativo.estudiantes.index'));
        $response->assertStatus(403);
    }

    /** @test */
    public function un_administrativo_puede_ver_el_detalle_de_un_estudiante()
    {
        $this->withoutExceptionHandling();

        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $administrativo = Administrativo::factory()->create([
            'usuario_id' => $user->id
        ]);

        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $userEstudiante = Usuario::factory()->habilitado()->create([
            'tipo_id' => $tipoEstudiante->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '3411234567',
            'email' => 'juan@example.com',
        ]);
        $estudiante = Estudiante::factory()->create([
            'usuario_id' => $userEstudiante->id,
            'dni' => '12345678',
        ]);

        $response = $this->actingAs($user)->get(route('administrativo.estudiantes.show', $estudiante->id));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('administrativo/ShowEstudiante')
            ->has('estudiante', fn (Assert $data) => $data
                ->where('id', $estudiante->id)
                ->where('nombre', 'Juan')
                ->where('apellido', 'Pérez')
                ->where('dni', '12345678')
                ->where('telefono', '3411234567')
                ->where('email', 'juan@example.com')
                ->where('habilitado', true)
                ->etc()
            )
        );

        $response->assertStatus(200);
    }

    /** @test */
    
    public function un_administrativo_puede_actualizar_los_datos_de_un_estudiante()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $administrativo = Administrativo::factory()->create([
            'usuario_id' => $user->id
        ]);

        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $userEstudiante = Usuario::factory()->habilitado()->create([
            'tipo_id' => $tipoEstudiante->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '3411234567',
            'email' => 'juan@example.com',
        ]);
        $estudiante = Estudiante::factory()->create([
            'dni' => '12345678',
        ]);

        $payload = [
            'nombre' => 'Juan Carlos',
            'apellido' => 'Pérez',
            'dni' => '12345678',
            'telefono' => '3419998888',
            'email' => 'juancarlos@example.com',
            'habilitado' => false,
        ];

        $response = $this->actingAs($user)->patch(
            route('administrativo.estudiantes.update', $estudiante->id), 
            $payload
        );

        $response->assertRedirect(route('administrativo.estudiantes.show', $estudiante->id));

        $this->assertDatabaseHas('usuario', [
            'id' => $estudiante->usuario_id,
            'nombre' => 'Juan Carlos',
            'telefono' => '3419998888',
            'email' => 'juancarlos@example.com',
            'habilitado' => false,
        ]);

        $this->assertDatabaseHas('estudiante', [
            'id' => $estudiante->id,
            'dni' => '12345678',
        ]);
    }
}
