@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-background">
        <div class="background-overlay">
            <div class="titulo-empresa">
                <span class="titulo-linea">GRUPO</span>
                <span class="titulo-linea">PROMOCIONAL</span>
                <span class="titulo-linea">PRESTIGE</span>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email">Correo:</label>
                    <div class="input-container">
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email" 
                               autofocus
                               pattern="[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                               title="Ingrese un correo válido con formato: texto@dominio.extensión">
                        <span class="icon user-icon"></span>
                    </div>
                    <div class="validation-message" id="email-error">
                        Debe incluir @ con texto antes y después, y un punto en el dominio
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <div class="input-container">
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               minlength="4">
                        <span class="icon password-icon"></span>
                    </div>
                </div>

                <button type="submit">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</div>
@endsection
