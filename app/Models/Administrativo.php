<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Usuario;

class Administrativo extends Model
{
    /** @use HasFactory<\Database\Factories\AdministrativoFactory> */
    use HasFactory;

    protected $table = 'administrativo';

    protected $fillable = [
        'usuario_id'
    ];

    public function usuario(): HasOne
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }
}
