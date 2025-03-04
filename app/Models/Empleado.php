<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'idEmpleado';
    protected $fillable = [
        'Nombre', 'Apellido_P', 'Apellido_M', 'Telefono_M', 'Telefono_F',
        'Ciudad', 'Estado', 'Escolaridad', 'Correo', 'Experiencia',
        'Comentarios', 'id_PuestoEmpleadoFK'
    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'id_PuestoEmpleadoFK', 'idPuestos')
                    ->select(['idPuestos', 'Puesto']); // Selecciona solo el campo necesario
    }
    public function documentos()
    {
        return $this->hasMany(Documento::class, 'idEmpleadoFK', 'idEmpleado');
    }
}

