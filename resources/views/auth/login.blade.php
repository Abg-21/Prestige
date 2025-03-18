<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-background">
            <div class="background-overlay">
                <h1>Grupo Prestige</h1>
                
                @if(session('error'))
                    <p class="error">{{ session('error') }}</p>
                @endif
                
                <form action="{{ route('login.attempt') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <div class="input-container">
                            <input type="text" id="username" name="username" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-container">
                            <input type="password" id="password" name="password" required>
                        </div>
                    </div>
                    <button type="submit">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Evitar navegación hacia atrás después de logout
        window.onload = function() {
            if (window.history && window.history.pushState) {
                window.history.pushState('forward', null, window.location.href);
                window.onpopstate = function() {
                    window.history.pushState('forward', null, window.location.href);
                };
            }
        }
        
        // Desactivar el cache para evitar que la página se muestre después de logout
        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };
    </script>
</body>
</html>
