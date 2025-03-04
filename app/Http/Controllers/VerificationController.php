<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Muestra la vista de notificación de verificación de correo.
     */
    public function show()
    {
        return view('auth.verify-email');
    }

    /**
     * Maneja la verificación del correo electrónico.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('dashboard')->with('status', 'Correo verificado exitosamente.');
    }

    /**
     * Reenvía el enlace de verificación.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'El enlace de verificación ha sido enviado.');
    }
}

