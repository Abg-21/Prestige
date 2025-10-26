// ==================== ASISTENCIA JAVASCRIPT - VERSION ARREGLADA ====================
console.log('🚀 CARGANDO asistencia-fixed.js...');

// Función para inicializar CONFIG si no existe
function inicializarConfig() {
    if (!window.CONFIG_ASISTENCIA) {
        console.warn('⚠️ CONFIG_ASISTENCIA no encontrado, creando CONFIG por defecto...');
        
        // Obtener valores de los selects de la página si existen
        const mesSelect = document.querySelector('select[name="mes"]')?.value || new Date().getMonth() + 1;
        const periodoSelect = document.querySelector('select[name="periodo"]')?.value || 'primera_quincena';
        const estadoSelect = document.querySelector('select[name="estado"]')?.value || 'todos';
        
        window.CONFIG_ASISTENCIA = {
            año: new Date().getFullYear(),
            mes: parseInt(mesSelect),
            periodo: periodoSelect,
            estado: estadoSelect,
            csrf_token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            fecha_actual: {
                dia: new Date().getDate(),
                mes: new Date().getMonth() + 1,
                año: new Date().getFullYear(),
                nombre_dia: null,
                semana: null
            }
        };
        console.log('✅ CONFIG_ASISTENCIA creado con valores de la página:', window.CONFIG_ASISTENCIA);
    } else {
        console.log('✅ CONFIG_ASISTENCIA encontrado:', window.CONFIG_ASISTENCIA);
    }
}

// Función de test global
window.testAsistencia = function() {
    console.log('🧪 TEST - Verificando funcionalidades...');
    inicializarConfig();
    console.log('   • CONFIG_ASISTENCIA:', !!window.CONFIG_ASISTENCIA);
    console.log('   • CONFIG año:', window.CONFIG_ASISTENCIA?.año);
    console.log('   • CONFIG token:', window.CONFIG_ASISTENCIA?.csrf_token ? 'EXISTS' : 'MISSING');
    console.log('   • aplicarFiltrosAsistencia:', typeof window.aplicarFiltrosAsistencia);
    console.log('   • abrirModalReporte:', typeof window.abrirModalReporte);
    console.log('   • guardarAsistenciaCompleta:', typeof window.guardarAsistenciaCompleta);
    console.log('   • Botón filtros:', !!document.getElementById('aplicar-filtros'));
    console.log('   • Botón reportes:', !!document.getElementById('generar-reporte'));
    console.log('   • Botón guardar:', !!document.getElementById('guardar-asistencia'));
    
    // Test específico de casillas
    const casillas = document.querySelectorAll('.dia-asistencia');
    console.log(`   • Casillas encontradas: ${casillas.length}`);
    casillas.forEach((casilla, i) => {
        if (i < 5) { // Solo mostrar las primeras 5
            console.log(`     - Casilla ${i}: empleado=${casilla.getAttribute('data-empleado')}, dia=${casilla.getAttribute('data-dia')}, contenido="${casilla.textContent.trim()}"`);
        }
    });
    
    return '✅ Test completado - revisar console';
};

// Función temporal para marcar una casilla manualmente (para pruebas)
window.marcarCasillaTest = function(empleadoId, dia, estado = 'presente') {
    const casillas = document.querySelectorAll('.dia-asistencia');
    let marcada = false;
    casillas.forEach(casilla => {
        if (casilla.getAttribute('data-empleado') === empleadoId.toString() && 
            casilla.getAttribute('data-dia') === dia.toString()) {
            casilla.textContent = estado === 'presente' ? 'P' : 'F';
            casilla.style.backgroundColor = estado === 'presente' ? '#28a745' : '#dc3545';
            casilla.style.color = '#fff';
            casilla.style.fontWeight = 'bold';
            casilla.setAttribute('data-estado', estado);
            console.log(`✅ Casilla marcada: empleado ${empleadoId}, día ${dia}, estado ${estado}`);
            marcada = true;
        }
    });
    if (!marcada) {
        console.error(`❌ No se encontró casilla para empleado ${empleadoId}, día ${dia}`);
    }
    return marcada;
};

