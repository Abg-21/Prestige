<?php
namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión primero.');
        }
        $notificaciones = Notificacion::where('user_id', Auth::id())->get();
        return view('menu', compact('notificaciones'));
    }
}
