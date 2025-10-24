<?php

namespace Tests\Feature\Empresa;

use App\Models\Usuario;
use App\Models\TipoUsuario;
use App\Models\Empresa;
use App\Models\Estudiante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EmpresaListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_no_autenticado_no_puede_ver_el_listado_de_empresas()
    {
        $response = $this->get(route('estudiante.empresas.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('administrativo.empresas.index'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function un_estudiante_ve_solo_empresas_habilitadas()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $userEstudiante = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $userEstudiante->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresaHabilitada = Usuario::factory()->habilitado()->create([
            'tipo_id' => $tipoEmpresa->id, 
            'nombre' => 'Empresa Habilitada'
        ]);
        $empresaHabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaHabilitada->id ]);
        $userEmpresaDeshabilitada = Usuario::factory()->deshabilitado()->create([
            'tipo_id' => $tipoEmpresa->id,
            'nombre' => 'Empresa Inhabilitada'
         ]);
        $empresaDeshabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaDeshabilitada->id ]);

        $response = $this->actingAs($userEstudiante)->get(route('estudiante.empresas.index'));
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
            $page
                ->component('estudiante/VerEmpresas')
                ->has('empresas.data', 1)
                ->where('empresas.data.0.nombre', 'Empresa Habilitada')
        );
    }

    /** @test */
    public function un_administrativo_ve_todas_las_empresas()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $userAdministrativo = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresaHabilitada = Usuario::factory()->habilitado()->create([
            'tipo_id' => $tipoEmpresa->id, 
            'nombre' => 'Empresa Habilitada'
        ]);
        $empresaHabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaHabilitada->id ]);
        $userEmpresaDeshabilitada = Usuario::factory()->deshabilitado()->create([
            'tipo_id' => $tipoEmpresa->id,
            'nombre' => 'Empresa Inhabilitada'
         ]);
        $empresaDeshabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaDeshabilitada->id ]);

        $response = $this->actingAs($userAdministrativo)->get(route('administrativo.empresas.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
            $page
                ->component('administrativo/VerEmpresas')
                ->has('empresas.data', 2)
        );
    }

    /** @test */
    public function un_administrativo_ve_el_boton_de_crear_empresa()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $userAdministrativo = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);

        $response = $this->actingAs($userAdministrativo)->get(route('administrativo.empresas.index'));

        $response->assertInertia(fn (Assert $page) =>
            $page->where('showNewButton', true)
        );
    }

    /** @test */
    public function un_estudiante_no_ve_el_boton_de_crear_empresa()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $userEstudiante = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);

        $response = $this->actingAs($userEstudiante)->get(route('estudiante.empresas.index'));

        $response->assertInertia(fn (Assert $page) =>
            $page->where('showNewButton', false)
        );
    }
}
