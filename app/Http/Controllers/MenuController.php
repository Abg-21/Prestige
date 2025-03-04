<?php
namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::where('leido', false)->get();
        return view('auth.menu', compact('notificaciones'));
    }

}

