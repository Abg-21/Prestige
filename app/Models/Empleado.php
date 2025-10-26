<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'IdEmpleados';
    
    protected $fillable = [
        'Nombre',
        'Apellido_Paterno',
        'Apellido_Materno',
        'Edad',
        'Telefono',
        'Estado',
        'Ruta',
        'Escolaridad',
        'Correo',
        'Experiencia',
        'Fecha_Ingreso',
        'Fecha_Egreso',
        'Curp',
        'NSS',
        'RFC',
        'Codigo_Postal',
        'Folio',
        'Codigo',
        'No_Cuenta',
        'Tipo_Cuenta',
        'Sueldo',
        'IdPuestoEmpleadoFK',
        'Eliminado_en'
    ];

    protected $dates = [
        'Fecha_Ingreso',
        'Fecha_Egreso',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'Edad' => 'integer',
        'Fecha_Ingreso' => 'date',
        'Fecha_Egreso' => 'date',
        'Eliminado_en' => 'datetime',
        'Sueldo' => 'decimal:2',
    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'IdPuestoEmpleadoFK', 'idPuestos');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'IdEmpleadoDocumentoFK', 'IdEmpleados');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'empleado_id', 'IdEmpleados');
    }
}

