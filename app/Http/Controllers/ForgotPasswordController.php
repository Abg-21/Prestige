<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Muestra el formulario para solicitar el enlace de restablecimiento de contraseña.
     */
    public function showRequestForm()
    {
        // Cambiado a 'auth.email' para ajustarse a la nueva ubicación de la vista
        return view('auth.email');
    }

    /**
     * Envía el enlace de restablecimiento de contraseña al correo electrónico proporcionado.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Muestra el formulario para restablecer la contraseña usando el token.
     */
    public function showResetForm($token)
    {
        // Cambiado a 'auth.reset' para ajustarse a la nueva ubicación de la vista
        return view('auth.resetpassword', ['token' => $token]);
    }

    /**
     * Restablece la contraseña del usuario.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