// Función para guardar UNA asistencia específica (para pruebas)
window.guardarUnaAsistenciaTest = async function(empleadoId, dia, estado) {
    inicializarConfig();
    
    const formData = new FormData();
    formData.append('empleado_id', empleadoId);
    formData.append('dia', dia);
    formData.append('valor', estado);
    formData.append('año', window.CONFIG_ASISTENCIA.año);
    formData.append('mes', window.CONFIG_ASISTENCIA.mes);
    formData.append('_token', window.CONFIG_ASISTENCIA.csrf_token);
    
    const baseURL = window.location.origin + (window.location.pathname.includes('Prestige') ? '/Prestige/public' : '');
    const url = `${baseURL}/asistencia/guardar`;
    
    console.log('🧪 Guardando asistencia de prueba:', {empleadoId, dia, estado, url});
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const data = await response.json();
        console.log('📊 Respuesta del servidor:', data);
        
        if (data.success) {
            console.log('✅ Guardado exitoso!');
            mostrarModalConfirmacion(1, 0, 1);
        } else {
            console.error('❌ Error al guardar:', data.message);
        }
        
        return data;
    } catch (error) {
        console.error('❌ Error de red:', error);
        return {success: false, error: error.message};
    }
};

// Variables globales
window.empleadoActual = null;
window.diaActual = null;
window.asistenciaIdActual = null;

// ==================== FUNCIONES PRINCIPALES GLOBALES ====================

// Función para abrir modal de estados
window.abrirModalEstados = function(empleadoId, dia, asistenciaId) {
    console.log('🎯 Abriendo modal:', {empleadoId, dia, asistenciaId});
    
    window.empleadoActual = empleadoId;
    window.diaActual = dia;
    window.asistenciaIdActual = asistenciaId;
    
    const modal = document.getElementById('estadoModal');
    if (modal) {
        modal.style.display = 'block';
        console.log('✅ Modal abierto');
    } else {
        console.error('❌ Modal no encontrado');
        alert('Error: Modal no encontrado');
    }
};

// Función para cerrar modal
window.cerrarModal = function() {
    const modal = document.getElementById('estadoModal');
    if (modal) {
        modal.style.display = 'none';
        console.log('✅ Modal cerrado');
    }
};

// Función para seleccionar estado
window.seleccionarEstado = function(estado) {
    console.log('🎯 Estado seleccionado:', estado);
    
    if (!window.empleadoActual || !window.diaActual) {
        console.error('❌ Datos incompletos');
        alert('Error: Datos incompletos');
        return;
    }
    
    // Actualizar visualmente la casilla
    actualizarCasillaVisual(window.empleadoActual, window.diaActual, estado);
    window.cerrarModal();
    
    // Simular guardado
    console.log('💾 Estado guardado:', {
        empleado: window.empleadoActual,
        dia: window.diaActual,
        estado: estado
    });
};

// Función para actualizar casilla visual
function actualizarCasillaVisual(empleadoId, dia, estado) {
    const casilla = document.querySelector(`[data-empleado="${empleadoId}"][data-dia="${dia}"]`);
    if (casilla) {
        // Usar los colores vivos exactos del modelo Asistencia::ESTADOS
        const estadosDisponibles = {
            'presente': { letra: 'P', color: '#28a745', nombre: 'Presente' },
            'falta': { letra: 'F', color: '#dc3545', nombre: 'Falta' },
            'vacaciones': { letra: 'VAC', color: '#6f42c1', nombre: 'Vacaciones' },
            'incapacidad': { letra: 'I', color: '#20c997', nombre: 'Incapacidad' },
            'prima_dominical': { letra: 'PD', color: '#fd7e14', nombre: 'Prima Dominical' },
            'bajas': { letra: 'B', color: '#e83e8c', nombre: 'Bajas' },
            'alta': { letra: 'A', color: '#ffc107', nombre: 'Alta' },
            'prestamo': { letra: 'P', color: '#007bff', nombre: 'Préstamo' },
            'vacante': { letra: 'V', color: '#343a40', nombre: 'Vacante' }
        };
        
        // Usar estados locales con letras
        const estadoInfo = estadosDisponibles[estado] || null;
        
        if (estadoInfo) {
            casilla.innerHTML = estadoInfo.letra;
            casilla.style.backgroundColor = estadoInfo.color;
            casilla.style.color = '#fff'; // Texto blanco para mejor contraste con colores vivos
            casilla.style.fontWeight = 'bold'; // Texto en negrita
            casilla.style.fontSize = '12px'; // Tamaño de fuente apropiado
            casilla.setAttribute('data-valor', estado);
            casilla.title = estadoInfo.nombre; // Tooltip
            console.log('✅ Casilla actualizada con letra:', {empleadoId, dia, estado, letra: estadoInfo.letra, color: estadoInfo.color});
        } else {
            // Estado no reconocido, usar primera letra del estado
            const letra = estado ? estado.charAt(0).toUpperCase() : '';
            casilla.innerHTML = letra;
            casilla.style.backgroundColor = '#f8f9fa';
            casilla.style.color = '#000';
            casilla.style.fontWeight = 'bold';
            casilla.setAttribute('data-valor', estado);
            casilla.title = estado || 'Sin estado';
            console.log('⚠️ Estado no reconocido, usando primera letra:', {estado, letra});
        }
    } else {
        console.error('❌ Casilla no encontrada:', {empleadoId, dia});
    }
}

