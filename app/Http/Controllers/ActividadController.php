<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividad;
use App\Models\Comision;

class ActividadController extends Controller
{
    public function create(){
        $comisiones = Comision::orderBy('name_comision')
            ->get(['id', 'name_comision', 'color_comision']);

        return view('actividades.create', compact('comisiones'));
    }
    public function store(Request $request){
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'lugar' => ['required', 'string', 'max:100'],
            'hora_fecha' => ['required', 'date'],
            'hora_finalizacion' => ['required', 'date_format:H:i'],
            'comisiones_id' => ['required', 'exists:comisions,id'],
            'descripcion' => ['nullable', 'string', 'max:200'],

        ], [
            'required' => 'Este campo es obligatorio.',
            'max' => 'Máximo :max caracteres.',
            'date' => 'Ingrese una fecha válida.',
            'date_format' => 'Ingrese una hora válida.',
            'exists' => 'Seleccione una opción válida.',
        ]);
        $data['user_id'] = auth()->id();
        Actividad::create($data);

        return redirect()->route('actividades.create')
            ->with('success', 'Actividad crada correctamente');
    }

    public function index()
    {
        $actividades = Actividad::with('comision')->get();
        return view('actividades.index', compact('actividades'));
    }
}
