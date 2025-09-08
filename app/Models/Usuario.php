<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Usuario extends Model
{
    /** @use HasFactory<\Database\Factories\UsuarioFactory> */
    use HasFactory;

    protected $table = 'usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'password',
        'tipo_id'
    ];

    public function tipoUsuario(): HasOne
    {
        return $this->hasOne(TipoUsuario::class, 'tipo_id');
    }

}
