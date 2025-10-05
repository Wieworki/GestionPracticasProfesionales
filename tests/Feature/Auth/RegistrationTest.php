<?php

namespace Tests\Feature\Auth;

use App\Models\TipoUsuario;
use App\Models\Usuario;
use App\Models\Estudiante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_estudiante_can_register()
    {
        $tipoEstudiante = TipoUsuario::factory()->isEstudiante()->create();

        $response = $this->post('/register/estudiante', [
            'nombre' => 'Test User',
            'apellido' => 'Apellido',
            'dni' => '12345678',
            'email' => 'juan@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('usuario', [
            'email' => 'juan@example.com',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_estudiante_dni_must_be_unique()
    {
        Estudiante::factory()->create([
            'dni' => '12345678',
        ]);

        $response = $this->post('/register/estudiante', [
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'dni' => '12345678',
            'email' => 'juan2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('dni');
    }

    public function test_estudiante_registration_requires_valid_data()
    {
        $response = $this->post('/register/estudiante', []); // Empty form

        $response->assertSessionHasErrors([
            'nombre',
            'apellido',
            'dni',
            'email',
            'password',
        ]);
    }

    public function test_new_empresa_can_register()
    {
        $tipoEmpresa = TipoUsuario::factory()->isEmpresa()->create();

        $response = $this->post('/register/empresa', [
            'nombre' => 'Test User',
            'email' => 'test@example.com',
            'cuit' => 'cuit',
            'descripcion' => 'descripcion',
            'sitioWeb' => 'empresa.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
