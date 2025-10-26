<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giro extends Model
{
    use HasFactory;

    protected $table = 'giros';
    protected $primaryKey = 'idGiros';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = ['Nombre', 'Descripcion'];

    public function puestos()
    {
        return $this->hasMany(Puesto::class, 'id_GiroPuestoFK', 'idGiros');
    }
}

