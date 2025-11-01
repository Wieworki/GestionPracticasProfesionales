<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Postulacion extends Model
{
    use HasFactory, SoftDeletes;

    public const ESTADO_ACTIVA = 'Activa';
    public const ESTADO_SELECCIONADA = 'Seleccionada';
    public const ESTADO_CONFIRMADA = 'Confirmada';
    public const ESTADO_RECHAZADA = 'Rechazada';
    public const ESTADO_ANULADA = 'Anulada';

    protected $table = 'postulacion';

    protected $fillable = [
        'oferta_id',
        'estudiante_id',
        'fecha_creacion',
        'estado'
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
    ];

    /**
     * Relación: una postulacion pertenece a una oferta.
     */
    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'oferta_id', 'id');
    }

    /**
     * Relación: una postulacion pertenece a un estudiante.
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id');
    }

    public function canBeSelected()
    {
        return $this->estado == Postulacion::ESTADO_ACTIVA;
    }

    public function isSeleccionada()
    {
        return $this->estado == Postulacion::ESTADO_SELECCIONADA;
    }

    public function isConfirmada()
    {
        return $this->estado == Postulacion::ESTADO_CONFIRMADA;
    }
}
