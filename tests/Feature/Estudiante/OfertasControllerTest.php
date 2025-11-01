<?php

namespace Tests\Feature\Estudiante;

use App\Models\Usuario;
use App\Models\TipoUsuario;
use App\Models\Estudiante;
use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\Postulacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfertasControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_estudiante_puede_ver_todas_las_ofertas()
    {
        // Arrange
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id ]);

        $userEmpresaHabilitada = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $empresaHabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaHabilitada->id ]);

        $ofertas = collect([
            Oferta::factory()->create([
                'empresa_id' => $empresaHabilitada->id,
                'estado' => Oferta::ESTADO_ACTIVA,
            ]),
            Oferta::factory()->create([
                'empresa_id' => $empresaHabilitada->id,
                'estado' => Oferta::ESTADO_PENDIENTE,
            ]),
        ]);

        $response = $this->actingAs($user)->get(route('estudiante.ofertas.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page
                ->component('estudiante/ofertas/ListadoOfertas')
                ->has('ofertas.data', 1)
                ->where('ofertas.data.0.titulo', $ofertas[0]->titulo)
        );
    }

    /** @test */
    public function un_usuario_estudiante_puede_filtrar_ofertas_por_empresa()
    {
        // Arrange
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id]);

        // Creamos dos empresas habilitadas
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresa1 = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id, 'habilitado' => true]);
        $empresa1 = Empresa::factory()->create(['usuario_id' => $userEmpresa1->id]);

        $userEmpresa2 = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id, 'habilitado' => true]);
        $empresa2 = Empresa::factory()->create(['usuario_id' => $userEmpresa2->id]);

        // Creamos ofertas para ambas empresas
        $ofertaEmpresa1 = Oferta::factory()->create([
            'empresa_id' => $empresa1->id,
            'estado' => Oferta::ESTADO_ACTIVA,
        ]);

        $ofertaEmpresa2 = Oferta::factory()->create([
            'empresa_id' => $empresa2->id,
            'estado' => Oferta::ESTADO_ACTIVA,
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('estudiante.ofertas.index', [
            'empresaId' => $empresa1->id
        ]));

        // Assert
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) =>
            $page
                ->component('estudiante/ofertas/ListadoOfertas')
                ->has('ofertas.data', 1) // Solo una oferta visible
                ->where('ofertas.data.0.id', $ofertaEmpresa1->id)
                ->where('ofertas.data.0.titulo', $ofertaEmpresa1->titulo)
                ->where('nombreEmpresaFiltro', $empresa1->nombre)
        );
    }

    //
    /** @test */
    public function un_estudiante_puede_ver_una_oferta_visible_y_activa()
    {
        // Arrange
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresaHabilitada = Usuario::factory()->habilitado()->create(['tipo_id' => $tipoEmpresa->id ]);
        $empresaHabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaHabilitada->id ]);

        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresaHabilitada->id,
            'estado' => Oferta::ESTADO_ACTIVA,
        ]);

        $response = $this->actingAs($user)
            ->get(route('estudiante.oferta.show', $oferta->id));

        $response->assertStatus(200)
                 ->assertInertia(fn ($page) =>
                    $page->component('estudiante/ofertas/ShowOferta')
                        ->where('oferta.id', $oferta->id)
                        ->where('oferta.titulo', $oferta->titulo)
                        ->where('oferta.estado', Oferta::ESTADO_ACTIVA)
                 );
    }

    /** @test */
    public function un_estudiante_no_puede_ver_una_oferta_eliminada()
    {
        // Arrange
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresaHabilitada = Usuario::factory()->habilitado()->create(['tipo_id' => $tipoEmpresa->id ]);
        $empresaHabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaHabilitada->id ]);

        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresaHabilitada->id,
            'estado' => Oferta::ESTADO_ELIMINADA,
        ]);

        $response = $this->actingAs($user)
            ->get(route('estudiante.oferta.show', $oferta->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function un_estudiante_no_puede_ver_una_oferta_de_una_empresa_no_habilitada()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresaDeshabilitada = Usuario::factory()->deshabilitado()->create(['tipo_id' => $tipoEmpresa->id ]);
        $empresaDeshabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaDeshabilitada->id ]);

        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresaDeshabilitada->id,
            'estado' => Oferta::ESTADO_ACTIVA,
        ]);

        $response = $this->actingAs($user)
            ->get(route('estudiante.oferta.show', $oferta->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function un_usuario_no_autenticado_no_puede_ver_el_detalle_de_una_oferta()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresaHabilitada = Usuario::factory()->habilitado()->create(['tipo_id' => $tipoEmpresa->id ]);
        $empresaHabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaHabilitada->id ]);


        $oferta = Oferta::factory()->create(['empresa_id' => $empresaHabilitada]);
        $response = $this->get(route('estudiante.oferta.show', $oferta->id));
        $response->assertRedirect(route('login'));
    }

    //
     /** @test */
    public function un_estudiante_puede_postularse_a_una_oferta_activa_visible_y_no_postulada()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresaHabilitada = Usuario::factory()->habilitado()->create(['tipo_id' => $tipoEmpresa->id ]);
        $empresaHabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaHabilitada->id ]);

        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresaHabilitada->id,
            'estado' => Oferta::ESTADO_ACTIVA,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('estudiante.oferta.postular', $oferta->id));

        $response->assertRedirect(route('estudiante.oferta.show', $oferta->id));
        $response->assertSessionHas('success', 'Postulacion exitosa.');

        $this->assertDatabaseHas('postulacion', [
            'oferta_id' => $oferta->id,
            'estudiante_id' => $estudiante->id,
        ]);
    }

    /** @test */
    public function no_puede_postularse_si_la_oferta_no_es_visible()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresaHabilitada = Usuario::factory()->habilitado()->create(['tipo_id' => $tipoEmpresa->id ]);
        $empresaHabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaHabilitada->id ]);

        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresaHabilitada->id,
            'estado' => Oferta::ESTADO_ELIMINADA,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('estudiante.oferta.postular', $oferta->id));

        $response->assertStatus(403);
        $this->assertDatabaseMissing('postulacion', [
            'oferta_id' => $oferta->id,
            'estudiante_id' => $estudiante->id,
        ]);
    }

    /** @test */
    public function no_puede_postularse_dos_veces_a_la_misma_oferta()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $user->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresaHabilitada = Usuario::factory()->habilitado()->create(['tipo_id' => $tipoEmpresa->id ]);
        $empresaHabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaHabilitada->id ]);

        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresaHabilitada->id,
            'estado' => Oferta::ESTADO_ACTIVA,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('estudiante.oferta.postular', $oferta->id));

        $response2 = $this->actingAs($user)
            ->patch(route('estudiante.oferta.postular', $oferta->id));

        $response2->assertStatus(403);
    }
}
