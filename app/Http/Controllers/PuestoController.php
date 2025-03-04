<?php
namespace App\Http\Controllers;

use App\Models\Giro;
use App\Models\Cliente;
use App\Models\Puesto;
use Illuminate\Http\Request;

class PuestoController extends Controller
{
    public function index()
    {
        $puestos = Puesto::with(['giro', 'cliente'])->get();
        return view('puestos.puesto', compact('puestos'));
    }

    public function create()
    {
        $giros = Giro::all();
        $clientes = Cliente::all();
        return view('puestos.create_puesto', compact('giros', 'clientes'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Categoría' => 'required|in:Promovendedor,Promotor,Supervisor,Otro',
            'Puesto' => 'required|max:45',
            'id_GiroPuestoFK' => 'required|exists:giros,idGiros',
            'id_ClientePuestoFK' => 'required|exists:clientes,idClientes',
            'Zona' => 'required|max:30',
            'Estado' => 'required|in:Aguascalientes,Baja California,Baja California Sur,Campeche,Chiapas,Chihuahua,Ciudad de México,Coahuila,Colima,Durango,Guanajuato,Guerrero,Hidalgo,Jalisco,México,Michoacán,Morelos,Nayarit,Nuevo León,Oaxaca,Puebla,Querétaro,Quintana Roo,San Luis Potosí,Sinaloa,Sonora,Tabasco,Tamaulipas,Tlaxcala,Veracruz,Yucatán,Zacatecas',
            'Edad' => 'required|array',
            'Edad.*' => 'in:18-23,24-30,31-35,36-42,43-51,52-60',
            'Escolaridad' => 'required|in:Primaria,Secundaria terminada,Bachillerato trunco,Bachillerato terminado,Técnico superior,Licenciatura trunca,Licenciatura terminada,Postgrado',
            'Experiencia' => 'required|max:40',
            'Conocimientos' => 'nullable|array',
            'Funciones' => 'nullable|array',
            'Habilidades' => 'nullable|array',
        ]);

        // Convertir arrays a cadenas
        $validatedData['Edad'] = $request->input('Edad') ? implode(', ', $request->input('Edad')) : null;
        $validatedData['Conocimientos'] = $request->input('Conocimientos') ? implode(', ', $request->input('Conocimientos')) : null;
        $validatedData['Funciones'] = $request->input('Funciones') ? implode(', ', $request->input('Funciones')) : null;
        $validatedData['Habilidades'] = $request->input('Habilidades') ? implode(', ', $request->input('Habilidades')) : null;

        // Crear el registro
        Puesto::create($validatedData);

        return redirect()->route('puestos.index')->with('success', 'Puesto creado exitosamente.');
    }


    public function edit(Puesto $puesto)
    {
        $giros = Giro::all();
        $clientes = Cliente::all();

        // Decodificar JSON antes de enviarlo a la vista
        $puesto->Conocimientos = json_decode($puesto->Conocimientos, true) ?? [];
        $puesto->Funciones = json_decode($puesto->Funciones, true) ?? [];
        $puesto->Habilidades = json_decode($puesto->Habilidades, true) ?? [];

        return view('puestos.edit_puesto', compact('puesto', 'giros', 'clientes'));
    }



    public function update(Request $request, Puesto $puesto)
    {
        $validatedData = $request->validate([
            'Categoría' => 'required|in:Promovendedor,Promotor,Supervisor,Otro',
            'Puesto' => 'required|max:45',
            'id_GiroPuestoFK' => 'required|exists:giros,idGiros',
            'id_ClientePuestoFK' => 'required|exists:clientes,idClientes',
            'Zona' => 'required|max:30',
            'Estado' => 'required|in:Aguascalientes,Baja California,Baja California Sur,Campeche,Chiapas,Chihuahua,Ciudad de México,Coahuila,Colima,Durango,Guanajuato,Guerrero,Hidalgo,Jalisco,México,Michoacán,Morelos,Nayarit,Nuevo León,Oaxaca,Puebla,Querétaro,Quintana Roo,San Luis Potosí,Sinaloa,Sonora,Tabasco,Tamaulipas,Tlaxcala,Veracruz,Yucatán,Zacatecas',
            'Edad' => 'required|array',
            'Edad.*' => 'in:18-23,24-30,31-35,36-42,43-51,52-60',
            'Escolaridad' => 'required|in:Primaria,Secundaria terminada,Bachillerato trunco,Bachillerato terminado,Técnico superior,Licenciatura trunca,Licenciatura terminada,Postgrado',
            'Experiencia' => 'required|max:40',
            'Conocimientos' => 'nullable|array|max:8',
            'Funciones' => 'nullable|array|max:8',
            'Habilidades' => 'nullable|array|max:8',
        ]);

        // Convertir listas a texto antes de guardar
        $validatedData['Edad'] = $request->Edad ? implode(', ', $request->Edad) : null;
        $validatedData['Conocimientos'] = json_encode($request->Conocimientos ?? []);
        $validatedData['Funciones'] = json_encode($request->Funciones ?? []);
        $validatedData['Habilidades'] = json_encode($request->Habilidades ?? []);

        $puesto->update($validatedData);

        return redirect()->route('puestos.index')->with('success', 'Puesto actualizado exitosamente.');
    }


    public function destroy(Puesto $puesto)
    {
        $puesto->delete();
        return redirect()->route('puestos.index')->with('success', 'Puesto eliminado exitosamente.');
    }
}