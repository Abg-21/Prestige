<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    use HasFactory;

    protected $primaryKey = 'idPuestos'; // Clave primaria personalizada
    protected $fillable = [
        'Categoría', 'Puesto', 'Zona', 'Estado', 'Edad', 'Escolaridad', 'Experiencia', 'Conocimientos', 'Funciones',
        'Habilidades', 'P_Aux', 'S_Aux', 'T_Aux', 'id_GiroPuestoFK', 'id_ClientePuestoFK'
    ];
    public $incrementing = true;
    protected $keyType = 'int';

    public function giro()
    {
        return $this->belongsTo(Giro::class, 'id_GiroPuestoFK', 'idGiros');
    }
    // Relación con Clientes
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_ClientePuestoFK', 'idClientes');
    }
    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_PuestoEmpleadoFK', 'idPuestos');
    }

}
