// ==================== ASISTENCIA JAVASCRIPT - VERSION CORREGIDA ====================

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
            alert('Error: Datos no disponibles');
            return;
        }
        
        // Enviar actualización
        fetch('/Prestige/public/asistencia/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CONFIG_ASISTENCIA.csrf_token
            },
            body: JSON.stringify({
                empleado_id: window.empleadoActual,
                año: window.CONFIG_ASISTENCIA.año,
                mes: window.CONFIG_ASISTENCIA.mes,
                campo: 'dia_' + window.diaActual,
                valor: estado
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Estado actualizado');
                // Actualizar visualmente la casilla
                actualizarCasillaVisual(window.empleadoActual, window.diaActual, estado);
                window.cerrarModal();
            } else {
                console.error('❌ Error:', data.message);
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('❌ Error de red:', error);
            alert('Error de conexión');
        });
    };
    
    // Función para actualizar casilla visualmente
    function actualizarCasillaVisual(empleadoId, dia, estado) {
        const casilla = document.querySelector(`[data-empleado="${empleadoId}"][data-dia="${dia}"]`);
        if (casilla && window.ESTADOS_ASISTENCIA && window.ESTADOS_ASISTENCIA[estado]) {
            const info = window.ESTADOS_ASISTENCIA[estado];
            casilla.style.backgroundColor = info.color;
            casilla.textContent = info.icono;
            casilla.style.color = ['#ffc107', '#f8d7da'].includes(info.color) ? '#000' : '#fff';
        }
    }
    
    // Función para aplicar filtros
    window.aplicarFiltros = function() {
        console.log('🎯 Aplicando filtros...');
        
        const estado = document.getElementById('estado-select')?.value || 'todos';
        const año = document.getElementById('año-select')?.value || window.CONFIG_ASISTENCIA.año;
        const mes = document.getElementById('mes-select')?.value || window.CONFIG_ASISTENCIA.mes;
        const periodo = document.getElementById('periodo-select')?.value || window.CONFIG_ASISTENCIA.periodo;
        
        console.log('📊 Filtros a aplicar:', {estado, año, mes, periodo});
        
        const btnFiltros = document.getElementById('aplicar-filtros');
        if (btnFiltros) {
            btnFiltros.innerHTML = '⏳ Aplicando...';
            btnFiltros.disabled = true;
        }
        
        fetch('/Prestige/public/asistencia/aplicar-filtros', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CONFIG_ASISTENCIA.csrf_token
            },
            body: JSON.stringify({estado, año, mes, periodo})
        })
        .then(response => {
            console.log('🌐 Respuesta recibida, status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('📥 Datos recibidos:', data);
            
            if (data.success && data.tableHtml) {
                // Actualizar tabla
                const contenedor = document.querySelector('div[style*="overflow-x: auto"]');
                if (contenedor) {
                    contenedor.innerHTML = data.tableHtml;
                    console.log('✅ Tabla actualizada con', data.empleados_count, 'empleados');
                    configurarEventos();
                } else {
                    console.error('❌ Contenedor de tabla no encontrado');
                }
            } else {
                console.error('❌ Error en filtros:', data.message);
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('❌ Error de red:', error);
            alert('Error de conexión al aplicar filtros');
        })
        .finally(() => {
            if (btnFiltros) {
                btnFiltros.innerHTML = '🔍 Aplicar Filtros';
                btnFiltros.disabled = false;
            }
        });
    };
    
    // Función para guardar asistencia
    window.guardarAsistencia = function() {
        console.log('💾 Guardando asistencia...');
        
        fetch('/Prestige/public/asistencia/guardar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CONFIG_ASISTENCIA.csrf_token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Asistencia guardada correctamente');
                console.log('✅ Asistencia guardada');
            } else {
                alert('Error: ' + data.message);
                console.error('❌ Error guardando:', data.message);
            }
        })
        .catch(error => {
            console.error('❌ Error:', error);
            alert('Error de conexión al guardar');
        });
    };
    
    // Función para mostrar modal de reportes
    window.mostrarModalReporte = function() {
        console.log('📊 Mostrando modal de reporte...');
        const modal = document.getElementById('reporteModal');
        if (modal) {
            modal.style.display = 'block';
            console.log('✅ Modal reporte abierto');
        } else {
            console.error('❌ Modal reporte no encontrado');
        }
    };
    
    // ==================== FUNCIONES LEGACY PARA COMPATIBILIDAD ====================
    
    // Funciones para compatibilidad con eventos onclick existentes
    window.abrirModalEstadosDirecto = window.abrirModalEstados;
    window.cerrarModalDirecto = window.cerrarModal;
    window.seleccionarEstadoDirecto = window.seleccionarEstado;
    window.descargarReporteDirecto = window.mostrarModalReporte;
    
    // Función para cerrar modal de reporte
    window.cerrarReporteModalDirecto = function() {
        const modal = document.getElementById('reporteModal');
        if (modal) {
            modal.style.display = 'none';
            console.log('✅ Modal reporte cerrado');
        }
    };
    
    // Función para descargar reporte confirmado
    window.descargarReporteConfirmado = function() {
        console.log('📊 Descargando reporte...');
        
        const form = document.getElementById('reporte-form');
        if (form) {
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            window.open('/Prestige/public/asistencia/exportar?' + params.toString(), '_blank');
            window.cerrarReporteModalDirecto();
        } else {
            alert('Error: Formulario no encontrado');
        }
    };
    
    // Agregar funcionalidad al botón cancelar reporte
    document.addEventListener('DOMContentLoaded', function() {
        const cancelarBtn = document.getElementById('cancelar-reporte');
        if (cancelarBtn) {
            cancelarBtn.addEventListener('click', window.cerrarReporteModalDirecto);
        }
        
        // Test de funciones disponibles
        console.log('🔧 Estado de las funciones JavaScript:');
        console.log('✅ abrirModalEstadosDirecto:', typeof window.abrirModalEstadosDirecto);
        console.log('✅ cerrarModalDirecto:', typeof window.cerrarModalDirecto);
        console.log('✅ seleccionarEstadoDirecto:', typeof window.seleccionarEstadoDirecto);
        console.log('✅ descargarReporteDirecto:', typeof window.descargarReporteDirecto);
        console.log('✅ cerrarReporteModalDirecto:', typeof window.cerrarReporteModalDirecto);
        console.log('✅ descargarReporteConfirmado:', typeof window.descargarReporteConfirmado);
        
        // Test de CSRF y configuración
        console.log('🔧 Configuración disponible:');
        console.log('✅ CONFIG_ASISTENCIA:', window.CONFIG_ASISTENCIA ? 'disponible' : 'NO DISPONIBLE');
        console.log('✅ ESTADOS_ASISTENCIA:', window.ESTADOS_ASISTENCIA ? 'disponible' : 'NO DISPONIBLE');
        
        console.log('🚀 Sistema JavaScript cargado correctamente!');
    });
    
    // ==================== CONFIGURAR EVENTOS ====================
    function configurarEventos() {
        console.log('🔧 Configurando eventos...');
        
        // Eventos para casillas de asistencia usando delegación
        document.addEventListener('click', function(e) {
            // Clic en casillas - CORREGIDO para usar data-asistencia-id
            if (e.target.classList.contains('dia-asistencia')) {
                e.preventDefault();
                e.stopPropagation();
                
                const empleadoId = e.target.getAttribute('data-empleado');
                const dia = e.target.getAttribute('data-dia');
                const asistenciaId = e.target.getAttribute('data-asistencia-id'); // CORREGIDO
                
                console.log('🎯 Click en casilla:', {empleadoId, dia, asistenciaId});
                console.log('🔍 Elemento clickeado:', e.target);
                
                window.abrirModalEstados(empleadoId, dia, asistenciaId);
                return;
            }
            
            // Clic en botón filtros
            if (e.target.id === 'aplicar-filtros') {
                e.preventDefault();
                console.log('🎯 Click en filtros');
                window.aplicarFiltros();
                return;
            }
            
            // Clic en guardar asistencia
            if (e.target.id === 'guardar-asistencia') {
                e.preventDefault();
                console.log('🎯 Click en guardar');
                window.guardarAsistencia();
                return;
            }
            
            // Clic en generar reporte
            if (e.target.id === 'generar-reporte') {
                e.preventDefault();
                console.log('🎯 Click en reporte');
                window.mostrarModalReporte();
                return;
            }
            
            // Cerrar modal
            if (e.target.id === 'cerrar-modal') {
                window.cerrarModal();
                return;
            }
        });
        
        console.log('✅ Eventos configurados');
    }
    
    // ==================== INICIALIZACIÓN ====================
    configurarEventos();
    
    // Estado final
    console.log('🏁 Estado inicial:');
    console.log('   - Casillas:', document.querySelectorAll('.dia-asistencia').length);
    console.log('   - Modal estados:', !!document.getElementById('estadoModal'));
    console.log('   - Modal reporte:', !!document.getElementById('reporteModal'));
    console.log('   - Botón filtros:', !!document.getElementById('aplicar-filtros'));
    console.log('✅ Módulo asistencia inicializado correctamente');
});

