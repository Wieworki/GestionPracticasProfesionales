<?php

namespace Tests\Unit\Repositories;

use App\Models\Empresa;
use App\Models\TipoUsuario;
use App\Models\Usuario;
use App\Repositories\EmpresaRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmpresaRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected EmpresaRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EmpresaRepository();
    }

    /** @test */
    public function puede_crear_una_empresa()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $usuario = Usuario::factory()->create(['tipo_id' => $tipoEmpresa->id]);

        $empresa = $this->repository->create([
            'usuario_id' => $usuario->id,
            'cuit' => '20123456789',
            'sitio_web' => 'https://empresa.com',
            'descripcion' => 'Una empresa de prueba',
        ]);

        $this->assertDatabaseHas('empresa', [
            'usuario_id' => $usuario->id,
            'cuit' => '20123456789',
        ]);

        $this->assertInstanceOf(Empresa::class, $empresa);
    }

    /** @test */
    public function get_all_devuelve_todas_las_empresas_con_paginacion()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        Usuario::factory()
            ->count(5)
            ->create(['tipo_id' => $tipoEmpresa->id])
            ->each(fn ($usuario) => Empresa::factory()->create(['usuario_id' => $usuario->id]));

        $result = $this->repository->getAll();

        $this->assertEquals(5, $result->total());
        $this->assertEquals(10, $result->perPage());
        $this->assertCount(5, $result->items());
    }

    /** @test */
    public function get_habilitadas_devuelve_solo_empresas_con_usuario_habilitado()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();

        $usuarioHabilitado = Usuario::factory()->habilitado()->create(['tipo_id' => $tipoEmpresa->id]);
        Empresa::factory()->create(['usuario_id' => $usuarioHabilitado->id]);

        $usuarioInhabilitado = Usuario::factory()->deshabilitado()->create(['tipo_id' => $tipoEmpresa->id]);
        Empresa::factory()->create(['usuario_id' => $usuarioInhabilitado->id]);

        $result = $this->repository->getHabilitadas();

        $this->assertEquals(1, $result->total());
        $empresa = $result->items()[0];
        $this->assertEquals($usuarioHabilitado->nombre, $empresa->nombre);
        $this->assertTrue($empresa->habilitado == 1);
    }

    /** @test */
    public function get_all_filtra_por_termino_de_busqueda_en_nombre_email_o_cuit()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();

        $usuarioMatch = Usuario::factory()->create([
            'tipo_id' => $tipoEmpresa->id,
            'nombre' => 'Empresa Buscada',
            'email' => 'buscada@example.com',
        ]);
        Empresa::factory()->create([
            'usuario_id' => $usuarioMatch->id,
            'cuit' => '999888777',
        ]);

        $usuarioNoMatch = Usuario::factory()->create([
            'tipo_id' => $tipoEmpresa->id,
            'nombre' => 'Otra Empresa',
            'email' => 'otra@example.com',
        ]);
        Empresa::factory()->create([
            'usuario_id' => $usuarioNoMatch->id,
            'cuit' => '123123123',
        ]);

        // Busca por nombre
        $resultByName = $this->repository->getAll('Buscada');
        $this->assertEquals(1, $resultByName->total());
        $this->assertEquals('Empresa Buscada', $resultByName->items()[0]->nombre);

        // Busca por email
        $resultByEmail = $this->repository->getAll('buscada@example.com');
        $this->assertEquals(1, $resultByEmail->total());

        // Busca por cuit
        $resultByCuit = $this->repository->getAll('999888777');
        $this->assertEquals(1, $resultByCuit->total());
    }

    /** @test */
    public function get_habilitadas_aplica_filtro_de_busqueda_correctamente()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();

        $usuarioMatch = Usuario::factory()->habilitado()->create([
            'tipo_id' => $tipoEmpresa->id,
            'nombre' => 'Empresa Visible',
        ]);
        Empresa::factory()->create(['usuario_id' => $usuarioMatch->id]);

        $usuarioNoMatch = Usuario::factory()->habilitado()->create([
            'tipo_id' => $tipoEmpresa->id,
            'nombre' => 'Otra Empresa',
        ]);
        Empresa::factory()->create(['usuario_id' => $usuarioNoMatch->id]);

        $result = $this->repository->getHabilitadas('Visible');
        $this->assertEquals(1, $result->total());
        $this->assertEquals('Empresa Visible', $result->items()[0]->nombre);
    }
}
