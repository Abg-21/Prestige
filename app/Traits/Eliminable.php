<?php
// app/Traits/Eliminable.php
namespace App\Traits;

use App\Models\Eliminado;
use Illuminate\Database\Eloquent\Builder;

trait Eliminable
{
    /**
     * Indica si el modelo está activo (no eliminado)
     */
    public function getActivoAttribute()
    {
        return $this->eliminado_en === null;
    }

    /**
     * Relación con el registro de eliminación
     */
    public function eliminacion()
    {
        return $this->morphOne(Eliminado::class, 'eliminable');
    }

    /**
     * Scope para obtener solo modelos activos
     */
    public function scopeActivos(Builder $query)
    {
        return $query->whereNull('eliminado_en');
    }

    /**
     * Scope para obtener solo modelos eliminados
     */
    public function scopeEliminados(Builder $query)
    {
        return $query->whereNotNull('eliminado_en');
    }

    /**
     * Da de baja el modelo (eliminación lógica)
     */
    public function darDeBaja($motivo = null)
    {
        // Marcar como eliminado
        $this->eliminado_en = now();
        $this->save();
        
        // Registrar en tabla de eliminados
        Eliminado::create([
            'eliminable_type' => get_class($this),
            'eliminable_id' => $this->getKey(),
            'tipo' => class_basename($this),
            'motivo' => $motivo,
            'eliminado_en' => now(),
            'idUsuarioEliminadoFK' => auth()->check() ? auth()->id() : null
        ]);
    }

    /**
     * Restaura el modelo
     */
    public function restaurar()
    {
        // Si hay un registro de eliminación, actualizarlo
        if ($eliminacion = $this->eliminacion) {
            $eliminacion->update([
                'restaurado_en' => now(),
                'restaurado_por' => auth()->check() ? auth()->user()->nombre_usuario : null
            ]);
        }
        
        // Marcar como activo
        $this->eliminado_en = null;
        $this->save();
    }
}