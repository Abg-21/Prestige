<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';
    protected $primaryKey = 'idDocumento';

    protected $fillable = [
        'idEmpleadoFK',
        'TipoArchivo',
        'RutaArchivo',
        'FechaSubida',
        'FechaEdicion'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleadoFK', 'idEmpleado');
    }
}
