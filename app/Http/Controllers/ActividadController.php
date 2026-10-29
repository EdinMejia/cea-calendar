<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividad;
use App\Models\Comision;
use Illuminate\Validation\Rule;

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
            'hora_finalizacion' => [
                'required',
                'date_format:H:i',
                function ($_attribute, $value, $fail) use ($request) {
                    $inicio = date('H:i', strtotime($request->hora_fecha));
                    if ($value <= $inicio) {
                        $fail('La hora de finalización debe ser posterior a la de inicio.');
                    }
                },
                ],
            'comisiones_id' => ['required', Rule::exists('comisions', 'id')],
            'descripcion' => ['nullable', 'string', 'max:200'],

        ], [
            'required' => 'Este campo es obligatorio.',
            'max' => 'Máximo :max caracteres.',
            'date' => 'Ingrese una fecha válida.',
            'date_format' => 'Ingrese una hora válida.',
            'exists' => 'Seleccione una opción válida.',
        ]);
        $data['user_id'] = $request->user()->id;

        Actividad::create($data);

        return redirect()->route('actividades.create')
            ->with('success', 'Actividad creada correctamente');
    }

    public function index()
    {
        $actividades = Actividad::with('comision')
        ->ordenBy('hora_fecha')
        ->get();
        return view('actividades.index', compact('actividades'));
    }

    public function historial()
    {
        $actividades = Actividad::with('comision')
            ->pasadas() 
            ->orderBy('hora_fecha', 'desc') 
            ->get();

        return view('actividades.historial', compact('actividades'));
    }

    public function show($id)
    {
    // Buscamos la actividad con su comisión asociada
    $actividad = Actividad::with('comision')->findOrFail($id);
    
    return view('actividades.show', compact('actividad'));
    }

    public function edit($id)
    {
    $actividad = Actividad::findOrFail($id);
    $comisiones = Comision::all(); // Traemos todas las comisiones para el <select>
    
    return view('actividades.edit', compact('actividad', 'comisiones'));
    }

    public function update(Request $request, $id)
    {
    // Validamos los datos entrantes
    $request->validate([
        'nombre'            => 'required|string|max:100',
        'lugar'             => 'required|string|max:100',
        'comisiones_id'     => 'required|exists:comisions,id',
        'hora_fecha'        => 'required',
        'hora_finalizacion' => 'required',
        'descripcion'       => 'nullable|string',
    ]);

    $actividad = Actividad::findOrFail($id);
    
    // Actualizamos la base de datos
    $actividad->update($request->all());

    // Redirigimos a la pantalla de detalles ("show") con mensaje de éxito
    return redirect()->route('actividades.show', $actividad->id)
            ->with('success', 'Actividad actualizada correctamente.');
    }

    public function destroy($id)
    {
    // Buscamos la actividad por su ID
    $actividad = Actividad::findOrFail($id);
    
    // La eliminamos de la base de datos
    $actividad->delete();

    // Redirigimos al inicio (dashboard) activando tu alerta verde de éxito
    return redirect()->route('dashboard')
            ->with('success', 'La actividad fue eliminada correctamente.');
    }
}
