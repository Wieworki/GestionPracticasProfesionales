<?php

namespace Tests\Unit\Services;

use App\Models\Carrera;
use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\Usuario;
use App\Services\OfertaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class OfertaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OfertaService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OfertaService();
    }

    /** @test */
    public function crea_una_oferta_y_la_asocia_con_la_carrera_todas()
    {
        $usuario = Usuario::factory()->habilitado()->create();
        $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);
        $carrera = Carrera::factory()->create(['nombre' => 'Todas']);

        $data = [
            'titulo' => 'Desarrollador Backend',
            'descripcion' => 'Buscamos dev con experiencia en Laravel.',
            'fecha_cierre' => now()->addDays(10),
            'modalidad' => 'Remoto',
        ];

        $oferta = $this->service->crearOferta($empresa, $data, 'Todas');

        $this->assertDatabaseHas('oferta', [
            'titulo' => 'Desarrollador Backend',
            'empresa_id' => $empresa->id,
            'estado' => 'Pendiente',
        ]);

        $this->assertDatabaseHas('oferta_carrera', [
            'oferta_id' => $oferta->id,
            'carrera_id' => $carrera->id,
        ]);
    }

    /** @test */
    public function lanza_excepcion_si_la_empresa_no_esta_habilitada()
    {
        $usuario = Usuario::factory()->deshabilitado()->create();
        $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('La empresa no está habilitada para crear ofertas.');

        $data = [
            'titulo' => 'QA Tester',
            'descripcion' => 'Control de calidad de software.',
            'fecha_cierre' => now()->addDays(5),
            'modalidad' => 'Presencial',
        ];

        $this->service->crearOferta($empresa, $data, 'Todas');
    }

    /** @test */
    public function no_falla_si_no_existe_la_carrera_asociada()
    {
        $usuario = Usuario::factory()->habilitado()->create();
        $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);

        $data = [
            'titulo' => 'DevOps Engineer',
            'descripcion' => 'Infraestructura y CI/CD.',
            'fecha_cierre' => now()->addDays(15),
            'modalidad' => 'Híbrido',
        ];

        $oferta = $this->service->crearOferta($empresa, $data, 'CarreraInexistente');

        $this->assertInstanceOf(Oferta::class, $oferta);
        $this->assertDatabaseHas('oferta', ['titulo' => 'DevOps Engineer']);
        $this->assertDatabaseMissing('oferta_carrera', ['oferta_id' => $oferta->id]);
    }
}
