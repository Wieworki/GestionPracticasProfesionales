<?php

namespace Tests\Feature\Controllers\Empresa;

use App\Models\Carrera;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\TipoUsuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfertaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function una_empresa_habilitada_puede_ver_el_formulario_de_creacion()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuario = Usuario::factory()->habilitado()->create(
            ['tipo_id' => $tipoEmpresa->id]
        );
        $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);

        $response = $this->actingAs($usuario)->get(route('empresa.ofertas.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('empresa/NuevaOferta')
                 ->where('empresa.nombre', $usuario->nombre)
        );
    }

    /** @test */
    public function una_empresa_habilitada_puede_crear_una_oferta()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuario = Usuario::factory()->habilitado()->create(
            ['tipo_id' => $tipoEmpresa->id]
        );
        $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);
        $carrera = Carrera::factory()->create(['nombre' => 'Todas']);

        $payload = [
            'titulo' => 'Desarrollador Fullstack',
            'descripcion' => 'React + Laravel',
            'fecha_cierre' => now()->addWeek()->format('Y-m-d'),
            'modalidad' => 'Remoto',
        ];

        $response = $this->actingAs($usuario)->post(route('empresa.ofertas.store'), $payload);

        $response->assertRedirect(route('empresa.dashboard', absolute: false));
        $this->assertDatabaseHas('oferta', [
            'titulo' => 'Desarrollador Fullstack',
            'empresa_id' => $empresa->id,
            'estado' => 'Pendiente',
        ]);

        $this->assertDatabaseHas('oferta_carrera', [
            'carrera_id' => $carrera->id,
        ]);
    }

    /** @test */
    public function una_empresa_no_habilitada_no_puede_crear_una_oferta()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuario = Usuario::factory()->deshabilitado()->create(
            ['tipo_id' => $tipoEmpresa->id]
        );
        $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);
        Carrera::factory()->create(['nombre' => 'Todas']);

        $payload = [
            'titulo' => 'DevOps Junior',
            'descripcion' => 'Manejo de Docker y CI/CD',
            'fecha_cierre' => now()->addDays(5)->format('Y-m-d'),
            'modalidad' => 'Híbrido',
        ];

        $response = $this->actingAs($usuario)->post(route('empresa.ofertas.store'), $payload);

        $response->assertRedirect(route('home', absolute: false));
        $this->assertDatabaseMissing('oferta', ['titulo' => 'DevOps Junior']);
    }

    /** @test */
    public function un_usuario_no_empresa_no_puede_crear_ofertas()
    {
        $usuario = Usuario::factory()->habilitado()->create();

        $payload = [
            'titulo' => 'Backend Developer',
            'descripcion' => 'API REST con Laravel',
            'fecha_cierre' => now()->addDays(7)->format('Y-m-d'),
            'modalidad' => 'Remoto',
        ];

        $response = $this->actingAs($usuario)->post(route('empresa.ofertas.store'), $payload);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('oferta', ['titulo' => 'Backend Developer']);
    }
}
