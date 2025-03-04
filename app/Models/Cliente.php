<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes'; // Nombre de la tabla
    protected $primaryKey = 'idClientes'; // Clave primaria

    protected $fillable = [
        'Nombre',
        'Telefono',
        'Descripcion',
    ];

    public function puestos()
    {
        return $this->hasMany(Puesto::class, 'id_ClientePuestoFK', 'idClientes');
    }

}
