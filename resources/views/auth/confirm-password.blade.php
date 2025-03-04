<x-guest-layout>
    <head>
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    </head>

    <div class="text-container">
        <p>Por seguridad, confirma tu contraseña para continuar.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="form-container">
        @csrf

        <!-- Contraseña -->
        <div>
            <label for="password" class="form-label">Contraseña</label>
            <input id="password" type="password" name="password" class="form-input" required>
            @error('password') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <!-- Botón -->
        <div class="mt-6">
            <button type="submit" class="btn-primary">Confirmar contraseña</button>
        </div>
    </form>
</x-guest-layout>
