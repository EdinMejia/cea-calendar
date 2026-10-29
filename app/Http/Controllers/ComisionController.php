<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class ComisionController extends Controller
{
    public function index()
    {
        $comisiones = Comision::has('docentes')->get();
        return view('comisiones.index', compact('comisiones'));
    }

    public function create()
    {
        $comisiones = Comision::all();
        $docentes = User::all();
        return view('comisiones.create', compact('comisiones', 'docentes'));
    }

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
                'nueva_comision_nombre' => 'required|string|max:75|unique:comisions,name_comision',
                'color_nueva_comision'=>'required|string|max:8',
            ], [
                'nueva_comision_nombre.required' => 'Escriba el nombre de la nueva comision',
                'nueva_comision_nombre.unique'   => 'Ya existe una comision con ese nombre',
                'nueva_comision_nombre.max'      => 'El nombre no puede superar los :max caracteres.',
                'color_nueva_comision.required'  => 'Elige un color para la nueva comisión.',
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
            ->with('warning', 'Se guardo correctamente, pero algunos docentes fueron omitidos porque ya exitian en la comision');
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
            'nombre.required' => 'El nombre de la comision no puede esta vacio',
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

    public function show(Comision $comision)
    {
        //
    }


    public function edit($id)
    {
        $comision = Comision::with('docentes')->findOrFail($id);
        $docentes = \App\Models\User::all();

        return view('comisiones.edit', compact('comision', 'docentes'));
    }

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
            'name_comision.max'      => 'El nombre no puede superar los :max letras.',
            'docentes.required'      => 'Debe seleccionar al menos un docente',

            'ciclo.required' => 'Por favor, seleccione el ciclo.',
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

    public function destroy($id)
    {
        $comision = Comision::findOrFail($id);
        if ($comision->actividades()->exists()) {
            return redirect()->route('comisiones.index')
                ->with('error', 'no se puede eliminar: la comision tiene actividades registradas.');
        }
        $comision->docentes()->detach();
        $comision->delete();

        return redirect()->route('comisiones.index')
            ->with('success', 'La comision ha sido eliminada de forma permamente');
    }
}
