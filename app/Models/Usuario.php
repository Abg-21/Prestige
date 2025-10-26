<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    
    protected $fillable = [
        'nombre',
        'correo',
        'contraseña',
        'eliminado_en'
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    protected $dates = [
        'eliminado_en'
    ];

    // Usar eliminación lógica con campo personalizado
    public function getDeletedAtColumn()
    {
        return 'eliminado_en';
    }

    // Scope para usuarios activos (no eliminados)
    public function scopeActivos($query)
    {
        return $query->whereNull('eliminado_en');
    }

    // Laravel espera 'password' pero tu BD tiene 'contraseña'
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    // Laravel espera 'email' pero tu BD tiene 'correo'
    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    // Especificar el campo que se usa como username/email para la autenticación
    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    // Obtener el identificador único para la autenticación
    public function getAuthIdentifier()
    {
        return $this->correo;
    }

    // Relación con roles (muchos a muchos)
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_roles', 'usuario_id', 'rol_id')
                    ->withTimestamps();
    }

    // Verificar si el usuario tiene un rol específico
    public function tieneRol($nombreRol)
    {
        return $this->roles()->where('nombre', $nombreRol)->exists();
    }

    // Verificar si el usuario está activo (no eliminado)
    public function estaActivo()
    {
        return is_null($this->eliminado_en);
    }
}