// ==================== FUNCIONES "DIRECTO" PARA COMPATIBILIDAD ====================

// Aliases para las funciones que usa la vista
window.abrirModalEstadosDirecto = function(empleadoId, dia, asistenciaId) {
    console.log('🎯 abrirModalEstadosDirecto llamado:', {empleadoId, dia, asistenciaId});
    return window.abrirModalEstados(empleadoId, dia, asistenciaId);
};

window.cerrarModalDirecto = function() {
    console.log('🎯 cerrarModalDirecto llamado');
    return window.cerrarModal();
};

window.seleccionarEstadoDirecto = function(estado) {
    console.log('🎯 seleccionarEstadoDirecto llamado:', estado);
    return window.seleccionarEstado(estado);
};

window.cerrarReporteModalDirecto = function() {
    console.log('🎯 cerrarReporteModalDirecto llamado');
    const modal = document.getElementById('reporteModal');
    if (modal) {
        modal.style.display = 'none';
        console.log('✅ Modal de reportes cerrado');
    }
};

window.descargarReporteDirecto = function() {
    console.log('🎯 descargarReporteDirecto llamado');
    const modal = document.getElementById('reporteModal');
    if (modal) {
        modal.style.display = 'block';
        console.log('✅ Modal de reportes abierto');
    } else {
        console.error('❌ Modal de reportes no encontrado');
        alert('Error: Modal de reportes no encontrado');
    }
};

window.descargarReporteConfirmado = function() {
    console.log('🎯 descargarReporteConfirmado llamado');
    
    // Inicializar CONFIG si no existe
    inicializarConfig();
    
    // Obtener valores del formulario
    const form = document.getElementById('reporte-form');
    if (!form) {
        alert('Error: Formulario no encontrado');
        return;
    }
    
    // Usar año actual del CONFIG
    const año = window.CONFIG_ASISTENCIA.año;
    const mes = form.querySelector('select[name="mes"]').value;
    const periodo = form.querySelector('select[name="periodo"]').value;
    const estado = form.querySelector('select[name="estado"]').value;
    const formato = form.querySelector('input[name="formato"]:checked').value;
    
    console.log('📊 Parámetros del reporte:', {año, mes, periodo, estado, formato});
    
    // Detectar base URL automáticamente
    const baseURL = window.location.origin + (window.location.pathname.includes('Prestige') ? '/Prestige/public' : '');
    
    // Construir URL de exportación sin caracteres especiales
    const baseUrl = window.location.pathname.includes('debug') ? 
        `${baseURL}/asistencia-debug-exportar` : 
        `${baseURL}/asistencia/exportar`;
    
    // Construir URL manualmente para evitar problemas de codificación
    const url = `${baseUrl}?ano=${año}&mes=${mes}&periodo=${periodo}&estado=${encodeURIComponent(estado)}&formato=${formato}`;
    console.log('📥 URL de descarga:', url);
    console.log('🌐 Modo debug:', window.location.pathname.includes('debug'));
    
    // Iniciar descarga usando location.href para mayor compatibilidad
    console.log('📥 Iniciando descarga con URL:', url);
    window.location.href = url;
    
    // Cerrar modal después de un breve delay
    setTimeout(() => {
        window.cerrarReporteModalDirecto();
    }, 500);
    
    console.log('✅ Descarga iniciada');
};

