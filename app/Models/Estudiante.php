<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Estudiante extends Model
{
    /** @use HasFactory<\Database\Factories\EstudianteFactory> */
    use HasFactory;

    protected $table = 'estudiante';

    protected $fillable = [
        'usuario_id',
        'dni'
    ];

    public function usuario(): HasOne
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }

    protected function nombre(): Attribute
    {
        return Attribute::get(fn() => $this->usuario?->nombre);
    }

    protected function email(): Attribute
    {
        return Attribute::get(fn() => $this->usuario?->email);
    }

    protected function habilitado(): Attribute
    {
        return Attribute::get(fn() => $this->usuario?->habilitado);
    }
}
