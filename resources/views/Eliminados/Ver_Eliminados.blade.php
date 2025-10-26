@if($eliminado->eliminable)
    <div style="margin-bottom: 20px;">
        <h4 style="color: #FE7743; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #FE7743;">
            Información de {{ class_basename($eliminado->eliminable_type) ?? ucfirst($eliminado->tipo) }} Eliminado
        </h4>
    </div>

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <!-- Información del registro eliminado -->
        <div style="flex: 2; min-width: 300px;">
            <div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px;">
                <div style="background: #FE7743; color: white; padding: 12px 15px; border-radius: 6px 6px 0 0;">
                    <h6 style="margin: 0; font-size: 16px;">Datos del Registro</h6>
                </div>
                <div style="padding: 15px;">
                    @if($eliminado->eliminable_type === 'App\Models\Candidato')
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px 15px; margin-bottom: 12px;">
                            <strong>Nombre:</strong>
                            <span>{{ $eliminado->eliminable->Nombre ?? 'N/A' }}</span>
                            <strong>Apellido Paterno:</strong>
                            <span>{{ $eliminado->eliminable->Apellido_Paterno ?? 'N/A' }}</span>
                            <strong>Apellido Materno:</strong>
                            <span>{{ $eliminado->eliminable->Apellido_Materno ?? 'N/A' }}</span>
                            <strong>Email:</strong>
                            <span>{{ $eliminado->eliminable->Correo ?? 'N/A' }}</span>
                            <strong>Teléfono:</strong>
                            <span>{{ $eliminado->eliminable->Telefono ?? 'N/A' }}</span>
                            <strong>Puesto:</strong>
                            <span>{{ $eliminado->eliminable->puesto->Nombre_del_puesto ?? 'N/A' }}</span>
                        </div>
                    @elseif($eliminado->tipo === 'Empleado')
                        <div class="row">
                            <div class="col-sm-4"><strong>Nombre:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->Nombre ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Apellido Paterno:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->Apellido_Paterno ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Apellido Materno:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->Apellido_Materno ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Email:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->Correo ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Teléfono:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->Telefono ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>NSS:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->NSS ?? 'N/A' }}</div>
                        </div>
                    @elseif($eliminado->tipo === 'Usuario')
                        <div class="row">
                            <div class="col-sm-4"><strong>Usuario:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->nombre_usuario ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Email:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->email ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Rol:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->rol->nombre_rol ?? 'N/A' }}</div>
                        </div>
                    @elseif($eliminado->tipo === 'Cliente')
                        <div class="row">
                            <div class="col-sm-4"><strong>Empresa:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->Empresa ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Contacto:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->Nombre_Contacto ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Email:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->Email ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Teléfono:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->Telefono ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Giro:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->giro->Nombre_giro ?? 'N/A' }}</div>
                        </div>
                    @elseif($eliminado->tipo === 'Puesto')
                        <div class="row">
                            <div class="col-sm-4"><strong>Nombre del Puesto:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->Nombre_del_puesto ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Descripción:</strong></div>
                            <div class="col-sm-8">{{ $eliminado->eliminable->Descripcion ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Salario:</strong></div>
                            <div class="col-sm-8">${{ number_format($eliminado->eliminable->Salario ?? 0, 2) }}</div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Vista de detalles no disponible para este tipo de registro.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información de eliminación -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-trash"></i> Información de Eliminación
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Eliminado por:</strong><br>
                        <span class="text-muted">
                            {{ $eliminado->usuario->nombre_usuario ?? $eliminado->usuario->nombre ?? 'Sistema' }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Fecha de eliminación:</strong><br>
                        <span class="text-muted">
                            {{ $eliminado->eliminado_en ? $eliminado->eliminado_en->format('d/m/Y H:i:s') : $eliminado->created_at->format('d/m/Y H:i:s') }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Motivo:</strong><br>
                        <span class="text-muted">
                            {{ $eliminado->motivo ?? 'Sin motivo especificado' }}
                        </span>
                    </div>
                    
                    @if($eliminado->restaurado_en)
                        <div class="alert alert-success">
                            <strong>Ya restaurado:</strong><br>
                            {{ $eliminado->restaurado_en->format('d/m/Y H:i:s') }}<br>
                            <small>Por: {{ $eliminado->restaurado_por ?? 'Sistema' }}</small>
                        </div>
                    @else
                        <div class="d-grid">
                            <button type="button" 
                                    class="btn btn-success"
                                    onclick="confirmarRestauracion('{{ $eliminado->tipo }}', '{{ $eliminado->eliminable_id }}', '{{ addslashes($eliminado->eliminable->Nombre ?? $eliminado->eliminable->nombre_usuario ?? $eliminado->eliminable->Empresa ?? 'este registro') }}')">
                                <i class="fas fa-undo"></i> Restaurar Registro
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-danger">
        <h5><i class="fas fa-exclamation-triangle"></i> Registro No Encontrado</h5>
        <p>El registro original ha sido eliminado permanentemente de la base de datos y no puede ser restaurado.</p>
        <hr>
        <p class="mb-0">
            <strong>Información disponible:</strong><br>
            <strong>Tipo:</strong> {{ $eliminado->tipo }}<br>
            <strong>ID Original:</strong> {{ $eliminado->eliminable_id }}<br>
            <strong>Eliminado por:</strong> {{ $eliminado->usuario->nombre_usuario ?? 'Sistema' }}<br>
            <strong>Fecha:</strong> {{ $eliminado->eliminado_en ? $eliminado->eliminado_en->format('d/m/Y H:i:s') : $eliminado->created_at->format('d/m/Y H:i:s') }}
        </p>
    </div>
@endif