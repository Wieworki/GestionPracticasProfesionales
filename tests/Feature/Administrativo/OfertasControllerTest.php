<?php

namespace Tests\Feature\Administrativo;

use App\Models\Usuario;
use App\Models\TipoUsuario;
use App\Models\Administrativo;
use App\Models\Empresa;
use App\Models\Oferta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfertasControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_administrativo_puede_ver_todas_las_ofertas()
    {
        // Arrange
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $user->id ]);

        $userEmpresaHabilitada = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $userEmpresaDeshabilitada = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $empresaHabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaHabilitada->id ]);
        $empresaNoHabilitada = Empresa::factory()->create(['usuario_id' => $userEmpresaDeshabilitada->id ]);

        $ofertas = collect([
            Oferta::factory()->create([
                'empresa_id' => $empresaHabilitada->id,
                'estado' => Oferta::ESTADO_ACTIVA,
            ]),
            Oferta::factory()->create([
                'empresa_id' => $empresaNoHabilitada->id,
                'estado' => Oferta::ESTADO_ELIMINADA,
            ]),
            Oferta::factory()->create([
                'empresa_id' => $empresaHabilitada->id,
                'estado' => Oferta::ESTADO_FINALIZADA,
            ]),
        ]);

        $response = $this->actingAs($user)->get(route('administrativo.ofertas.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page
                ->component('administrativo/ofertas/ListadoOfertas')
                ->has('ofertas.data', 3)
                ->where('ofertas.data.0.titulo', $ofertas[0]->titulo)
                ->where('ofertas.data.1.estado', $ofertas[1]->estado)
        );
    }

    /** @test */
    public function un_usuario_administrativo_puede_filtrar_ofertas_por_empresa()
    {
        // Arrange
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $user->id]);

        // Creamos dos empresas (una habilitada y otra no)
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresa1 = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id, 'habilitado' => true]);
        $empresa1 = Empresa::factory()->create(['usuario_id' => $userEmpresa1->id]);

        $userEmpresa2 = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id, 'habilitado' => false]);
        $empresa2 = Empresa::factory()->create(['usuario_id' => $userEmpresa2->id]);

        // Creamos ofertas para ambas empresas
        $ofertaEmpresa1_A = Oferta::factory()->create([
            'empresa_id' => $empresa1->id,
            'estado' => Oferta::ESTADO_ACTIVA,
        ]);

        $ofertaEmpresa1_B = Oferta::factory()->create([
            'empresa_id' => $empresa1->id,
            'estado' => Oferta::ESTADO_ELIMINADA,
        ]);

        $ofertaEmpresa2 = Oferta::factory()->create([
            'empresa_id' => $empresa2->id,
            'estado' => Oferta::ESTADO_FINALIZADA,
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('administrativo.ofertas.index', [
            'empresaId' => $empresa1->id
        ]));

        // Assert
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) =>
            $page
                ->component('administrativo/ofertas/ListadoOfertas')
                // Solo deben aparecer las ofertas de la empresa1 (2 en total)
                ->has('ofertas.data', 2)
                ->where('ofertas.data.0.empresa_id', $empresa1->id)
                ->where('ofertas.data.1.empresa_id', $empresa1->id)
                // Validamos que se muestre correctamente el nombre del filtro
                ->where('nombreEmpresaFiltro', $empresa1->nombre)
        );
    }

    /** @test */
    public function un_usuario_no_autenticado_no_puede_ver_el_listado_de_ofertas()
    {
        $response = $this->get(route('administrativo.ofertas.index'));
        $response->assertRedirect(route('login'));
    }

     /** @test */
    public function un_administrativo_puede_ver_el_detalle_de_una_oferta()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $user->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $userEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id ]);
        $empresa = Empresa::factory()->create(['usuario_id' => $userEmpresa->id ]);
        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresa->id,
            'titulo' => 'Desarrollador Fullstack',
            'estado' => Oferta::ESTADO_PENDIENTE,
        ]);

        $response = $this->actingAs($user)
            ->get(route('administrativo.oferta.show', $oferta->id));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('administrativo/ofertas/ShowOferta')
                ->where('oferta.id', $oferta->id)
                ->where('oferta.titulo', 'Desarrollador Fullstack')
                ->where('oferta.estado', Oferta::ESTADO_PENDIENTE)
                ->where('oferta.empresa', $oferta->empresa->usuario->nombre)
                ->where('oferta.canBeVisible', true)
        );
    }

    /** @test */
    public function un_administrativo_puede_poner_visible_una_oferta_pendiente()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $user->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuarioEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id]);
        $empresa = Empresa::factory()->create(['usuario_id' => $usuarioEmpresa->id]);
        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresa->id,
            'estado' => Oferta::ESTADO_PENDIENTE,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('administrativo.oferta.confirmarVisibilidad', $oferta->id));

        $response->assertRedirect(route('administrativo.ofertas.index'));
        $response->assertSessionHas('success', 'La oferta fue marcada como visible.');

        $this->assertDatabaseHas('oferta', [
            'id' => $oferta->id,
            'estado' => Oferta::ESTADO_ACTIVA,
        ]);
    }

    /** @test */
    public function un_administrativo_no_puede_poner_visible_una_oferta_eliminada()
    {
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();
        $user = Usuario::factory()->create(['tipo_id' => $tipoAdministrativo->id ]);
        $administrativo = Administrativo::factory()->create(['usuario_id' => $user->id ]);

        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuarioEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id]);
        $empresa = Empresa::factory()->create(['usuario_id' => $usuarioEmpresa->id]);
        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresa->id,
            'estado' => Oferta::ESTADO_ELIMINADA,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('administrativo.oferta.confirmarVisibilidad', $oferta->id));

        // Assert
        $response->assertStatus(403);

        $this->assertDatabaseHas('oferta', [
            'id' => $oferta->id,
            'estado' => Oferta::ESTADO_ELIMINADA, // No se modificó
        ]);
    }
}
