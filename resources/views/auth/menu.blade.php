<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <link rel="stylesheet" href="{{ asset('css/styles_menu.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            <h1 class="titulo-principal">Grupo Promocional Prestige</h1>
            <a href="#" class="notificaciones">
                <img src="{{ asset('images/notificacion.png') }}" alt="Notificaciones" class="icono-notificaciones">
                @if($notificaciones->count() > 0)
                    <span class="notificacion-indicador"></span>
                @endif
            </a>
        </div>
    </header>

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
        let index = 0;
        const images = document.querySelectorAll('.carousel img');

        setInterval(() => {
            images[index].classList.remove('active');
            index = (index + 1) % images.length;
            images[index].classList.add('active');
        }, 3000);
    </script>
</body>
</html>
