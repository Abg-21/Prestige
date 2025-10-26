<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencia';

    protected $fillable = [
        'empleado_id',
        'año',
        'mes',
        'periodo',
        'dia_1', 'dia_2', 'dia_3', 'dia_4', 'dia_5', 'dia_6', 'dia_7', 'dia_8', 
        'dia_9', 'dia_10', 'dia_11', 'dia_12', 'dia_13', 'dia_14', 'dia_15', 'dia_16',
        'dia_17', 'dia_18', 'dia_19', 'dia_20', 'dia_21', 'dia_22', 'dia_23', 'dia_24',
        'dia_25', 'dia_26', 'dia_27', 'dia_28', 'dia_29', 'dia_30', 'dia_31',
        'bono',
        'prestamo',
        'fonacot',
        'estatus_finiquito',
        'observaciones'
    ];

    protected $casts = [
        'año' => 'integer',
        'mes' => 'integer',
        'bono' => 'decimal:2',
        'prestamo' => 'decimal:2',
        'fonacot' => 'decimal:2',
        'estatus_finiquito' => 'decimal:2',
    ];

    /**
     * Estados de asistencia disponibles
     */
    public const ESTADOS = [
        'presente' => ['nombre' => 'Presente', 'color' => '#28a745', 'icono' => '✓'],
        'falta' => ['nombre' => 'Falta', 'color' => '#dc3545', 'icono' => '✗'],
        'prima_dominical' => ['nombre' => 'Prima Dominical', 'color' => '#fd7e14', 'icono' => 'PD'],
        'bajas' => ['nombre' => 'Bajas', 'color' => '#e83e8c', 'icono' => 'B'],
        'alta' => ['nombre' => 'Alta', 'color' => '#ffc107', 'icono' => 'A'],
        'incapacidad' => ['nombre' => 'Incapacidad', 'color' => '#20c997', 'icono' => 'I'],
        'prestamo' => ['nombre' => 'Préstamo', 'color' => '#007bff', 'icono' => 'P'],
        'vacante' => ['nombre' => 'Vacante', 'color' => '#343a40', 'icono' => 'V'],
        'vacaciones' => ['nombre' => 'Vacaciones', 'color' => '#6f42c1', 'icono' => 'VAC']
    ];

    /**
     * Relación con Empleado
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'IdEmpleados');
    }

    /**
     * Obtener los días del periodo actual
     */
    public function getDiasDelPeriodo()
    {
        if ($this->periodo === 'primera_quincena') {
            return range(1, 15);
        } else {
            // Para segunda quincena, calcular los días según el mes
            $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $this->mes, $this->año);
            return range(16, $diasEnMes);
        }
    }

    /**
     * Obtener el valor de asistencia para un día específico
     */
    public function getAsistenciaDia($dia)
    {
        // Para primera quincena: días 1-15 van a dia_1 - dia_15
        // Para segunda quincena: días 16-31 van a dia_16 - dia_31
        $campo = 'dia_' . $dia;
        return $this->$campo;
    }

    /**
     * Establecer asistencia para un día específico
     */
    public function setAsistenciaDia($dia, $valor)
    {
        // Para primera quincena: días 1-15 van a dia_1 - dia_15
        // Para segunda quincena: días 16-31 van a dia_16 - dia_31
        $campo = 'dia_' . $dia;
        $this->$campo = $valor;
    }

    /**
     * Determinar el periodo basado en el día actual
     */
    public static function determinarPeriodo($dia)
    {
        return $dia <= 15 ? 'primera_quincena' : 'segunda_quincena';
    }

    /**
     * Obtener información de un estado
     */
    public static function getEstadoInfo($estado)
    {
        return self::ESTADOS[$estado] ?? null;
    }

    /**
     * Calcular totales de asistencia para el empleado
     */
    public function calcularTotales()
    {
        $totales = [];
        foreach (self::ESTADOS as $estado => $info) {
            $totales[$estado] = 0;
        }
        $totales['sin_marcar'] = 0;

        $diasDelPeriodo = $this->getDiasDelPeriodo();
        
        foreach ($diasDelPeriodo as $dia) {
            $valor = $this->getAsistenciaDia($dia);
            if ($valor && isset(self::ESTADOS[$valor])) {
                $totales[$valor]++;
            } else {
                $totales['sin_marcar']++;
            }
        }

        return $totales;
    }

    /**
     * Obtener color para mostrar en la celda
     */
    public function getColorDia($dia)
    {
        $valor = $this->getAsistenciaDia($dia);
        if ($valor && isset(self::ESTADOS[$valor])) {
            return self::ESTADOS[$valor]['color'];
        }
        return '#f8f9fa'; // Color por defecto
    }

    /**
     * Obtener ícono para mostrar en la celda
     */
    public function getIconoDia($dia)
    {
        $valor = $this->getAsistenciaDia($dia);
        if ($valor && isset(self::ESTADOS[$valor])) {
            return self::ESTADOS[$valor]['icono'];
        }
        return '';
    }
}