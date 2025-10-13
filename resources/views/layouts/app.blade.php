<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Grupo Promocional Prestige</title>
    
    <!-- Google Fonts para el título llamativo -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Cinzel:wght@600&display=swap" rel="stylesheet">
    
    <style>
        /* Estilos para el modal de error */
        .venmodal {
            position: fixed;
            z-index: 1000;
            top: 20px;
            right: 20px;
            background-color: rgba(244, 67, 54, 0.9);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            width: 300px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            animation: slideInOut 3s ease-in-out forwards;
        }

        @keyframes slideInOut {
            0% {
                transform: translateX(100%);
                opacity: 0;
            }
            15% {
                transform: translateX(0);
                opacity: 1;
            }
            85% {
                transform: translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Fondo general del cuerpo */
        body {
            font-family: Arial, sans-serif;
            background: url('/images/CF1.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Contenedor principal del login */
        .login-container {
            background-color: transparent;
            width: 380px; /* Aumentamos un poco el ancho para el título más grande */
            text-align: center;
            position: relative;
        }

        /* Contenedor de fondo específico del login */
        .login-background {
            background: none;
            padding: 30px 20px 20px 20px; /* Más padding arriba para el título */
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        /* Superposición para aplicar efectos */
        .background-overlay {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px 25px;
            border-radius: 8px;
        }

        /* NUEVO: Estilos para el título llamativo */
        .titulo-empresa {
            font-family: 'Cinzel', serif;
            font-weight: 600;
            font-size: 28px;
            color: #B8860B; /* Dorado más elegante */
            text-align: center;
            margin-bottom: 25px;
            line-height: 1.2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            background: linear-gradient(135deg, #DAA520 0%, #B8860B 50%, #CD853F 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .titulo-linea {
            display: block;
            margin: 5px 0;
        }

        .titulo-linea:first-child {
            font-size: 32px;
            letter-spacing: 2px;
        }

        .titulo-linea:nth-child(2) {
            font-size: 30px;
            letter-spacing: 1px;
        }

        .titulo-linea:last-child {
            font-size: 34px;
            letter-spacing: 3px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #000000;
            font-weight: bold;
        }

        .input-container {
            position: relative;
        }

        input[type="email"], 
        input[type="password"] {
            width: calc(100% - 30px);
            padding: 12px;
            border: 2px solid #C57F1B;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus, 
        input[type="password"]:focus {
            outline: none;
            border-color: #B8860B;
            box-shadow: 0 0 8px rgba(184, 134, 11, 0.3);
        }

        /* Estilos para validación de campo inválido */
        input[type="email"]:invalid {
            border-color: #dc3545;
        }

        input[type="email"]:valid {
            border-color: #28a745;
        }

        .icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #C57F1B;
            font-size: 18px;
        }

        .user-icon::before {
            content: '\1F464';
        }

        .password-icon::before {
            content: '\1F512';
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #DAA520 0%, #B8860B 50%, #CD853F 100%);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(184, 134, 11, 0.4);
        }

        /* Mensaje de validación personalizado */
        .validation-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        /* Ocultar los estilos de Bootstrap para esta página */
        #app {
            display: contents;
        }
        
        main {
            padding: 0;
        }
    </style>
</head>
<body>
    <div id="app">
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Script para validaciones y modal de error -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validación del email en tiempo real
            const emailInput = document.getElementById('email');
            
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    validateEmail(this);
                });
                
                emailInput.addEventListener('blur', function() {
                    validateEmail(this);
                });
            }
            
            function validateEmail(input) {
                const email = input.value;
                const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                
                // Verificar que tenga texto antes del @
                const beforeAt = email.split('@')[0];
                // Verificar que tenga texto después del @ y un punto
                const afterAt = email.split('@')[1];
                
                if (!email) {
                    input.style.borderColor = '#C57F1B';
                    return;
                }
                
                if (!emailRegex.test(email) || !beforeAt || beforeAt.length < 1 || !afterAt || !afterAt.includes('.') || afterAt.split('.')[0].length < 1 || afterAt.split('.')[1].length < 2) {
                    input.style.borderColor = '#dc3545';
                    input.setCustomValidity('Formato de correo inválido. Debe tener texto@dominio.extensión');
                } else {
                    input.style.borderColor = '#28a745';
                    input.setCustomValidity('');
                }
            }
            
            // Modal de error
            @if ($errors->any())
                var modal = document.createElement('div');
                modal.className = 'venmodal';
                modal.innerHTML = '<div class="ventana">Usuario y/o contraseña incorrectos</div>';
                document.body.appendChild(modal);
                
                setTimeout(function() {
                    if (modal.parentNode) {
                        modal.parentNode.removeChild(modal);
                    }
                }, 3000);
            @endif
        });
    </script>
</body>
</html>

