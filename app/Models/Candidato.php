<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    protected $table = 'candidatos';
    protected $primaryKey = 'idCandidatos';

    protected $fillable = [
        'Nombre',
        'Apellido_P',
        'Apellido_M',
        'Telefono_M',
        'Telefono_F',
        'Ciudad',
        'Estado',
        'Escolaridad',
        'Correo',
        'Experiencia',
        'Comentarios',
        'id_PuestoCandidatoFK',
    ];    

    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'id_PuestoCandidatoFK', 'idPuestos');
    }

}
