<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Asistencia</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .debug-section { background: #e9ecef; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .dia-asistencia { 
            display: inline-block; 
            width: 30px; 
            height: 30px; 
            margin: 2px; 
            text-align: center; 
            line-height: 30px; 
            cursor: pointer; 
            border: 2px solid #ddd; 
            border-radius: 4px;
            background: #f8f9fa;
            font-weight: bold;
        }
        .dia-asistencia:hover { 
            background: #007bff; 
            color: white; 
            transform: scale(1.1);
        }
        .btn { 
            padding: 10px 20px; 
            margin: 5px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .modal { 
            display: none; 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0,0,0,0.5); 
            z-index: 9999; 
        }
        .modal-content { 
            background: white; 
            margin: 15% auto; 
            padding: 20px; 
            width: 80%; 
            max-width: 500px; 
            border-radius: 8px; 
        }
        .close { 
            float: right; 
            font-size: 28px; 
            font-weight: bold; 
            cursor: pointer; 
        }
        .logs { 
            background: #000; 
            color: #00ff00; 
            padding: 15px; 
            border-radius: 5px; 
            font-family: monospace; 
            font-size: 12px; 
            height: 200px; 
            overflow-y: auto; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛠️ Debug Asistencia</h1>
        
        <div class="debug-section">
            <h3>Estado del Sistema</h3>
            <div id="status">Verificando...</div>
        </div>
        
        <div class="debug-section">
            <h3>Filtros de Prueba</h3>
            <button id="filtro-test" class="btn btn-primary">🔍 Aplicar Filtros</button>
            <button id="reset-test" class="btn btn-success">🔄 Reset</button>
        </div>
        
        <div class="debug-section">
            <h3>Casillas de Día (Prueba)</h3>
            <div class="dia-asistencia" data-empleado="1" data-dia="1" data-asistencia-id="100">1</div>
            <div class="dia-asistencia" data-empleado="1" data-dia="2" data-asistencia-id="101">2</div>
            <div class="dia-asistencia" data-empleado="1" data-dia="3" data-asistencia-id="102">3</div>
            <div class="dia-asistencia" data-empleado="1" data-dia="4" data-asistencia-id="103">4</div>
            <div class="dia-asistencia" data-empleado="1" data-dia="5" data-asistencia-id="104">5</div>
        </div>
        
        <div class="debug-section">
            <h3>Logs en Tiempo Real</h3>
            <div id="logs" class="logs"></div>
        </div>
    </div>

    <!-- Modal de Estados -->
    <div id="estadoModal" class="modal">
        <div class="modal-content">
            <span class="close" id="cerrarModal">&times;</span>
            <h2>Seleccionar Estado</h2>
            <p>Empleado: <span id="empleado-info"></span></p>
            <p>Día: <span id="dia-info"></span></p>
            <div style="margin-top: 20px;">
                <button class="btn btn-success">✅ Presente</button>
                <button class="btn btn-danger" style="background: #dc3545;">❌ Ausente</button>
                <button style="background: #ffc107; color: black;" class="btn">⏰ Tardanza</button>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let empleadoActual, diaActual, asistenciaIdActual;
        
        // Función de log
        function log(mensaje) {
            const timestamp = new Date().toLocaleTimeString();
            const logElement = document.getElementById('logs');
            logElement.innerHTML += `[${timestamp}] ${mensaje}\n`;
            logElement.scrollTop = logElement.scrollHeight;
            console.log(mensaje);
        }
        
        // Función para mostrar modal
        function mostrarModal(modal) {
            if (!modal) {
                log('❌ Modal no proporcionado');
                return;
            }
            
            log('🎯 Mostrando modal: ' + modal.id);
            modal.style.display = 'block';
            modal.style.zIndex = '9999';
            
            // Actualizar información del modal
            document.getElementById('empleado-info').textContent = empleadoActual;
            document.getElementById('dia-info').textContent = diaActual;
        }
        
        function ocultarModal(modal) {
            if (!modal) {
                log('❌ Modal no proporcionado para ocultar');
                return;
            }
            
            log('🎯 Ocultando modal: ' + modal.id);
            modal.style.display = 'none';
        }
        
        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            log('🚀 DOM Cargado - Iniciando debug');
            
            // Verificar elementos
            const casillas = document.querySelectorAll('.dia-asistencia');
            const modal = document.getElementById('estadoModal');
            const filtroBtn = document.getElementById('filtro-test');
            
            log('✅ Casillas encontradas: ' + casillas.length);
            log('✅ Modal encontrado: ' + (modal ? 'SÍ' : 'NO'));
            log('✅ Botón filtro encontrado: ' + (filtroBtn ? 'SÍ' : 'NO'));
            
            // Actualizar estado
            document.getElementById('status').innerHTML = `
                ✅ Casillas: ${casillas.length}<br>
                ✅ Modal: ${modal ? 'Presente' : 'Ausente'}<br>
                ✅ Botones: ${filtroBtn ? 'Presente' : 'Ausente'}
            `;
            
            // Configurar casillas
            casillas.forEach((casilla, index) => {
                log(`🔧 Configurando casilla ${index + 1}`);
                
                casilla.onclick = function(e) {
                    e.preventDefault();
                    empleadoActual = this.dataset.empleado;
                    diaActual = this.dataset.dia;
                    asistenciaIdActual = this.dataset.asistenciaId;
                    
                    log(`🎯 CLICK en día ${diaActual} para empleado ${empleadoActual}`);
                    mostrarModal(modal);
                };
                
                casilla.addEventListener('mouseenter', function() {
                    log(`🎯 Hover en día ${this.dataset.dia}`);
                });
            });
            
            // Configurar filtros
            filtroBtn?.addEventListener('click', function() {
                log('🔍 FILTRO APLICADO');
                alert('¡Filtro funcionando! ✅');
            });
            
            document.getElementById('reset-test')?.addEventListener('click', function() {
                log('🔄 RESET EJECUTADO');
                alert('¡Reset funcionando! ✅');
            });
            
            // Configurar cierre de modal
            document.getElementById('cerrarModal')?.addEventListener('click', function() {
                log('🎯 Cerrando modal');
                ocultarModal(modal);
            });
            
            // Cerrar modal al hacer clic fuera
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    log('🎯 Cerrando modal (click fuera)');
                    ocultarModal(modal);
                }
            });
            
            log('✅ Configuración completa');
        });
    </script>
</body>
</html>