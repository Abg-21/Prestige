<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['logout', 'checkSession']);
        $this->middleware('guest')->only(['showLoginForm', 'login']);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Session::put('last_activity', now());

            return redirect()->route('menu')->with('success', 'Bienvenido ' . Auth::user()->name);
        }

        return back()->with('error', 'Credenciales incorrectas');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function checkSession(Request $request)
    {
        if (Session::has('last_activity')) {
            $lastActivity = Session::get('last_activity');
            $sessionTimeout = 15 * 60;

            if (now()->diffInSeconds($lastActivity) > $sessionTimeout) {
                return response()->json(['expired' => true]);
            }

            return response()->json(['expired' => false]);
        }

        return response()->json(['expired' => true]);
    }

    public function updateActivity()
    {
        Session::put('last_activity', now());
        return response()->json(['status' => 'updated']);
    }
}
