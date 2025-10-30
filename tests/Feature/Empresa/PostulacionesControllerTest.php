<?php

namespace Tests\Feature\Empresa;

use App\Models\Empresa;
use App\Models\Estudiante;
use App\Models\Oferta;
use App\Models\Postulacion;
use App\Models\TipoUsuario;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostulacionesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function una_empresa_puede_ver_las_postulaciones_de_su_oferta()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuarioEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id]);
        $empresa = Empresa::factory()->create(['usuario_id' => $usuarioEmpresa->id]);

        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresa->id,
            'estado' => Oferta::ESTADO_ACTIVA,
        ]);

        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $usuarioEstudiante = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $usuarioEstudiante->id]);

        $postulacion = Postulacion::factory()->create([
            'oferta_id' => $oferta->id,
            'estudiante_id' => $estudiante->id,
            'estado' => Postulacion::ESTADO_ACTIVA
        ]);

        $response = $this->actingAs($usuarioEmpresa)
            ->get(route('empresa.ofertas.postulantes', [
                'ofertaId' => $oferta->id,
            ]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page
                ->component('empresa/postulaciones/PostulacionesOferta')
                ->where('ofertaId', (string) $oferta->id)
                ->where('tituloOferta', $oferta->titulo)
                ->where('nombre', $empresa->nombre)
                ->has('postulaciones.data', 1)
                ->where('postulaciones.data.0.estudiante', $usuarioEstudiante->nombre)
        );
    }

    /** @test */
    public function una_empresa_no_puede_ver_postulaciones_de_otras_empresas()
    {
        // Arrange
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();

        // Empresa A
        $usuarioEmpresaA = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id]);
        $empresaA = Empresa::factory()->create(['usuario_id' => $usuarioEmpresaA->id]);

        // Empresa B (dueña de la oferta)
        $usuarioEmpresaB = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id]);
        $empresaB = Empresa::factory()->create(['usuario_id' => $usuarioEmpresaB->id]);

        $ofertaB = Oferta::factory()->create([
            'empresa_id' => $empresaB->id,
            'estado' => Oferta::ESTADO_ACTIVA,
        ]);

        // Act
        $response = $this->actingAs($usuarioEmpresaA)
            ->get(route('empresa.ofertas.postulantes', [
                'ofertaId' => $ofertaB->id,
            ]));

        // Assert
        $response->assertStatus(403);
    }

    /** @test */
    public function una_empresa_no_puede_ver_postulaciones_si_la_oferta_no_existe()
    {
        // Arrange
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuarioEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id]);
        $empresa = Empresa::factory()->create(['usuario_id' => $usuarioEmpresa->id]);

        // Act
        $response = $this->actingAs($usuarioEmpresa)
            ->get(route('empresa.ofertas.postulantes', [
                'ofertaId' => 9999, // oferta inexistente
            ]));

        // Assert
        $response->assertStatus(403);
    }
}
