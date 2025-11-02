<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AdministrativoController;
use App\Http\Controllers\Dashboard\DashboardRedirectController;
use App\Http\Controllers\Dashboard\EmpresaDashboardController;
use App\Http\Controllers\Dashboard\AdministrativoDashboardController;
use App\Http\Controllers\Dashboard\EstudianteDashboardController;
use App\Http\Controllers\Empresa\PerfilController as EmpresaPerfil;
use App\Http\Controllers\Empresa\OfertaController as EmpresaOferta;
use App\Http\Controllers\Empresa\PostulacionController as EmpresaPostulaciones;
use App\Http\Controllers\Estudiante\PerfilController as EstudiantePerfil;
use App\Http\Controllers\Estudiante\EmpresaController as EmpresaEstudianteController;
use App\Http\Controllers\Estudiante\OfertaController as OfertaEstudianteController;
use App\Http\Controllers\Estudiante\PostulacionController as PostulacionEstudianteController;
use App\Http\Controllers\Administrativo\PerfilController as AdministrativoPerfil;
use App\Http\Controllers\Administrativo\EmpresaController as EmpresaAdministrativoController;
use App\Http\Controllers\Administrativo\EstudianteController as EstudianteAdministrativoController;
use App\Http\Controllers\Administrativo\AdministracionController as AdministracionAdministrativoController;
use App\Http\Controllers\Administrativo\OfertaController as OfertaAdministrativoController;
use App\Http\Controllers\Auth\PasswordController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/password/cambiar', [PasswordController::class, 'edit'])->name('password.cambiar');
    Route::post('/password/guardar', [PasswordController::class, 'update'])->name('password.guardar');
    Route::get('/dashboard', DashboardRedirectController::class)->name('dashboard');    // This calls the functin "__invoke" of the class

    Route::middleware('checkUserType:empresa')->group(function () {
        Route::get('/empresa/perfil', [EmpresaPerfil::class, 'index'])->name('empresa.perfil');
        Route::get('/empresa/dashboard', [EmpresaDashboardController::class, 'index'])->name('empresa.dashboard');
        Route::get('/empresa/perfil/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
        Route::patch('/empresa/perfil', [EmpresaController::class, 'update'])->name('empresa.update');

        Route::get('/empresa/ofertas/nueva', [EmpresaOferta::class, 'create'])
            ->name('empresa.ofertas.create');

        Route::post('/empresa/ofertas', [EmpresaOferta::class, 'store'])
            ->name('empresa.ofertas.store');

        Route::get('/empresa/ofertas/index', [EmpresaOferta::class, 'index'])
            ->name('empresa.ofertas.index');

        Route::get('/empresa/ofertas/show/{id}', [EmpresaOferta::class, 'show'])
            ->name('empresa.ofertas.show');

        Route::get('/empresa/ofertas/edit/{id}', [EmpresaOferta::class, 'edit'])
            ->name('empresa.ofertas.edit');

        Route::patch('/empresa/ofertas/edit/{id}', [EmpresaOferta::class, 'update'])
            ->name('empresa.ofertas.update');

        Route::patch('/empresa/ofertas/eliminar/{id}', [EmpresaOferta::class, 'eliminar'])
            ->name('empresa.ofertas.eliminar');

        Route::get('/empresa/ofertas/postulantes', [EmpresaPostulaciones::class, 'index'])
            ->name('empresa.ofertas.postulantes');
        Route::get('/empresa/ofertas/postulantes/show', [EmpresaPostulaciones::class, 'show'])
            ->name('empresa.postulacion.show');
        Route::patch('/empresa/ofertas/postulantes/seleccionar', [EmpresaPostulaciones::class, 'seleccionarPostulante'])
            ->name('empresa.postulacion.seleccionarPostulante');
        
    });

    Route::middleware('checkUserType:estudiante')->group(function () {
        Route::get('/estudiante/perfil', [EstudiantePerfil::class, 'index'])->name('estudiante.perfil');
        Route::get('/estudiante/dashboard', [EstudianteDashboardController::class, 'index'])->name('estudiante.dashboard');
        Route::get('/estudiante/perfil/edit', [EstudianteController::class, 'edit'])->name('estudiante.edit');
        Route::patch('/estudiante/perfil', [EstudianteController::class, 'update'])->name('estudiante.update');

        Route::get('/estudiante/empresas', [EmpresaController::class, 'indexEstudiante'])
            ->name('estudiante.empresas.index');
        Route::get('/estudiante/empresas/show/{id}', [EmpresaEstudianteController::class, 'show'])
            ->name('estudiante.empresas.show');

        Route::get('/estudiante/ofertas/index', [OfertaEstudianteController::class, 'index'])
            ->name('estudiante.ofertas.index');
        Route::get('/estudiante/ofertas/show/{id}', [OfertaEstudianteController::class, 'show'])
            ->name('estudiante.oferta.show');
        Route::patch('/estudiante/ofertas/confirmar/{id}', [OfertaEstudianteController::class, 'postular'])
            ->name('estudiante.oferta.postular');

        Route::get('/estudiante/postulaciones/index', [PostulacionEstudianteController::class, 'index'])
            ->name('estudiante.postulaciones.index');
        Route::patch('/estudiante/postulacion/confirmar', [PostulacionEstudianteController::class, 'confirmar'])
            ->name('estudiante.postulacion.confirmar');
        Route::patch('/estudiante/postulacion/anular', [PostulacionEstudianteController::class, 'anular'])
            ->name('estudiante.postulacion.anular');
    });

    Route::middleware('checkUserType:administrativo')->group(function () {
        Route::get('/administrativo/perfil', [AdministrativoPerfil::class, 'index'])->name('administrativo.perfil');
        Route::get('/administrativo/dashboard', [AdministrativoDashboardController::class, 'index'])->name('administrativo.dashboard');
        Route::get('/administrativo/perfil/edit', [AdministrativoController::class, 'edit'])->name('administrativo.edit');
        Route::patch('/administrativo/perfil', [AdministrativoController::class, 'update'])->name('administrativo.update');

        Route::get('/administrativo/empresas', [EmpresaAdministrativoController::class, 'index'])
            ->name('administrativo.empresas.index');
        Route::get('/administrativo/empresas/show/{id}', [EmpresaAdministrativoController::class, 'show'])
            ->name('administrativo.empresas.show');
        Route::patch('/administrativo/empresas/convenio', [EmpresaAdministrativoController::class, 'confirmarConvenio'])
            ->name('administrativo.empresas.convenio');
        Route::get('/administrativo/empresas/create', [EmpresaAdministrativoController::class, 'create'])
            ->name('administrativo.empresas.create');
        Route::post('/administrativo/empresas/store', [EmpresaAdministrativoController::class, 'store'])
            ->name('administrativo.empresas.store');

        Route::get('/administrativo/ofertas/index', [OfertaAdministrativoController::class, 'index'])
            ->name('administrativo.ofertas.index');
        Route::get('/administrativo/ofertas/show/{id}', [OfertaAdministrativoController::class, 'show'])
            ->name('administrativo.oferta.show');
        Route::patch('/administrativo/ofertas/confirmar/{id}', [OfertaAdministrativoController::class, 'confirmarVisibilidad'])
            ->name('administrativo.oferta.confirmarVisibilidad');

        Route::get('/administrativo/estudiantes', [EstudianteAdministrativoController::class, 'index'])
            ->name('administrativo.estudiantes.index');
        Route::get('/administrativo/estudiantes/show/{id}', [EstudianteAdministrativoController::class, 'show'])
            ->name('administrativo.estudiantes.show');
        Route::get('/administrativo/estudiantes/edit/{id}', [EstudianteAdministrativoController::class, 'edit'])
            ->name('administrativo.estudiantes.edit');
        Route::patch('/administrativo/estudiantes/edit/{id}', [EstudianteAdministrativoController::class, 'update'])
            ->name('administrativo.estudiantes.update');
        Route::get('/administrativo/estudiantes/create', [EstudianteAdministrativoController::class, 'create'])
            ->name('administrativo.estudiantes.create');
        Route::post('/administrativo/estudiantes/store', [EstudianteAdministrativoController::class, 'store'])
            ->name('administrativo.estudiantes.store');

        Route::get('/administrativo/administracion', [AdministracionAdministrativoController::class, 'index'])
            ->name('administrativo.administracion.index');
        Route::get('/administrativo/administracion/crearUsuario', [AdministrativoController::class, 'create'])
            ->name('administrativo.administracion.crearUsuario');
        Route::post('/administrativo/administracion/storeUsuario', [AdministrativoController::class, 'store'])
            ->name('administrativo.administracion.storeUsuario');
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
