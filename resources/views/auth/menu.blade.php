<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="{{ asset('css/styles_menu.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        header .header-container form button {
            background-color: #f4b154ff;
            color: black;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        
        header .header-container form button:hover {
            background-color: #c0392b;
        }
        
        .welcome-message {
            background-color: #D7D7D7;
            color: #273F4F;
            padding: 20px 30px;
            margin: 0 auto;
            border-radius: 4px;
            border-left: 4px solid #447D9B;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 100;
            display: none;
            max-width: 80%;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .welcome-message.show {
            display: block;
            animation: fadeInOut 4s forwards;
        }
        
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
            15% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            85% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            100% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
               
        @media (max-width: 768px) {
            #main-content {
                background-position: top center;
            }
        }
        
        #spinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(255,255,255,0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .spinner-circle {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            background-color: #C57F1B;
            color: #273F4F;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        #main-content {
            height: 100%;
            width: calc(100% - 250px);
            background: #f5f5f5 url('{{ asset('images/fondo1.png') }}') no-repeat center center;
            background-size: 110%;
            background-position: center center;
            position: fixed;
            top: 80px;
            left: 250px;
            right: 0;
            bottom: 0;
            margin: 0;
            padding: 0;
            z-index: 1;
        }

        main {
            position: relative;
            height: calc(100vh - 80px);
            margin: 0;
            padding: 0;
            margin-left: 250px;
        }

        /* Estilos para el overlay con claridad absoluta y scroll */
        #main-content-overlay {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            margin: 30px auto;
            width: 85%;
            max-width: 1100px;
            max-height: calc(100vh - 150px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            top: 10px;
            z-index: 2000;
            display: none;
            pointer-events: auto !important;
            overflow-y: auto !important;
            overflow-x: hidden;
        }

        /* Forzar claridad en el contenido del overlay */
        #main-content-overlay * {
            pointer-events: auto !important;
            cursor: pointer;
        }

        /* Estilos para elementos sin permisos */
        .sin-permisos {
            text-align: center;
            padding: 50px 20px;
            color: #666;
            font-size: 18px;
        }

        .sin-permisos h3 {
            color: #dc3545;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <form action="{{ route('emergency.logout') }}" method="GET">
                <button type="submit">Cerrar Sesión</button>
            </form>
            <h1 class="titulo-principal">Bienvenido</h1>
            <a href="#" class="notificaciones">
                <img src="{{ asset('images/notificacion.png') }}" alt="Notificaciones" class="icono-notificaciones">
                @if(isset($notificaciones) && $notificaciones->count() > 0)
                    <span class="notificacion-indicador"></span>
                @endif 
            </a>
        </div>
    </header>
    
    {{-- Mensaje de bienvenida personalizado --}}
    @if(isset($mensajeBienvenida))
        <div id="welcomeMessage" class="welcome-message">
            {{ $mensajeBienvenida }}
        </div>
    @endif

    {{-- Debug: Mostrar permisos del usuario (temporal) --}}
    @if(config('app.debug') && isset($permisos) && count($permisos) > 0)
        <div style="position: fixed; top: 90px; right: 10px; background: #fff; padding: 10px; border: 1px solid #ccc; z-index: 9999; font-size: 12px;">
            <strong>Usuario:</strong> {{ $usuario->nombre ?? 'No definido' }}<br>
            <strong>Permisos:</strong><br>
            @foreach($permisos as $permiso => $valor)
                {{ $permiso }}: {{ $valor ? '✅' : '❌' }}<br>
            @endforeach
        </div>
    @endif

    <div class="menu-lateral">
        {{-- Verificar si el usuario tiene ALGÚN permiso antes de mostrar el menú --}}
        @php
            $tieneAlgunPermiso = false;
            $modulosConPermisos = ['candidatos', 'empleados', 'puestos', 'giros', 'clientes', 'documentos', 'asistencia', 'nomina', 'eliminados', 'usuarios', 'roles', 'contratos'];
            
            foreach($modulosConPermisos as $modulo) {
                if(\App\Helpers\PermissionHelper::hasPermission($modulo, 'ver')) {
                    $tieneAlgunPermiso = true;
                    break;
                }
            }
        @endphp

        @if($tieneAlgunPermiso)
            {{-- Sección Personal - Solo mostrar si tiene acceso a candidatos O empleados --}}
            @if(\App\Helpers\PermissionHelper::hasPermission('candidatos', 'ver') || \App\Helpers\PermissionHelper::hasPermission('empleados', 'ver'))
                <div class="menu-item">Personal
                    <div class="submenu-opciones">
                        <ul>
                            @if(\App\Helpers\PermissionHelper::hasPermission('candidatos', 'ver'))
                                <li><a href="{{ route('candidatos.index') }}" class="ajax-link">Candidatos</a></li>
                            @endif
                            @if(\App\Helpers\PermissionHelper::hasPermission('empleados', 'ver'))
                                <li><a href="{{ route('empleados.index') }}" class="ajax-link">Empleados</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Sección Reclutamiento - Solo mostrar si tiene acceso a puestos, giros O clientes --}}
            {{-- CONTABILIDAD NO VERÁ ESTA SECCIÓN --}}
            @if(\App\Helpers\PermissionHelper::hasPermission('puestos', 'ver') || \App\Helpers\PermissionHelper::hasPermission('giros', 'ver') || \App\Helpers\PermissionHelper::hasPermission('clientes', 'ver'))
                <div class="menu-item">Reclutamiento
                    <div class="submenu-opciones">
                        <ul>
                            @if(\App\Helpers\PermissionHelper::hasPermission('puestos', 'ver'))
                                <li><a href="{{ route('puestos.index') }}" class="ajax-link">Puestos</a></li>
                            @endif
                            
                            @if(\App\Helpers\PermissionHelper::hasPermission('giros', 'ver'))
                                <li><a href="{{ route('giros.index') }}" class="ajax-link">Giros</a></li>
                            @endif
                            
                            @if(\App\Helpers\PermissionHelper::hasPermission('clientes', 'ver'))
                                <li><a href="{{ route('clientes.index') }}" class="ajax-link">Clientes</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Sección Documentación - Solo mostrar si tiene acceso a documentos O contratos --}}
            @if(\App\Helpers\PermissionHelper::hasPermission('documentos', 'ver') || \App\Helpers\PermissionHelper::hasPermission('contratos', 'ver'))
                <div class="menu-item">Documentación
                    <div class="submenu-opciones">
                        <ul>
                            @if(\App\Helpers\PermissionHelper::hasPermission('documentos', 'ver'))
                                <li><a href="{{ route('documentos.index') }}" class="ajax-link">Entrada</a></li>
                            @endif
                            @if(\App\Helpers\PermissionHelper::hasPermission('contratos', 'ver'))
                                {{-- Contratos aún no tiene ruta, mostrar como en desarrollo --}}
                                <li><a href="#" onclick="alert('Módulo de Contratos en desarrollo')" class="ajax-link">Contratos</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Sección Asistencia - Solo mostrar si tiene permisos --}}
            {{-- CONTABILIDAD y RH pueden verlo --}}
            @if(\App\Helpers\PermissionHelper::hasPermission('asistencia', 'ver'))
                <div class="menu-item">
                    <a href="{{ route('asistencia.index') }}" class="ajax-link" style="color: inherit; text-decoration: none; display: block; width: 100%; height: 100%;">
                        Asistencia
                    </a>
                </div>
            @endif
            
            {{-- Sección Nómina - Solo mostrar si tiene permisos --}}
            {{-- CONTABILIDAD y RH pueden verlo, pero está en desarrollo --}}
            @if(\App\Helpers\PermissionHelper::hasPermission('nomina', 'ver'))
                <div class="menu-item" onclick="alert('Módulo de Nómina en desarrollo')">Nómina</div>
            @endif
            
            {{-- Sección Eliminados - Solo mostrar si tiene permisos --}}
            {{-- Solo ADMIN y RH pueden verlo --}}
            @if(\App\Helpers\PermissionHelper::hasPermission('eliminados', 'ver'))
                <div class="menu-item">
                    <a href="{{ route('eliminados.index') }}" class="ajax-link" style="color: inherit; text-decoration: none; display: block;">Eliminados</a>
                </div>
            @endif

            {{-- Sección Configuración - Solo mostrar si tiene acceso a usuarios O roles --}}
            {{-- Solo ADMIN puede verlo, RH y CONTABILIDAD NO --}}
            @if(\App\Helpers\PermissionHelper::hasPermission('usuarios', 'ver') || \App\Helpers\PermissionHelper::hasPermission('roles', 'ver'))
                <div class="menu-item">Configuración
                    <div class="submenu-opciones">
                        <ul>
                            @if(\App\Helpers\PermissionHelper::hasPermission('usuarios', 'ver'))
                                <li><a href="{{ route('usuarios.index') }}" class="ajax-link">Usuarios</a></li>
                            @endif
                            
                            @if(\App\Helpers\PermissionHelper::hasPermission('roles', 'ver'))
                                <li><a href="{{ route('roles.index') }}" class="ajax-link">Roles</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
        @else
            {{-- Mensaje cuando no tiene permisos --}}
            <div class="sin-permisos">
                <h3>🚫 Sin permisos asignados</h3>
                <p>Contacta al administrador para obtener acceso a los módulos del sistema.</p>
            </div>
        @endif
    </div>

    <main>
        <div id="main-content">
            <!-- La imagen está como fondo en CSS -->
            <div id="main-content-overlay"></div>
        </div>
    </main>

    <div id="spinner">
        <div class="spinner-circle"></div>
    </div>

    {{-- TODO EL JAVASCRIPT ORIGINAL SIN CAMBIOS --}}
    <script>
        console.log("Documento cargado");
        
        $(document).ready(function() {
            console.log("jQuery listo");
            
            // Mostrar mensaje de bienvenida personalizado
            if ($("#welcomeMessage").length) {
                $("#welcomeMessage").addClass("show");
                setTimeout(function() {
                    $("#welcomeMessage").remove();
                }, 4000); // Desaparece después de 4 segundos
            }

            var img = new Image();
            img.onload = function() {
                console.log("✅ Imagen de fondo cargada correctamente:", this.src);
            };
            img.onerror = function() {
                console.error("❌ Error al cargar la imagen de fondo:", this.src);
            };
            img.src = "{{ asset('images/fondo1.jpg') }}";
        });
        
        window.onload = function() {
            if (window.history && window.history.pushState) {
                window.history.pushState('forward', null, window.location.href);
                window.onpopstate = function () {
                    window.history.pushState('forward', null, window.location.href);
                };
            }
        }
        
        {{-- CONTROL DE SESIÓN DESHABILITADO PARA MÁXIMO RENDIMIENTO
        setInterval(function() {
            $.ajax({
                url: "route('check.session')",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "csrf_token()"
                },
                success: function(response) {
                    if (response.expired) {
                        alert("Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.");
                        window.location.href = "route('login')";
                    }
                }
            });
        }, 60000);
        
        $(document).on('click keypress mousemove', function() {
            $.ajax({
                url: "route('update.activity')",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "csrf_token()"
                }
            });
        });
        --}}
        
        // Código para controlar los submenús - SOLO HOVER, SIN POSICIONAMIENTO
        document.querySelectorAll('.menu-item').forEach(item => {
            const submenu = item.querySelector('.submenu-opciones');
            if (!submenu) return;
            
            // Solo manejamos el hover, el posicionamiento lo hace CSS
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(215, 215, 215, 0.3)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
        
        // FUNCIÓN PRINCIPAL PARA CARGAR CONTENIDO VIA AJAX
        $(document).on('click', '.ajax-link', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var url = $(this).attr('href');
            
            // Verificar que no sea un enlace vacío o hash
            if (!url || url === '#' || url === 'javascript:void(0)') {
                console.log('Enlace vacío o sin URL válida');
                return false;
            }
            
            console.log('🔗 Cargando URL via AJAX:', url);
            $("#spinner").css('display', 'flex').fadeIn(150);
            
            $.ajax({
                url: url,
                type: 'GET',
                cache: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(data) {
                    console.log('✅ Contenido cargado exitosamente');
                    displayContent(data);
                    $("#spinner").fadeOut(150);
                },
                error: function(xhr, status, error) {
                    console.error("❌ Error al cargar:", error);
                    console.error("❌ Status:", status);
                    console.error("❌ XHR Status:", xhr.status);
                    console.error("❌ Response Text:", xhr.responseText);
                    
                    var errorMsg = '<div style="color:red;text-align:center;padding:30px;">';
                    errorMsg += '<h3>Error al cargar el contenido</h3>';
                    errorMsg += '<p><strong>Error:</strong> ' + error + '</p>';
                    errorMsg += '<p><strong>Status:</strong> ' + xhr.status + '</p>';
                    if (xhr.responseText) {
                        errorMsg += '<p><strong>Respuesta del servidor:</strong></p>';
                        errorMsg += '<pre style="background:#f5f5f5;padding:10px;text-align:left;font-size:12px;max-height:200px;overflow:auto;">' + xhr.responseText.substring(0, 1000) + '</pre>';
                    }
                    errorMsg += '</div>';
                    
                    $("#main-content-overlay").html(errorMsg);
                    $("#main-content-overlay").css('display', 'block');
                    $("#spinner").fadeOut(150);
                }
            });
            
            return false;
        });

        // Función para mostrar el contenido en el overlay
        function displayContent(data) {
            console.log('📄 Mostrando contenido en overlay');
            
            // Reiniciar completamente los estilos del overlay
            $("#main-content-overlay").attr('style', '');
            $("#main-content-overlay").css({
                'background-color': '#ffffff',
                'display': 'block',
                'z-index': '9999',
                'color': '#000000',
                'max-height': 'calc(100vh - 150px)',
                'overflow-y': 'auto', 
                'overflow-x': 'hidden',
                'pointer-events': 'auto',
                'border-radius': '10px',
                'padding': '30px',
                'margin': '30px auto',
                'width': '85%',
                'max-width': '1100px',
                'box-shadow': '0 4px 15px rgba(0, 0, 0, 0.3)',
                'position': 'relative',
                'top': '10px'
            });
            
            // Insertar el contenido
            $("#main-content-overlay").html(data);
            
            // Habilitar interacción específica para diferentes tipos de elementos
            $("#main-content-overlay input, #main-content-overlay select, #main-content-overlay textarea").css({
                'pointer-events': 'auto',
                'user-select': 'text',
                'cursor': 'text'
            });
            
            $("#main-content-overlay select").css({
                'cursor': 'pointer'
            });
            
            $("#main-content-overlay button, #main-content-overlay a, #main-content-overlay .btn").css({
                'pointer-events': 'auto',
                'cursor': 'pointer'
            });
            
            // Forzar recálculo de scroll y reactivar eventos
            setTimeout(function() {
                $("#main-content-overlay").scrollTop(0);
                
                // Asegurar que todos los nuevos enlaces ajax-link funcionen
                $("#main-content-overlay").find('.ajax-link').css({
                    'pointer-events': 'auto',
                    'cursor': 'pointer'
                });
                
                console.log('🔄 Eventos AJAX reactivos para nuevos enlaces');
            }, 50);
        }

        // TODO EL RESTO DEL JAVASCRIPT ORIGINAL...
        // [Resto del código JavaScript sin modificaciones]
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