// ==================== INICIALIZACIÓN ====================

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 ===== DOM CONTENT LOADED - INICIALIZANDO ASISTENCIA =====');
    
    // Verificar que las variables de configuración estén disponibles
    if (typeof window.CONFIG_ASISTENCIA === 'undefined') {
        console.error('❌ CONFIG_ASISTENCIA no está definido');
        return;
    }
    
    console.log('📊 Configuración cargada:', window.CONFIG_ASISTENCIA);
    
    // Debug: Verificar elementos en el DOM
    console.log('🔍 Elementos encontrados:');
    console.log('   • Casillas día:', document.querySelectorAll('.dia-asistencia').length);
    console.log('   • Modal estados:', !!document.getElementById('estadoModal'));
    console.log('   • Modal reportes:', !!document.getElementById('reporteModal'));
    console.log('   • Botón guardar:', !!document.getElementById('guardar-asistencia'));
    console.log('   • Botón filtros:', !!document.getElementById('aplicar-filtros'));
    
    // ==================== EVENT LISTENERS ====================
    
    // Event listener para guardar asistencia
    const btnGuardar = document.getElementById('guardar-asistencia');
    if (btnGuardar) {
        btnGuardar.addEventListener('click', function() {
            console.log('🎯 Guardando asistencia (addEventListener)...');
            window.guardarAsistenciaCompleta();
        });
        console.log('✅ Event listener agregado para guardar asistencia');
    }
    
    // Event listener para aplicar filtros
    const btnFiltros = document.getElementById('aplicar-filtros');
    if (btnFiltros) {
        btnFiltros.addEventListener('click', function(e) {
            console.log('🎯 BOTÓN FILTROS CLICKEADO (addEventListener)!');
            e.preventDefault();
            window.aplicarFiltrosAsistencia();
        });
        console.log('✅ Event listener agregado para aplicar filtros');
    } else {
        console.error('❌ No se encontró el botón aplicar-filtros');
    }
    
    // Event listeners para casillas de días (respaldo para onclick)
    const casillas = document.querySelectorAll('.dia-asistencia');
    casillas.forEach(casilla => {
        casilla.addEventListener('click', function(e) {
            e.preventDefault();
            const empleadoId = this.getAttribute('data-empleado');
            const dia = this.getAttribute('data-dia');
            const asistenciaId = this.getAttribute('data-asistencia-id');
            console.log('🎯 Casilla clickeada:', {empleadoId, dia, asistenciaId});
            window.abrirModalEstadosDirecto(empleadoId, dia, asistenciaId);
        });
    });
    console.log(`✅ Event listeners agregados para ${casillas.length} casillas`);
    
    // Event listener para cerrar modal con Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.cerrarModalDirecto();
            window.cerrarReporteModalDirecto();
        }
    });
    
    console.log('🎯 Event listeners adicionales configurados');
    
    // ==================== VERIFICACIÓN FINAL ====================
    setTimeout(() => {
        console.log('🔍 Verificación del sistema:');
        console.log('   • Casillas:', document.querySelectorAll('.dia-asistencia').length);
        console.log('   • Modal estados:', !!document.getElementById('estadoModal'));
        console.log('   • Modal reportes:', !!document.getElementById('reporteModal'));
        console.log('   • Configuración:', !!window.CONFIG_ASISTENCIA);
        
        console.log('💡 Funciones disponibles para testing:');
        console.log('   • window.testFiltros() - Probar filtros');
        console.log('   • window.testCasillas() - Probar casillas');
        console.log('   • window.descargarReporteDirecto() - Probar modal reportes');
        console.log('   • window.testCompleto() - Test completo del sistema');
        
        console.log('✅ Sistema listo para usar');
        
        // Test automático de funciones
        console.log('🧪 Testing funciones:');
        console.log('   • abrirModalEstadosDirecto:', typeof window.abrirModalEstadosDirecto);
        console.log('   • cerrarModalDirecto:', typeof window.cerrarModalDirecto);
        console.log('   • descargarReporteDirecto:', typeof window.descargarReporteDirecto);
        
        // Test clicking en la primera casilla encontrada
        const primeraCasilla = document.querySelector('.dia-asistencia');
        if (primeraCasilla) {
            console.log('🎯 Primera casilla encontrada:', {
                empleado: primeraCasilla.getAttribute('data-empleado'),
                dia: primeraCasilla.getAttribute('data-dia')
            });
        }
        
    }, 500);
});

// ==================== FUNCIONES DE FILTROS Y GUARDADO ====================