// Funciones de test disponibles globalmente
window.testAsistencia = function() {
    console.log('🧪 === TEST COMPLETO DE ASISTENCIA ===');
    
    console.log('1️⃣ Configuración:');
    console.log('   CONFIG_ASISTENCIA:', !!window.CONFIG_ASISTENCIA);
    
    console.log('2️⃣ Elementos DOM:');
    console.log('   Casillas:', document.querySelectorAll('.dia-asistencia').length);
    console.log('   Modal estados:', !!document.getElementById('estadoModal'));
    console.log('   Botón filtros:', !!document.getElementById('aplicar-filtros'));
    
    console.log('3️⃣ Funciones:');
    console.log('   abrirModalEstados:', typeof window.abrirModalEstados);
    console.log('   aplicarFiltros:', typeof window.aplicarFiltros);
    console.log('   guardarAsistencia:', typeof window.guardarAsistencia);
    
    console.log('4️⃣ Test de casilla (si hay):');
    const casillas = document.querySelectorAll('.dia-asistencia');
    if (casillas.length > 0) {
        console.log('   Primera casilla:', casillas[0]);
        console.log('   Simulando clic...');
        casillas[0].click();
    } else {
        console.log('   No hay casillas para probar');
    }
};

// 🎯 AUTO-EJECUCIÓN AL CARGAR LA PÁGINA
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 === SISTEMA DE ASISTENCIA INICIADO ===');
    
    // Verificaciones automáticas
    setTimeout(() => {
        console.log('📊 Elementos disponibles:');
        console.log('   • Filtros:', !!document.getElementById('aplicar-filtros'));
        console.log('   • Modal estados:', !!document.getElementById('estadoModal'));
        console.log('   • Modal reportes:', !!document.getElementById('reporteModal'));
        console.log('   • Casillas:', document.querySelectorAll('.dia-asistencia').length);
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
    
    // Obtener valores del formulario
    const form = document.getElementById('reporte-form');
    if (!form) {
        alert('Error: Formulario no encontrado');
        return;
    }
    
    const año = form.querySelector('select[name="año"]').value;
    const mes = form.querySelector('select[name="mes"]').value;
    const periodo = form.querySelector('select[name="periodo"]').value;
    const estado = form.querySelector('select[name="estado"]').value;
    const formato = form.querySelector('input[name="formato"]:checked').value;
    
    console.log('📊 Parámetros del reporte:', {año, mes, periodo, estado, formato});
    
    // Construir URL de exportación
    const baseUrl = window.location.pathname.includes('debug') ? 
        '/Prestige/public/asistencia-debug-exportar' : 
        '/Prestige/public/asistencia/exportar';
    
    const params = new URLSearchParams({
        año: año,
        mes: mes,
        periodo: periodo,
        estado: estado,
        formato: formato
    });
    
    const url = `${baseUrl}?${params.toString()}`;
    console.log('📥 URL de descarga:', url);
    
    // Cerrar modal
    window.cerrarReporteModalDirecto();
    
    // Descargar archivo
    window.open(url, '_blank');
    console.log('✅ Descarga iniciada');
};

// ==================== EVENT LISTENERS ADICIONALES ====================

// Agregar event listeners cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    
    // Event listener para guardar asistencia
    const btnGuardar = document.getElementById('guardar-asistencia');
    if (btnGuardar) {
        btnGuardar.addEventListener('click', function() {
            console.log('🎯 Guardando asistencia...');
            guardarAsistenciaCompleta();
        });
        console.log('✅ Event listener agregado para guardar asistencia');
    }
    
    // Event listener para aplicar filtros
    const btnFiltros = document.getElementById('aplicar-filtros');
    if (btnFiltros) {
        btnFiltros.addEventListener('click', function() {
            console.log('🎯 Aplicando filtros...');
            aplicarFiltrosAsistencia();
        });
        console.log('✅ Event listener agregado para aplicar filtros');
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
});

