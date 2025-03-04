<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    // Especifica los campos asignables
    protected $fillable = ['empleado_id', 'contenido'];

    // Relación: cada contrato pertenece a un empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'idEmpleado');
    }
}
