<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'permisos'
    ];

    protected $casts = [
        'permisos' => 'array'
    ];

    /**
     * Relación muchos a muchos con usuarios
     */
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_roles', 'rol_id', 'usuario_id');
    }

    /**
     * Obtener los módulos disponibles en el sistema
     */
    public static function getModulosDisponibles()
    {
        return [
            'candidatos' => 'Candidatos',
            'empleados' => 'Empleados',
            'puestos' => 'Puestos',
            'giros' => 'Giros',
            'clientes' => 'Clientes',
            'documentos' => 'Documentos',
            'usuarios' => 'Usuarios',
            'roles' => 'Roles',
        ];
    }

    /**
     * Obtener las acciones disponibles para cada módulo
     */
    public static function getAccionesDisponibles()
    {
        return [
            'ver' => 'Ver',
            'crear' => 'Crear',
            'editar' => 'Editar',
            'eliminar' => 'Eliminar'
        ];
    }

    /**
     * Verificar si el rol tiene permiso para una acción específica en un módulo
     */
    public function tienePermiso($modulo, $accion = 'ver')
    {
        $permisos = $this->permisos ?? [];
        
        return isset($permisos[$modulo][$accion]) && $permisos[$modulo][$accion] == 1;
    }

    /**
     * Asignar permiso específico
     */
    public function asignarPermiso($modulo, $accion, $valor = true)
    {
        $permisos = $this->permisos ?? [];
        $permisos[$modulo][$accion] = $valor;
        $this->permisos = $permisos;
        $this->save();
    }

    /**
     * Obtener todos los permisos formateados
     */
    public function getPermisosFormateados()
    {
        $permisos = $this->permisos ?? [];
        $modulos = self::getModulosDisponibles();
        $acciones = self::getAccionesDisponibles();
        
        $resultado = [];
        
        foreach ($modulos as $modulo => $nombreModulo) {
            $resultado[$modulo] = [
                'nombre' => $nombreModulo,
                'permisos' => []
            ];
            
            foreach ($acciones as $accion => $nombreAccion) {
                $resultado[$modulo]['permisos'][$accion] = $permisos[$modulo][$accion] ?? false;
            }
        }
        
        return $resultado;
    }
}