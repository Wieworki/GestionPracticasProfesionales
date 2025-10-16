<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_autenticado_puede_ver_el_formulario_de_cambio_de_contraseña()
    {
        $user = Usuario::factory()->create();

        $response = $this->actingAs($user)->get(route('password.cambiar'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('auth/ChangePassword')
        );
    }

    /** @test */
    public function un_usuario_no_autenticado_no_puede_ver_el_formulario_de_cambio_de_contraseña()
    {
        $response = $this->get(route('password.cambiar'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function un_usuario_puede_cambiar_su_contraseña_con_datos_validos()
    {
        $user = Usuario::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->post(route('password.guardar'), [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', 'Contraseña actualizada correctamente.');

        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }

    /** @test */
    public function falla_si_la_contraseña_actual_no_coincide()
    {
        $user = Usuario::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->post(route('password.guardar'), [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    /** @test */
    public function falla_si_las_nuevas_contraseñas_no_coinciden()
    {
        $user = Usuario::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->post(route('password.guardar'), [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('new_password');
    }
}
