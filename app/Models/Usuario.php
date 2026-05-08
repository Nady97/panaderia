<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName()
    {
        return 'codigo';
    }
    protected $fillable = [
        'codigo',
        'nombre',
        'email',
        'password',
        'telefono',
        'direccion',
        'rol_id',
        'last_login_at',
        'sexo',
        'imagen',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    // Relación con el modelo Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id');
    }
    // Relación con el modelo Venta (si es necesario)
    public function getRolNombreAtribute()
    {
        return $this->rol ? $this->rol->nombre : 'Administrador';
    }

    /**
     * Verifica si el usuario tiene un rol específico o lista de roles (por slug)
     */
    public function hasRole($roles)
    {
        if (!$this->rol) {
            return false;
        }

        if (is_array($roles)) {
            return in_array($this->rol->slug, $roles);
        }

        return $this->rol->slug === $roles;
    }

    /**
     * Helper para saber si es administrador
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
    // Métodos necesarios para la autenticación
    public function getAuthIdentifierName()
    {
        return 'codigo';
    }
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
}
