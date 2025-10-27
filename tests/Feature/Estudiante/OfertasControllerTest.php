<?php

namespace Tests\Feature\Estudiante;

use App\Models\Usuario;
use App\Models\TipoUsuario;
use App\Models\Estudiante;
use App\Models\Empresa;
use App\Models\Oferta;
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
}
