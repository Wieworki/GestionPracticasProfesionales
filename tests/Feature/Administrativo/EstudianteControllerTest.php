<?php

namespace Tests\Feature\Administrativo;

use App\Models\Usuario;
use App\Models\Estudiante;
use App\Models\TipoUsuario;
use App\Models\Administrativo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
}