// Función para aplicar filtros (GLOBAL)
window.aplicarFiltrosAsistencia = function() {
    console.log('🚀 === FILTROS TEMPORALMENTE DESHABILITADOS ===');
    alert('Los filtros están temporalmente deshabilitados por error 500.\nEn su lugar, recarga la página con los parámetros deseados.');
    return;
    
    /* CÓDIGO DESHABILITADO TEMPORALMENTE
    console.log('🚀 === INICIANDO APLICAR FILTROS ===');
    
    // Inicializar CONFIG si no existe
    inicializarConfig();
    
    // Usar año actual del CONFIG o año actual como fallback
    const año = window.CONFIG_ASISTENCIA.año || new Date().getFullYear();
    const mes = document.querySelector('select[name="mes"]')?.value;  
    const estado = document.querySelector('select[name="estado"]')?.value;
    const periodo = document.querySelector('select[name="periodo"]')?.value;
    
    console.log('🔍 Elementos encontrados:');
    console.log('   • Select mes:', !!document.querySelector('select[name="mes"]'));
    console.log('   • Select estado:', !!document.querySelector('select[name="estado"]'));
    console.log('   • Select periodo:', !!document.querySelector('select[name="periodo"]'));
    console.log('📊 Filtros seleccionados:', {año, mes, estado, periodo});
    
    if (!mes || !periodo) {
        alert('Por favor selecciona mes y período');
        console.error('❌ Filtros incompletos:', {año, mes, estado, periodo});
        return;
    }
    
    // Verificar que el contenedor de tabla existe antes de enviar petición
    const tablaContainer = document.querySelector('.table-responsive') || 
                          document.querySelector('div[style*="overflow-x: auto"]');
    console.log('🔍 Contenedor de tabla encontrado:', !!tablaContainer);
    if (!tablaContainer) {
        console.error('❌ No se encontró contenedor de tabla');
        alert('Error: No se encontró la tabla para actualizar');
        return;
    }
    
    // Hacer petición AJAX para aplicar filtros
    const formData = new FormData();
    formData.append('año', año); // Usar el parámetro original
    formData.append('mes', mes);
    formData.append('estado', estado);
    formData.append('periodo', periodo);
    formData.append('_token', window.CONFIG_ASISTENCIA?.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
    
    // Detectar base URL automáticamente
    const baseURL = window.location.origin + (window.location.pathname.includes('Prestige') ? '/Prestige/public' : '');
    
    // Usar ruta normal con manejo de errores mejorado
    const url = `${baseURL}/asistencia/aplicar-filtros`;
    
    console.log('🌐 URL de filtros:', url);
    console.log('🔍 Modo debug:', window.location.pathname.includes('debug'));
    
    console.log('🌐 Enviando petición AJAX...');
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('📡 Respuesta recibida:', response.status, response.statusText);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('✅ Respuesta de filtros:', data);
        if (data.success) {
            // Encontrar el contenedor de la tabla en el DOM
            const tablaContainer = document.querySelector('div[style*="overflow-x: auto"]') || 
                                  document.querySelector('.table-responsive') ||
                                  document.querySelector('div.table-responsive');
            
            if (tablaContainer && data.tableHtml) {
                tablaContainer.outerHTML = data.tableHtml;
                console.log('✅ Tabla actualizada completamente');
                
                // Re-agregar event listeners a las nuevas casillas después de actualizar
                setTimeout(() => {
                    agregarEventListenersCasillas();
                    console.log('✅ Event listeners re-agregados');
                }, 100);
                
                // Actualizar información en pantalla
                console.log(`📊 Empleados encontrados: ${data.empleados_count || 0}`);
                if (data.diasDelPeriodo) {
                    console.log(`📅 Días del período: ${data.diasDelPeriodo.join(', ')}`);
                }
                
                // Mostrar mensaje de éxito
                const mensaje = `Filtros aplicados: ${data.empleados_count || 0} empleados encontrados`;
                console.log(`✅ ${mensaje}`);
                
            } else {
                console.error('❌ No se recibió HTML de tabla o contenedor no encontrado');
                console.log('🔍 Contenedor buscado:', tablaContainer);
            }
        } else {
            alert('Error al aplicar filtros: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('❌ Error en filtros:', error);
        alert('Error al aplicar filtros. Verifica la consola para más detalles.');
        console.error('📋 Detalles del error:', {
            url: url,
            parametros: {año, mes, estado, periodo},
            error: error.message || error
        });
    });
    */ // Fin del código deshabilitado
}

