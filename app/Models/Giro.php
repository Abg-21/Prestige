<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giro extends Model
{
    use HasFactory;

    protected $primaryKey = 'idGiros';
    protected $fillable = ['Nombre', 'Descripcion'];

    public function puestos()
    {
        return $this->hasMany(Puesto::class, 'id_GiroPuestoFK', 'idGiros');
    }
}

