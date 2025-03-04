<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // Configurar 'username' como identificador para el login
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            return redirect()->route('menu');
        }
        dd('Fallo al autenticar');
        return redirect()->route('login')->with('error', 'Credenciales incorrectas');
    }


    public function showLoginForm()
    {
        return view('auth.login'); // Aquí se carga la vista login.blade.php
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