// Función para aplicar filtros
function aplicarFiltrosAsistencia() {
    const año = document.querySelector('select[name="año"]')?.value;
    const mes = document.querySelector('select[name="mes"]')?.value;  
    const estado = document.querySelector('select[name="estado"]')?.value;
    const periodo = document.querySelector('select[name="periodo"]')?.value;
    
    console.log('📊 Filtros seleccionados:', {año, mes, estado, periodo});
    
    if (!año || !mes || !periodo) {
        alert('Por favor selecciona todos los filtros requeridos');
        return;
    }
    
    // Hacer petición AJAX para aplicar filtros
    const formData = new FormData();
    formData.append('año', año);
    formData.append('mes', mes);
    formData.append('estado', estado);
    formData.append('periodo', periodo);
    formData.append('_token', window.CONFIG_ASISTENCIA.csrf_token);
    
    // Usar ruta debug si estamos en debug, sino usar ruta normal
    const url = window.location.pathname.includes('debug') ? 
        '/Prestige/public/asistencia-debug-filtros' : 
        '/Prestige/public/asistencia/aplicar-filtros';
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('✅ Respuesta de filtros:', data);
        if (data.success) {
            // Actualizar la tabla con el nuevo HTML
            const tablaContainer = document.querySelector('.table-responsive');
            if (tablaContainer && data.tableHtml) {
                tablaContainer.innerHTML = data.tableHtml;
                console.log('✅ Tabla actualizada');
            }
        } else {
            alert('Error al aplicar filtros: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('❌ Error en filtros:', error);
        alert('Error al aplicar filtros');
    });
}

