<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    const generic = "Todas";

    protected $table = 'carrera';

    protected $fillable = [
        'nombre',
        'facultad_id',
    ];

    /**
     * Relación: una carrera pertenece a una facultad.
     */
    public function facultad()
    {
        return $this->belongsTo(Facultad::class, 'facultad_id', 'id');
    }
}
