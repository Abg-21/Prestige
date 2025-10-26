<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Eliminable;

class Candidato extends Model
{
    use HasFactory, Eliminable;

    protected $table = 'candidatos';
    protected $primaryKey = 'IdCandidatos';

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
        'Fecha_Postulacion',
        'IdPuestoCandidatoFK',
        'Eliminado_en'
    ];    

    protected $dates = [
        'Fecha_Postulacion',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'Edad' => 'integer',
        'Fecha_Postulacion' => 'date',
    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'IdPuestoCandidatoFK', 'idPuestos');
    }

    public function eliminacion()
    {
        return $this->morphOne(Eliminado::class, 'eliminable');
    }

    public function scopeEliminados($query)
    {
        return $query->whereNotNull('Eliminado_en');
    }

    public function scopeActivos($query)
    {
        return $query->whereNull('Eliminado_en');
    }

    public function darDeBaja($motivo = null)
    {
        // Usamos eliminado_en en lugar de activo
        $this->Eliminado_en = now();
        $this->save();
        
        // Añadimos el campo tipo que es obligatorio
        Eliminado::create([
            'eliminable_type' => 'App\Models\Candidato',
            'eliminable_id' => $this->IdCandidatos,
            'tipo' => 'Candidato', // Campo requerido que faltaba
            'motivo' => $motivo,
            'eliminado_en' => now(),
            'idUsuarioEliminadoFK' => auth()->check() ? auth()->id() : null
        ]);
    }

    public function restaurar()
    {
        // Usamos eliminado_en en lugar de activo
        $this->Eliminado_en = null;
        $this->save();
        
        // Actualizar el registro en Eliminados
        if ($eliminacion = $this->eliminacion) {
            $eliminacion->update([
                'restaurado_en' => now(),
                'restaurado_por' => auth()->check() ? auth()->user()->nombre_usuario : null
            ]);
        }
    }
}
