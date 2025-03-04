<x-guest-layout>
    <head>
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    </head>

    <form method="POST" action="{{ route('password.email') }}" class="form-reset">
        @csrf
        <div>
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required>
            @error('email') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="mt-6">
            <button type="submit" class="btn-primary">Enviar enlace de restablecimiento</button>
        </div>
    </form>
</x-guest-layout>
