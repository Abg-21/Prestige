<x-guest-layout>
    <head>
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    </head>

    <div class="container">
        <h1>Verifica tu correo electrónico</h1>
        <p>Te hemos enviado un enlace de verificación a tu correo.</p>
        <p>Si no recibiste el correo, puedes solicitar otro.</p>

        @if (session('status') == 'verification-link-sent')
            <p class="text-success">Se ha enviado un nuevo enlace a tu correo electrónico.</p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Reenviar enlace</button>
        </form>
    </div>
</x-guest-layout>