// Función para agregar event listeners a casillas después de actualizar tabla
function agregarEventListenersCasillas() {
    const casillas = document.querySelectorAll('.dia-asistencia');
    casillas.forEach(casilla => {
        casilla.addEventListener('click', function(e) {
            e.preventDefault();
            const empleadoId = this.getAttribute('data-empleado');
            const dia = this.getAttribute('data-dia');
            const asistenciaId = this.getAttribute('data-asistencia-id');
            console.log('🎯 Nueva casilla clickeada:', {empleadoId, dia, asistenciaId});
            window.abrirModalEstadosDirecto(empleadoId, dia, asistenciaId);
        });
    });
    console.log(`✅ Event listeners re-agregados para ${casillas.length} nuevas casillas`);
}

// Función para guardar asistencia completa
window.guardarAsistenciaCompleta = function() {
    console.log('🎯 Guardando asistencia completa...');
    
    // Inicializar CONFIG si no existe
    inicializarConfig();
    
    // Recopilar todas las casillas que han sido modificadas
    const casillas = document.querySelectorAll('.dia-asistencia');
    const asistencias = [];
    
    console.log(`🔍 Encontradas ${casillas.length} casillas para revisar`);
    
    casillas.forEach((casilla, index) => {
        const empleadoId = casilla.getAttribute('data-empleado');
        const dia = casilla.getAttribute('data-dia');
        
        // Verificar si la casilla tiene contenido (ha sido marcada)
        const contenido = casilla.textContent.trim();
        
            console.log(`📋 Casilla ${index}:`, {
            empleado: empleadoId, 
            dia: dia, 
            contenido: `"${contenido}"`, 
            html: `"${casilla.innerHTML}"`,
            backgroundColor: casilla.style.backgroundColor,
            dataEstado: casilla.getAttribute('data-estado'),
            tieneContenido: tieneContenido,
            tieneColor: tieneColorPersonalizado,
            tieneData: !!tieneDataEstado
        });
        
        // Verificar múltiples formas de detectar casillas marcadas
        const tieneContenido = contenido && contenido !== '' && contenido !== ' ';
        const tieneColorPersonalizado = casilla.style.backgroundColor && 
                                       !casilla.style.backgroundColor.includes('248, 249, 250') &&
                                       casilla.style.backgroundColor !== '#f8f9fa' &&
                                       casilla.style.backgroundColor !== '';
        const tieneDataEstado = casilla.getAttribute('data-estado');
        
        if ((tieneContenido || tieneColorPersonalizado || tieneDataEstado) && empleadoId && dia) {
            // Mapear el contenido a un valor de estado
            const mapeoEstados = {
                'P': 'presente',
                'F': 'falta', 
                'V': 'vacaciones',
                'VAC': 'vacaciones',
                'I': 'incapacidad',
                'PD': 'prima_dominical',
                'B': 'bajas',
                'A': 'alta'
            };
            const valor = tieneDataEstado || mapeoEstados[contenido] || contenido || 'presente';
            // Verificar CONFIG antes de usar
            const año = window.CONFIG_ASISTENCIA?.año || new Date().getFullYear();
            const mes = window.CONFIG_ASISTENCIA?.mes || new Date().getMonth() + 1;
            
            asistencias.push({
                empleado_id: empleadoId,
                dia: dia,
                valor: valor,
                año: año,
                mes: mes
            });
        }
    });
    
    console.log('📊 Asistencias a guardar:', asistencias);
    console.log('📋 Detalles de asistencias encontradas:', asistencias.map(a => ({
        empleado: a.empleado_id, 
        dia: a.dia, 
        valor: a.valor
    })));
    
    if (asistencias.length === 0) {
        console.warn('⚠️ No se encontraron asistencias marcadas');
        alert('No hay asistencias marcadas para guardar.\n\nPara marcar una casilla:\n1. Haz clic en una casilla de día\n2. Selecciona un estado del modal\n3. Intenta guardar nuevamente');
        return;
    }
    
    // Confirmar guardado
    if (!confirm(`¿Guardar ${asistencias.length} asistencias marcadas?`)) {
        return;
    }
    
    // Detectar base URL automáticamente
    const baseURL = window.location.origin + (window.location.pathname.includes('Prestige') ? '/Prestige/public' : '');
    
    // Usar ruta normal con manejo de errores mejorado
    const url = `${baseURL}/asistencia/guardar`;
    
    console.log('💾 Guardando asistencias reales...');
    console.log('🌐 URL de guardado:', url);
    
    // Guardar asistencias una por una (método actual del controlador)
    let guardadas = 0;
    let errores = 0;
    
    const guardarAsistencia = async (asistencia) => {
        const formData = new FormData();
        formData.append('empleado_id', asistencia.empleado_id);
        formData.append('dia', asistencia.dia);
        formData.append('valor', asistencia.valor);
        formData.append('año', asistencia.año);
        formData.append('mes', asistencia.mes);
        formData.append('_token', window.CONFIG_ASISTENCIA?.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const data = await response.json();
            if (data.success) {
                guardadas++;
            } else {
                errores++;
                console.error('❌ Error guardando asistencia:', data.message);
            }
        } catch (error) {
            errores++;
            console.error('❌ Error en petición:', error);
        }
    };
    
    // Procesar todas las asistencias
    Promise.all(asistencias.map(guardarAsistencia))
        .then(() => {
            mostrarModalConfirmacion(guardadas, errores, asistencias.length);
            console.log('📊 Resumen guardado:', {guardadas, errores, total: asistencias.length});
        })
        .catch(error => {
            console.error('❌ Error general:', error);
            mostrarModalConfirmacion(0, asistencias.length, asistencias.length, 'Error al procesar asistencias');
        });
}

