<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Postulacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'postulacion';

    protected $fillable = [
        'oferta_id',
        'estudiante_id',
        'fecha_creacion',
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
}
