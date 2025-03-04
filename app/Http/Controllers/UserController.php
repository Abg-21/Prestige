<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrador');
    }

    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all(); // Para el modal
        return view('usuarios.user', compact('users', 'roles'));
    }
    public function create()
    {
        $roles = Role::all(); // Obtener los roles para asignarlos al usuario
        return view('usuarios.create_user', compact('roles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',  // Aquí se asegura de que el rol exista en la base de datos
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $user->assignRole($request->role);  // Asignar rol
        
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
        
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('usuarios.edit_user', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('usuarios.index')->with('success', 'Rol actualizado correctamente.');
    }
}

