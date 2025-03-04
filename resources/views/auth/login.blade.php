<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-background">
            <div class="background-overlay">
                <h1>Grupo Prestige</h1>
                <form action="{{ route('login.attempt') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <div class="input-container">
                            <input type="text" id="username" name="username" required>
                            <i class="icon user-icon"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-container">
                            <input type="password" id="password" name="password" required>
                            <i class="icon password-icon"></i>
                        </div>
                    </div>
                    <button type="submit">Iniciar Sesión</button>
                    
                </form>
            </div>
        </div>
    </div>

    

    <script>
    // Agregar parámetro 'error' si la sesión tiene un mensaje de error
    @if (session('error'))
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('error', '1');
        window.history.replaceState({}, document.title, currentUrl);
    @endif
</script>
<script src="{{ asset('js/modal.js') }}"></script>

</body>
