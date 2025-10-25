<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    use HasFactory;

    public const ESTADO_FINALIZADA = "Finalizada";
    public const ESTADO_ELIMINADA = "Eliminada";
    public const ESTADO_PENDIENTE = "Pendiente";
    public const ESTADO_ACTIVA = "Activa";

    protected $table = 'oferta';

    protected $fillable = [
        'empresa_id',
        'titulo',
        'descripcion',
        'fecha_creacion',
        'fecha_cierre',
        'estado',
        'modalidad',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_cierre' => 'datetime',
    ];

    /**
     * Relación: una oferta pertenece a una empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }

    public function canBeEdited()
    {
        return (!$this->isEliminada() && !$this->isFinalizada());
    }

    public function canBeDeleted()
    {
        return (!$this->isEliminada() && !$this->isFinalizada());
    }

    public function isPendiente()
    {
        return $this->estado === Oferta::ESTADO_PENDIENTE;
    }

    public function isActiva()
    {
        return $this->estado === Oferta::ESTADO_ACTIVA;
    }

    public function isEliminada()
    {
        return $this->estado === Oferta::ESTADO_ELIMINADA;
    }

    public function isFinalizada()
    {
        return $this->estado === Oferta::ESTADO_FINALIZADA;
    }
}