// ==================== MODAL DE CONFIRMACIÓN ====================

// Función para mostrar modal de confirmación
function mostrarModalConfirmacion(exitosas, errores, total, mensajeError = null) {
    const modal = document.getElementById('modal-confirmacion');
    const icono = document.getElementById('icono-resultado');
    const titulo = document.getElementById('titulo-resultado');
    const mensaje = document.getElementById('mensaje-resultado');
    const detalle = document.getElementById('detalle-resultado');
    
    if (mensajeError) {
        // Error general
        icono.className = 'icono-error';
        icono.textContent = '✗';
        titulo.textContent = 'Error de Guardado';
        mensaje.textContent = mensajeError;
        detalle.innerHTML = `<strong>Total procesadas:</strong> ${total}`;
    } else if (errores === 0) {
        // Éxito total
        icono.className = 'icono-exito';
        icono.textContent = '✓';
        titulo.textContent = 'Guardado Exitoso';
        mensaje.textContent = 'Todos los cambios se han guardado correctamente';
        detalle.innerHTML = `
            <div style="color: #28a745;">
                <strong>✅ Guardadas:</strong> ${exitosas} de ${total}
            </div>
        `;
    } else {
        // Éxito parcial
        icono.className = exitosas > errores ? 'icono-exito' : 'icono-error';
        icono.textContent = exitosas > errores ? '✓' : '⚠';
        titulo.textContent = 'Guardado Parcial';
        mensaje.textContent = 'Algunos cambios no pudieron guardarse';
        detalle.innerHTML = `
            <div style="color: #28a745;">
                <strong>✅ Exitosas:</strong> ${exitosas}
            </div>
            <div style="color: #dc3545;">
                <strong>❌ Errores:</strong> ${errores}
            </div>
            <div style="color: #6c757d;">
                <strong>📊 Total:</strong> ${total}
            </div>
        `;
    }
    
    modal.style.display = 'block';
    
    // Auto-cerrar modal después de 2 segundos solo si fue exitoso completamente
    if (errores === 0 && !mensajeError) {
        setTimeout(() => {
            cerrarModalConfirmacion();
            console.log('✅ Modal cerrado automáticamente después de 2 segundos');
        }, 2000);
    }
}

// Función para cerrar modal de confirmación
function cerrarModalConfirmacion() {
    const modal = document.getElementById('modal-confirmacion');
    modal.style.display = 'none';
}

// Cerrar modal al hacer click fuera
window.onclick = function(event) {
    const modal = document.getElementById('modal-confirmacion');
    if (event.target === modal) {
        cerrarModalConfirmacion();
    }
}

// ==================== MODAL DE REPORTES ====================

// Función para abrir modal de reportes
window.abrirModalReporte = function() {
    console.log('📊 Abriendo modal de reportes...');
    const modal = document.getElementById('reporteModal');
    if (modal) {
        modal.style.display = 'block';
        console.log('✅ Modal de reportes abierto');
    } else {
        console.error('❌ No se encontró el modal de reportes');
    }
}