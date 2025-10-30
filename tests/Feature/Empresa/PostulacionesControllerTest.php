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
use Inertia\Testing\AssertableInertia as Assert;

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

    // Test para el show postulacion

    /** @test */
    public function una_empresa_puede_ver_el_detalle_de_una_postulacion_de_su_oferta()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuarioEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id]);
        $empresa = Empresa::factory()->create(['usuario_id' => $usuarioEmpresa->id]);

        // Crear estudiante + usuario asociado
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $usuarioEstudiante = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $usuarioEstudiante->id]);

        // Crear oferta de la empresa
        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresa->id,
            'estado' => Oferta::ESTADO_ACTIVA
        ]);

        // Crear postulacion asociada
        $postulacion = Postulacion::factory()->create([
            'oferta_id' => $oferta->id,
            'estudiante_id' => $estudiante->id,
            'estado' => Postulacion::ESTADO_ACTIVA,
        ]);

        // Ejecutar la request como usuario empresa
        $response = $this
            ->actingAs($usuarioEmpresa)
            ->get(route('empresa.postulacion.show', ['postulacionId' => $postulacion->id]));

        // Assertions
        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('empresa/postulaciones/ShowPostulacion')
                ->where('nombre', $empresa->nombre)
                ->where('postulacion.id', $postulacion->id)
                ->where('postulacion.titulo', $postulacion->oferta->titulo)
                ->where('postulacion.estado', $postulacion->estado)
                ->where('postulacion.estudiante', $postulacion->estudiante->nombre)
                ->where('postulacion.canBeSelected', true)
        );
    }

    /** @test */
    public function retorna_403_si_la_postulacion_no_existe()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuarioEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id]);
        $empresa = Empresa::factory()->create(['usuario_id' => $usuarioEmpresa->id]);

        $response = $this
            ->actingAs($usuarioEmpresa)
            ->get(route('empresa.postulacion.show', ['postulacionId' => 999]));

        $response->assertStatus(403);
    }

    /** @test */
    public function retorna_403_si_la_postulacion_pertenece_a_otra_empresa()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuarioEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id]);
        $empresa = Empresa::factory()->create(['usuario_id' => $usuarioEmpresa->id]);

        // Otra empresa y oferta
        $otroUsuarioEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id]);
        $otraEmpresa = Empresa::factory()->create(['usuario_id' => $otroUsuarioEmpresa->id]);

        $ofertaAjena = Oferta::factory()->create([
            'empresa_id' => $otraEmpresa->id,
            'estado' => Oferta::ESTADO_ACTIVA
        ]);

        // Postulación de la otra empresa
        $postulacion = Postulacion::factory()->create(['oferta_id' => $ofertaAjena->id]);

        // Intento de acceder
        $response = $this
            ->actingAs($usuarioEmpresa)
            ->get(route('empresa.postulacion.show', ['postulacionId' => $postulacion->id]));

        $response->assertStatus(403);
    }
}
