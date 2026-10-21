<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use Illuminate\Http\Request;
use App\Models\User;

class ComisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comisiones = Comision::has('docentes')->get();
        return view('comisiones.index', compact('comisiones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $comisiones = Comision::all();
        $docentes = User::all();
        return view('comisiones.create', compact('comisiones', 'docentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'docentes' => 'required|array', 
            'comision_id' => 'required', 
            'ciclo' => 'required|date',
        ], [
            'docentes.required' => 'Debes selecionar al menos un docente de la lista', 
            'comision_id.required' => 'Debes seleccionar una comision.', 
            'ciclo.required' => 'La fecha del ciclo es obligatoria'
        ]);

        if ($request->comision_id === 'nueva'){
            $request->validate([
                'nueva_comision_nombre' => 'required|string|max:255', 
                'color_nueva_comision'=>'required|string', 
            ]);
        $comision = Comision::create([
            'name_comision' => $request->nueva_comision_nombre,
            'color_comision' => $request->color_nueva_comision,
            'ciclo_comision' => $request->ciclo,
        ]);
        $idDeLacomision = $comision->id;
    } else {
        $idDeLacomision = $request->comision_id;
    }
    $comisionFinal = Comision::findOrFail($idDeLacomision);
    $resultado = $comisionFinal->docentes()->syncWithoutDetaching($request->docentes); 

    if (count($resultado['attached']) < count($request->docentes)) {
        return redirect()->route('comisiones.index')
            ->with('warning', 'Se guardo correctamente, pero algunos docentes fueron omitidos porque ya preexistian en la comision'); 
    }

    return redirect()->route('comisiones.index')
        ->with('success', 'Comisión creada exitosamente.');
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:75|unique:comisions,name_comision',
            'color' => 'required|string|max:8',
        ], [
            'nombre.requires' => 'El nombre de la comision no puede esta vacio',
            'nombre.unique' => 'Ya existe una comision con ese nombre',
            'color.required' => 'No olvides el color de la comision',
        ]); 

        $comision = Comision::create([
            'name_comision' => $request->nombre,
            'color_comision' => $request->color,
            'ciclo_comision' => date('Y-m-d'),
        ]);

        return response()->json([
            'success' => true,
            'comision' => [
                'id' => $comision->id, 
                'name_comision' => $comision->name_comision,
                'color_comision' => $comision->color_comision,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comision $comision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $comision = Comision::with('docentes')->findOrFail($id);
        $docentes = \App\Models\User::all();

        return view('comisiones.edit', compact('comision', 'docentes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'docentes' => 'required|array',
            'ciclo' => 'required|date',
            'name_comision' => 'required|string|max:75|unique:comisions,name_comision,' .$id,
            'color_comision' => 'required|string|max:8',
        ], [
            'name_comision.required' => 'El nombre de la comisión es obligatorio.',
            'name_comision.unique'   => 'Ya existe una comisión registrada con este nombre.',
            'name_comision.max'      => 'El nombre no puede superar los 255 caracteres.',

            'ciclo_comision.required' => 'Por favor, selecciona o escribe el ciclo.',
            'color_comision.required' => 'Debes elegir un color para identificar la comisión.'

        ]);

        $comision = Comision::findOrFail($id);
        $comision->update([
            'name_comision' => $request->name_comision,
            'color_comision' => $request->color_comision,
            'ciclo_comision' => $request->ciclo,
        ]);

        $comision->docentes()->sync($request->docentes); 
        return redirect()->route('comisiones.index')
            ->with('success', 'Comision actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comision = Comision::findOrFail($id);
        $comision->docentes()->detach();

        return redirect()->route('comisiones.index')
            ->with('success', 'La comision ha sido eliminada de forma permamente');
    }
}
