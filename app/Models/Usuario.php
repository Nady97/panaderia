<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\AuditableChanges;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, CanResetPassword, AuditableChanges;

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
        'descripcion',
        'rol_id',
        'last_login_at',
        'last_logout_at',
        'sexo',
        'imagen',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_logout_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $auditExclude = [
        'last_login_at',
        'last_logout_at',
    ];
    // Relación con el modelo Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id');
    }

    public function bitacorasAcceso()
    {
        return $this->hasMany(BitacoraAcceso::class, 'usuario_codigo', 'codigo');
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
      public function permisosDirectos()
    {
        return $this->belongsToMany(
            \App\Models\Permiso::class, 
            'usuario_permiso', 
            'usuario_codigo', 
            'permiso_id',
            'codigo',
            'id'
        )->withTimestamps();
    }

    /**
     * Verifica si el usuario tiene un permiso especifico por slug
     */
       public function hasPermission(string $permissionSlug): bool
    {
        if (!$this->rol) {
            return false;
        }

        // Primero verificar permisos del rol
        $tienePorRol = $this->rol->permisos()->where('slug', $permissionSlug)->exists();
        
        if ($tienePorRol) {
            return true;
        }

        // Luego verificar permisos directos del usuario
        return $this->permisosDirectos()->where('slug', $permissionSlug)->exists();
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

    /**
 * Verifica si el usuario tiene una sesión activa
 */
    public function isCurrentlyActive(): bool
    {
        if (!$this->last_login_at) return false;
        
        $timeout = config('session.lifetime') / 60; // en horas
        
        return $this->last_login_at->gt($this->last_logout_at ?? now()->subYears(1)) 
            && $this->last_login_at->diffInHours(now()) < $timeout;
    }
}