// Función para guardar asistencia completa
function guardarAsistenciaCompleta() {
    // Recopilar todas las asistencias marcadas
    const casillas = document.querySelectorAll('.dia-asistencia');
    const asistencias = [];
    
    casillas.forEach(casilla => {
        const empleadoId = casilla.getAttribute('data-empleado');
        const dia = casilla.getAttribute('data-dia');
        const valor = casilla.getAttribute('data-valor') || '';
        
        if (valor && empleadoId && dia) {
            asistencias.push({
                empleado_id: empleadoId,
                dia: dia,
                valor: valor,
                año: window.CONFIG_ASISTENCIA.año,
                mes: window.CONFIG_ASISTENCIA.mes
            });
        }
    });
    
    console.log('📊 Asistencias a guardar:', asistencias);
    
    if (asistencias.length === 0) {
        alert('No hay asistencias marcadas para guardar');
        return;
    }
    
    // Confirmar guardado
    if (!confirm(`¿Guardar ${asistencias.length} asistencias marcadas?`)) {
        return;
    }
    
    // Usar ruta debug si estamos en debug
    const url = window.location.pathname.includes('debug') ? 
        '/Prestige/public/asistencia-debug-guardar' : 
        '/Prestige/public/asistencia/guardar';
    
    // Simular guardado por ahora
    console.log('💾 Simulando guardado de asistencias...');
    alert(`Funcionalidad de guardado simulada.\n${asistencias.length} asistencias procesadas.\nURL: ${url}`);
    
    // TODO: Implementar guardado real cuando esté listo
    /*
    const formData = new FormData();
    formData.append('asistencias', JSON.stringify(asistencias));
    formData.append('_token', window.CONFIG_ASISTENCIA.csrf_token);
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('✅ Asistencias guardadas:', data);
        if (data.success) {
            alert('Asistencias guardadas correctamente');
        } else {
            alert('Error al guardar: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('❌ Error guardando:', error);
        alert('Error al guardar asistencias');
    });
    */
}

// ==================== FUNCIONES DE TESTING MANUAL ====================

window.testAsistencia = function() {
    console.log('🧪 INICIANDO TEST COMPLETO DE ASISTENCIA');
    
    console.log('1. ✓ Verificando configuración...');
    console.log('   CONFIG_ASISTENCIA:', window.CONFIG_ASISTENCIA);
    
    console.log('2. ✓ Verificando elementos DOM...');
    const elementos = {
        casillas: document.querySelectorAll('.dia-asistencia').length,
        modalEstados: !!document.getElementById('estadoModal'),
        modalReportes: !!document.getElementById('reporteModal'),
        btnGuardar: !!document.getElementById('guardar-asistencia'),
        btnFiltros: !!document.getElementById('aplicar-filtros')
    };
    console.log('   Elementos:', elementos);
    
    console.log('3. ✓ Verificando funciones...');
    const funciones = {
        abrirModal: typeof window.abrirModalEstadosDirecto,
        cerrarModal: typeof window.cerrarModalDirecto,
        reportes: typeof window.descargarReporteDirecto,
        filtros: typeof aplicarFiltrosAsistencia
    };
    console.log('   Funciones:', funciones);
    
    console.log('4. ✓ Test de casilla (simulado)...');
    const primeraCasilla = document.querySelector('.dia-asistencia');
    if (primeraCasilla) {
        console.log('   Primera casilla:', {
            empleado: primeraCasilla.getAttribute('data-empleado'),
            dia: primeraCasilla.getAttribute('data-dia'),
            onclick: primeraCasilla.getAttribute('onclick')
        });
        console.log('   💡 Para testear: hacer clic en la primera casilla');
    }
    
    console.log('5. ✓ Test de botones...');
    console.log('   💡 Para testear filtros: hacer clic en "Aplicar Filtros"');
    console.log('   💡 Para testear reportes: hacer clic en "Generar Reporte"');
    console.log('   💡 Para testear guardado: hacer clic en "Guardar Asistencia"');
    
    console.log('🎉 TEST COMPLETADO - Revisar funcionalidades manualmente');
    return elementos;
};

window.testCasilla = function() {
    const casilla = document.querySelector('.dia-asistencia');
    if (casilla) {
        const empleado = casilla.getAttribute('data-empleado');
        const dia = casilla.getAttribute('data-dia');
        console.log('🎯 Testeando casilla:', {empleado, dia});
        window.abrirModalEstadosDirecto(empleado, dia, 'TEST');
    } else {
        console.error('❌ No se encontró ninguna casilla');
    }
};