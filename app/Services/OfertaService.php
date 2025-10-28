<?php

namespace App\Services;

use App\Models\Oferta;
use App\Models\Carrera;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OfertaService
{
    /**
     * Crea una nueva oferta para una empresa.
     *
     * @param Empresa $empresa
     * @param array $data
     * @return Oferta
     * @throws \Throwable
     */
    public function crearOferta(Empresa $empresa, array $data, string $nombreCarrera): Oferta
    {
        if (!$empresa->usuario->habilitado) {
            throw ValidationException::withMessages([
                'empresa' => 'La empresa no está habilitada para crear ofertas.',
            ]);
        }

        return DB::transaction(function () use ($empresa, $data, $nombreCarrera) {
            $oferta = Oferta::create([
                'empresa_id' => $empresa->id,
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'fecha_creacion' => now(),
                'fecha_cierre' => $data['fecha_cierre'],
                'estado' => OFERTA::ESTADO_PENDIENTE,
                'modalidad' => $data['modalidad'],
            ]);

            $carrera = Carrera::where('nombre', $nombreCarrera)->first();

            if ($carrera) {
                DB::table('oferta_carrera')->insert([
                    'oferta_id' => $oferta->id,
                    'carrera_id' => $carrera->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                Log::warning('No se encontró la carrera ' . $carrera . ' al crear la oferta.');
            }

            return $oferta;
        });
    }

    public function getVisibleOfertaForEstudiante(int $ofertaId): Oferta
    {
        $oferta = Oferta::with('empresa')->find($ofertaId);

        if (!$oferta) {
            throw new ModelNotFoundException("La oferta no existe.");
        }

        if (!$oferta->isVisibileForEstudiante()) {
            abort(403, 'No tiene permiso para acceder a esta oferta.');
        }

        if (!$oferta->empresa->habilitado) {
            abort(403, 'La empresa no está habilitada.');
        }

        return $oferta;
    }

    public function canEstudiantePostularse(Oferta $oferta, int $estudianteId): bool
    {
        return !$oferta->postulaciones()
            ->where('estudiante_id', $estudianteId)
            ->exists()
            && $oferta->isActiva();
    }
}
