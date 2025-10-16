<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UsuarioFactory> */
    use HasFactory, Notifiable;

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
        return $this->hasOne(TipoUsuario::class, 'id', 'tipo_id');
    }

    public function empresa(): HasOne
    {
        return $this->hasOne(Empresa::class, 'usuario_id');
    }

    public function estudiante(): HasOne
    {
        return $this->hasOne(Estudiante::class, 'usuario_id');
    }

    public function administrativo(): HasOne
    {
        return $this->hasOne(Administrativo::class, 'usuario_id');
    }

    public function isEmpresa()
    {
        $tipo = $this->tipoUsuario;
        return $tipo->nombre === 'empresa';
    }

    public function isEstudiante()
    {
        $tipo = $this->tipoUsuario;
        return $tipo->nombre === 'estudiante';
    }

    public function isAdministrativo()
    {
        $tipo = $this->tipoUsuario;
        return $tipo->nombre === 'administrativo';
    }

}
