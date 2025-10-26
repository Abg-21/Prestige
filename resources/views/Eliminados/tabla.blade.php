@if($eliminados->count() > 0)
    <table style="width:100%; border-collapse: collapse; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #FE7743; color: #fff;">
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: left;">Tipo</th>
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: left;">Nombre/Identificador</th>
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: left;">Motivo</th>
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: left;">Eliminado Por</th>
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: left;">Fecha</th>
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: center; width: 120px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eliminados as $eliminado)
                <tr class="registro-eliminado" data-tipo="{{ $eliminado->tipo }}" style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 8px; border: 1px solid #ddd;">
                        @php
                            $tipoDisplay = $eliminado->tipo;
                            if ($eliminado->eliminable_type) {
                                $tipoDisplay = class_basename($eliminado->eliminable_type);
                            } elseif ($eliminado->tipo === 'desactivacion') {
                                $tipoDisplay = 'Empleado';
                            }
                        @endphp
                        <span style="background: #6c757d; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            {{ $tipoDisplay }}
                        </span>
                    </td>
                    <td style="padding: 12px 8px; border: 1px solid #ddd;">
                        @if($eliminado->eliminable)
                            @php
                                $nombre = 'N/A';
                                if ($eliminado->eliminable_type === 'App\Models\Candidato' || $eliminado->tipo === 'desactivacion') {
                                    $nombre = ($eliminado->eliminable->Nombre ?? 'N/A') . ' ' . ($eliminado->eliminable->Apellido_Paterno ?? '');
                                } elseif ($eliminado->eliminable_type === 'App\Models\Empleado') {
                                    $nombre = ($eliminado->eliminable->Nombre ?? 'N/A') . ' ' . ($eliminado->eliminable->Apellido_Paterno ?? '');
                                } elseif ($eliminado->eliminable_type === 'App\Models\Usuario') {
                                    $nombre = $eliminado->eliminable->nombre_usuario ?? $eliminado->eliminable->nombre ?? 'N/A';
                                } elseif ($eliminado->eliminable_type === 'App\Models\Cliente') {
                                    $nombre = $eliminado->eliminable->Empresa ?? 'N/A';
                                } else {
                                    $nombre = $eliminado->eliminable->nombre ?? $eliminado->eliminable->Nombre ?? 'N/A';
                                }
                            @endphp
                            {{ trim($nombre) }}
                        @else
                            <span style="color: #6c757d; font-style: italic;">Registro no encontrado</span>
                        @endif
                    </td>
                    <td style="padding: 12px 8px; border: 1px solid #ddd;">
                        @if($eliminado->motivo)
                            <span style="color: #6c757d;">{{ Str::limit($eliminado->motivo, 35) }}</span>
                        @else
                            <span style="color: #6c757d; font-style: italic;">Sin motivo</span>
                        @endif
                    </td>
                    <td style="padding: 12px 8px; border: 1px solid #ddd;">
                        @if($eliminado->usuario)
                            {{ $eliminado->usuario->nombre_usuario ?? $eliminado->usuario->nombre }}
                        @else
                            <span style="color: #6c757d; font-style: italic;">Sistema</span>
                        @endif
                    </td>
                    <td style="padding: 12px 8px; border: 1px solid #ddd; font-size: 13px; color: #6c757d;">
                        {{ $eliminado->eliminado_en ? $eliminado->eliminado_en->format('d/m/Y H:i') : $eliminado->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                        @php
                            $tipoParam = $eliminado->eliminable_type ? class_basename($eliminado->eliminable_type) : ($eliminado->tipo === 'desactivacion' ? 'Empleado' : $eliminado->tipo);
                            $nombreParam = '';
                            if ($eliminado->eliminable) {
                                if ($eliminado->eliminable_type === 'App\Models\Empleado' || $eliminado->tipo === 'desactivacion') {
                                    $nombreParam = $eliminado->eliminable->Nombre ?? 'este registro';
                                } elseif ($eliminado->eliminable_type === 'App\Models\Usuario') {
                                    $nombreParam = $eliminado->eliminable->nombre_usuario ?? $eliminado->eliminable->nombre ?? 'este registro';
                                } elseif ($eliminado->eliminable_type === 'App\Models\Cliente') {
                                    $nombreParam = $eliminado->eliminable->Empresa ?? 'este registro';
                                } else {
                                    $nombreParam = $eliminado->eliminable->Nombre ?? $eliminado->eliminable->nombre ?? 'este registro';
                                }
                            }
                        @endphp
                        
                        <div style="display: flex; justify-content: center; gap: 8px;">
                            <button type="button" 
                                    onclick="alert('Ver detalles: {{ addslashes($nombreParam) }}')"
                                    style="background: none; border: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center;"
                                    title="Ver detalles">
                                <img src="{{ asset('images/ver.png') }}" alt="Ver" style="width: 28px; height: 28px;">
                                <div style="font-size: 11px; color: #555;">Ver</div>
                            </button>
                            
                            @if($eliminado->eliminable)
                                <button type="button" 
                                        onclick="confirmarRestauracionEliminados('{{ $tipoParam }}', '{{ $eliminado->eliminable_id }}', '{{ addslashes($nombreParam) }}')"
                                        style="background: none; border: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center;"
                                        title="Restaurar registro">
                                    <img src="{{ asset('images/restaurar.png') }}" alt="Restaurar" style="width: 28px; height: 28px;">
                                    <div style="font-size: 11px; color: #4CAF50;">Restaurar</div>
                                </button>
                            @else
                                <button type="button" 
                                        style="background: none; border: none; padding: 5px; cursor: not-allowed; display: flex; flex-direction: column; align-items: center; opacity: 0.5;"
                                        disabled
                                        title="Registro no disponible">
                                    <img src="{{ asset('images/eliminar.png') }}" alt="No disponible" style="width: 28px; height: 28px;">
                                    <div style="font-size: 11px; color: #6c757d;">N/A</div>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </table>
    
    <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 4px; border-left: 4px solid #FE7743;">
        <small style="color: #6c757d;">
            <strong>Total de registros eliminados:</strong> {{ $eliminados->count() }}
        </small>
    </div>
@else
    <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px; border: 2px dashed #dee2e6;">
        <img src="{{ asset('images/eliminar.png') }}" alt="Sin registros" style="width: 64px; height: 64px; opacity: 0.5; margin-bottom: 20px;">
        <h4 style="color: #6c757d; margin-bottom: 10px;">No hay registros eliminados</h4>
        <p style="color: #6c757d; margin: 0;">Los registros eliminados aparecerán aquí cuando se desactiven.</p>
    </div>
@endif