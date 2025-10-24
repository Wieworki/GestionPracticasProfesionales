<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Se usa cuando el modelo tiene una foreign key
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    public function convenio()
    {
        if ($this->habilitado) {
            return "Firmado";
        } else {
            return "Sin firmar";
        }
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
