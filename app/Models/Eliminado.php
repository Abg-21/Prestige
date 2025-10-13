<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eliminado extends Model
{
    protected $table = 'eliminados';
    protected $primaryKey = 'idEliminados';
    
    protected $fillable = [
        'eliminable_type',
        'eliminable_id',
        'tipo',
        'motivo',
        'eliminado_en',
        'restaurado_en',
        'restaurado_por',
        'idUsuarioEliminadoFK'
    ];
    
    protected $casts = [
        'eliminado_en' => 'datetime',
        'restaurado_en' => 'datetime',
    ];
    
    /**
     * Obtiene el modelo eliminado
     */
    public function eliminable()
    {
        return $this->morphTo();
    }
    
    /**
     * Relación con el usuario que eliminó
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuarioEliminadoFK', 'id');
    }
}
