<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empresa extends Model
{
    /** @use HasFactory<\Database\Factories\EmpresaFactory> */
    use HasFactory;

    protected $table = 'empresa';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'usuario_id',
        'cuit',
        'sitio_web',
        'descripcion'
    ];

    public function usuario(): HasOne
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }
}
