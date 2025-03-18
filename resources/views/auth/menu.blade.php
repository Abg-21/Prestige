<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Menú Principal</title>
    <link rel="stylesheet" href="{{ asset('css/styles_menu.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Estilos para mejorar el botón de cierre de sesión */
        header .header-container form button {
            background-color: #e74c3c;
            color: white;
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
        
        /* Mensaje de bienvenida */
        .welcome-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px 15px;
            margin: 10px;
            border-radius: 4px;
            border-left: 4px solid #28a745;
            position: absolute;
            top: 70px;
            right: 20px;
            z-index: 100;
            display: none;
        }
        
        /* Animación para el mensaje de bienvenida */
        .welcome-message.show {
            display: block;
            animation: fadeOut 5s forwards;
        }
        
        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h2>Bienvenido, {{ Auth::user()->name }}</h2>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Cerrar Sesión</button>
            </form>
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            <h1 class="titulo-principal">Grupo Promocional Prestige</h1>
            <a href="#" class="notificaciones">
                <img src="{{ asset('images/notificacion.png') }}" alt="Notificaciones" class="icono-notificaciones">
                @if(isset($notificaciones) && $notificaciones->count() > 0)
                    <span class="notificacion-indicador"></span>
                @endif 
            </a>
        </div>
    </header>
    
    <!-- Mensaje de bienvenida temporal -->
    @if(session('success'))
        <div id="welcomeMessage" class="welcome-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="menu-lateral">
        <div class="menu-item">Personal
            <div class="submenu-opciones">
                <ul>
                    <li><a href="{{ route('candidatos.index') }}">Candidatos</a></li>
                    <li><a href="{{ route('empleados.index') }}">Empleados</a></li>
                </ul>
            </div>
        </div>
        <div class="menu-item">Reclutamiento
            <div class="submenu-opciones">
                <ul>
                    <li><a href="{{ route('puestos.index') }}">Puestos</a></li>
                    <li><a href="{{ route('clientes.index') }}">Clientes</a></li>
                    <li><a href="{{ route('giros.index') }}">Giros</a></li>
                </ul>
            </div>
        </div>
        <div class="menu-item">Documentación
            <div class="submenu-opciones">
                <ul>
                    <li><a href="{{ route('documentos.index') }}">Documentos de entrada</a></li>
                    <li><a href="#">Documentos de salida</a></li>
                    <li><a href="{{ route('contratos.index') }}">Contratos</a></li>
                </ul>
            </div>
        </div>
        <div class="menu-item">Configuración
            <div class="submenu-opciones">
                <ul>
                    <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
                    <li><a href="{{ route('roles.index') }}">Roles</a></li>
                    @can('crear usuarios')
                        <li><a href="{{ route('usuarios.create') }}">Registrar usuario</a></li>
                    @endcan
                </ul>
            </div>
        </div>
         
        <div class="menu-item">Progreso de empleado</div>
        <div class="menu-item">Contabilidad
            <div class="submenu-opciones">
                <ul>
                    <li><a href="#">Préstamos</a></li>
                    <li><a href="#">Incidencias</a></li>
                    <li><a href="#">Nóminas</a></li>
                </ul>
            </div>
        </div>
    </div>

    <main>
        <div class="carousel">
            <img src="{{ asset('images/c1.png') }}" class="active">
            <img src="{{ asset('images/c2.png') }}">
            <img src="{{ asset('images/c3.png') }}">
            <img src="{{ asset('images/c4.png') }}">
        </div>
    </main>

    <script>
        // Carrusel de imágenes
        let index = 0;
        const images = document.querySelectorAll('.carousel img');
        
        setInterval(() => {
            images[index].classList.remove('active');
            index = (index + 1) % images.length;
            images[index].classList.add('active');
        }, 3000);
        
        // Mostrar mensaje de bienvenida con animación
        $(document).ready(function() {
            if ($("#welcomeMessage").length) {
                $("#welcomeMessage").addClass("show");
                setTimeout(function() {
                    $("#welcomeMessage").remove();
                }, 5000);
            }
        });
        
        // Prevenir navegación hacia atrás
        window.onload = function() {
            if (window.history && window.history.pushState) {
                window.history.pushState('forward', null, window.location.href);
                window.onpopstate = function() {
                    window.history.pushState('forward', null, window.location.href);
                };
            }
        }
        
        // Verificar timeout de sesión cada minuto
        setInterval(function() {
            $.ajax({
                url: "{{ route('check.session') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.expired) {
                        alert("Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.");
                        window.location.href = "{{ route('login') }}";
                    }
                }
            });
        }, 60000); // Verificar cada minuto
        
        // Actualizar tiempo de actividad con cada interacción
        $(document).on('click keypress mousemove', function() {
            $.ajax({
                url: "{{ route('update.activity') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
        });
        
        // Expandir/contraer submenús
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                // Toggle solo si tiene submenu
                if (this.querySelector('.submenu-opciones')) {
                    this.classList.toggle('active');
                }
            });
        });
    </script>
</body>
</html>