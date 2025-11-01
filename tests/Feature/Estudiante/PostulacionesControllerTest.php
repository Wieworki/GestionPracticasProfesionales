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

class PostulacionesControllerTest extends TestCase
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
    public function un_estudiante_puede_ver_solo_sus_postulaciones()
    {
        // Arrange
        // Creamos dos estudiantes con sus usuarios
        // Arrange
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $usuario1 = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante1 = Estudiante::factory()->create(['usuario_id' => $usuario1->id ]);
        $usuario2 = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante2 = Estudiante::factory()->create(['usuario_id' => $usuario2->id ]);


        // Creamos una empresa y dos ofertas
        $userEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $empresa = Empresa::factory()->create(['usuario_id' => $userEmpresa->id ]);
        $oferta1 = Oferta::factory()->create([
            'empresa_id' => $empresa->id,
            'estado' => Oferta::ESTADO_ACTIVA
        ]);
        $oferta2 = Oferta::factory()->create([
            'empresa_id' => $empresa->id,
            'estado' => Oferta::ESTADO_ACTIVA
        ]);

        // Creamos postulaciones: una del estudiante autenticado y otra de otro estudiante
        $postulacion1 = Postulacion::factory()->create([
            'oferta_id' => $oferta1->id,
            'estudiante_id' => $estudiante1->id,
            'estado' => Postulacion::ESTADO_ACTIVA,
        ]);

        $postulacion2 = Postulacion::factory()->create([
            'oferta_id' => $oferta2->id,
            'estudiante_id' => $estudiante2->id,
            'estado' => Postulacion::ESTADO_RECHAZADA,
        ]);

        // Act
        $response = $this
            ->actingAs($usuario1)
            ->get(route('estudiante.postulaciones.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page
                ->component('estudiante/postulaciones/Index')
                ->has('postulaciones.data', 1)
                ->where('postulaciones.data.0.id', $postulacion1->id)
                ->where('postulaciones.data.0.estado', $postulacion1->estado)
                ->where('postulaciones.data.0.oferta_id', $oferta1->id)
        );
    }

    /** @test */
    public function el_filtro_de_busqueda_funciona_por_titulo_de_oferta()
    {
        // Arrange
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $usuario = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $usuario->id ]);

        $userEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $empresa = Empresa::factory()->create(['usuario_id' => $userEmpresa->id ]);

        $oferta1 = Oferta::factory()->create([
            'empresa_id' => $empresa->id,
            'estado' => Oferta::ESTADO_ACTIVA,
            'titulo' => 'Desarrollador Backend'
        ]);
        $oferta2 = Oferta::factory()->create([
            'empresa_id' => $empresa->id,
            'estado' => Oferta::ESTADO_ACTIVA, 
            'titulo' => 'Diseñador UX/UI'
        ]);

        $postulacion1 = Postulacion::factory()->create([
            'oferta_id' => $oferta1->id,
            'estado' => Postulacion::ESTADO_ACTIVA,
            'estudiante_id' => $estudiante->id,
        ]);

        $postulacion2 = Postulacion::factory()->create([
            'oferta_id' => $oferta2->id,
            'estado' => Postulacion::ESTADO_ACTIVA,
            'estudiante_id' => $estudiante->id,
        ]);

        $response = $this
            ->actingAs($usuario)
            ->get(route('estudiante.postulaciones.index', ['search' => 'Backend']));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page
                ->component('estudiante/postulaciones/Index')
                ->has('postulaciones.data', 1)
                ->where('postulaciones.data.0.oferta_id', $oferta1->id)
        );
    }

    // Confirmacion postulacion

    /** @test */
    public function estudiante_puede_confirmar_su_postulacion()
    {
        // Arrange
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $usuario = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $usuario->id ]);

        $userEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $empresa = Empresa::factory()->create(['usuario_id' => $userEmpresa->id ]);

        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresa->id,
            'estado' => Oferta::ESTADO_ACTIVA, 
        ]);

        // Creamos la postulacion como "Seleccionada"
        $postulacion = Postulacion::factory()->create([
            'oferta_id' => $oferta->id,
            'estudiante_id' => $estudiante->id,
            'estado' => Postulacion::ESTADO_SELECCIONADA,
        ]);

        // Act
        $response = $this->actingAs($usuario)
            ->patch(route('estudiante.postulacion.confirmar'), [
                'postulacionId' => $postulacion->id,
            ]);

        // Assert
        $response->assertRedirect(route('estudiante.oferta.show', $oferta->id));
        $response->assertSessionHas('success', 'Postulacion exitosa.');

        $this->assertDatabaseHas('postulacion', [
            'id' => $postulacion->id,
            'estado' => Postulacion::ESTADO_CONFIRMADA,
        ]);
    }

    /** @test */
    public function estudiante_no_puede_confirmar_postulacion_de_otro_estudiante()
    {
        // Arrange
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $usuario1 = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante1 = Estudiante::factory()->create(['usuario_id' => $usuario1->id ]);
        $usuario2 = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante2 = Estudiante::factory()->create(['usuario_id' => $usuario2->id ]);

        $userEmpresa = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $empresa = Empresa::factory()->create(['usuario_id' => $userEmpresa->id ]);

        $oferta = Oferta::factory()->create([
            'empresa_id' => $empresa->id,
            'estado' => Oferta::ESTADO_ACTIVA, 
        ]);

        $postulacion = Postulacion::factory()->create([
            'oferta_id' => $oferta->id,
            'estudiante_id' => $estudiante2->id,
            'estado' => Postulacion::ESTADO_SELECCIONADA,
        ]);

        // Act
        $response = $this->actingAs($usuario1)
            ->patch(route('estudiante.postulacion.confirmar'), [
                'postulacionId' => $postulacion->id,
            ]);

        // Assert
        $response->assertForbidden();
        $this->assertDatabaseHas('postulacion', [
            'id' => $postulacion->id,
            'estado' => Postulacion::ESTADO_SELECCIONADA,
        ]);
    }

    /** @test */
    public function estudiante_no_puede_confirmar_una_postulacion_inexistente()
    {
        // Arrange
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $usuario = Usuario::factory()->create(['tipo_id' => $tipoEstudiante->id ]);
        $estudiante = Estudiante::factory()->create(['usuario_id' => $usuario->id ]);

        // Act
        $response = $this->actingAs($usuario)
            ->patch(route('estudiante.postulacion.confirmar'), [
                'postulacionId' => 999,
            ]);

        // Assert
        $response->assertForbidden();
    }
}
