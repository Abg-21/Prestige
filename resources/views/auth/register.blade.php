<x-guest-layout>
    <head>
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    </head>

    <form method="POST" action="{{ route('register.store') }}" class="form-container">
        @csrf

        <!-- Nombre -->
        <div>
            <label for="username" class="form-label">Nombre de Usuario</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" class="form-input" required>
            @error('username') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <!-- Correo electrónico -->
        <div class="mt-4">
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required>
            @error('email') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <label for="password" class="form-label">Contraseña</label>
            <input id="password" type="password" name="password" class="form-input" required>
            @error('password') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <!-- Confirmar contraseña -->
        <div class="mt-4">
            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required>
            @error('password_confirmation') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <!-- Botón -->
        <div class="mt-6">
            <button type="submit" class="btn-primary">Registrarse</button>
        </div>
    </form>
</x-guest-layout>
