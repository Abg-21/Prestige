<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use PDF; // Asegúrate de importar la clase PDF

class ContratoController extends Controller
{
    // Muestra un listado de empleados para seleccionar y generar contrato
    public function index()
    {
        $empleados = Empleado::all();
        return view('contratos.index', compact('empleados'));
    }

    // Muestra en pantalla el contrato generado para un empleado
    public function show($id)
    {
        $empleado = Empleado::findOrFail($id);
        // Aquí se sustituye la plantilla con los datos del empleado.
        // Puedes ajustar la vista "contratos.show" según tus necesidades.
        return view('contratos.show', compact('empleado'));
    }

    // Genera el PDF del contrato y lo descarga
    public function download($id)
    {
        $empleado = Empleado::findOrFail($id);
        // Usamos la vista "contratos.contract" para generar el PDF
        $pdf = PDF::loadView('contratos.contract', compact('empleado'));
        return $pdf->download('contrato_' . $empleado->idEmpleado . '.pdf');
    }
}
