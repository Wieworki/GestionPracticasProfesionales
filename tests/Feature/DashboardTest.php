<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Models\TipoUsuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_authenticated_estudiante_can_visit_the_dashboard()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();

        $this->actingAs($user = Usuario::factory()->create([
            'tipo_id' => $tipoEstudiante->id
        ]));

        $this->get('/dashboard')->assertRedirect('/estudiante/dashboard');
    }

    public function test_authenticated_empresa_can_visit_the_dashboard()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();

        $this->actingAs($user = Usuario::factory()->create([
            'tipo_id' => $tipoEmpresa->id
        ]));

        $this->get('/dashboard')->assertRedirect('/empresa/dashboard');
    }

    public function test_authenticated_administrativo_can_visit_the_dashboard()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();
        $tipoAdministrativo = TipoUsuario::factory()->isAdministrativo()->create();

        $this->actingAs($user = Usuario::factory()->create([
            'tipo_id' => $tipoAdministrativo->id
        ]));

        $this->get('/dashboard')->assertRedirect('/administrativo/dashboard');
    }
}
