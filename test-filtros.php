Route::get('/test-filtros', function() {
    $año = 2025;
    $mes = 1;
    $estado = 'todos';
    $periodo = 'primera_quincena';
    
    // Obtener empleados igual que en el controlador
    $query = \App\Models\Empleado::with(['puesto.cliente']);
    
    if ($estado !== 'todos') {
        $query->where('Estado', $estado);
    }
    
    $empleados = $query->orderBy('Apellido_Paterno')
                      ->orderBy('Apellido_Materno')
                      ->orderBy('Nombre')
                      ->get();
    
    // Obtener asistencias
    $asistencias = [];
    foreach ($empleados as $empleado) {
        $asistencia = \App\Models\Asistencia::where('empleado_id', $empleado->IdEmpleados)
                                           ->where('año', $año)
                                           ->where('mes', $mes)
                                           ->first();
        
        if ($asistencia) {
            $asistencias[$empleado->IdEmpleados] = $asistencia;
        }
    }
    
    return response()->json([
        'empleados_count' => $empleados->count(),
        'asistencias_count' => count($asistencias),
        'first_empleado' => $empleados->first(),
        'estados' => \App\Models\Empleado::select('Estado')->distinct()->pluck('Estado')->toArray()
    ]);
});