<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComisionController extends Controller
{
    public function index()
    {
        $comisiones = Comision::with('docentes')->get();
        $todosLosDocentes = User::orderBy('name_one')->get();
        return view('comisiones.index', compact('comisiones', 'todosLosDocentes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_comision'  => 'required|string|max:75|unique:comisions,name_comision',
            'color_comision' => 'required|string|max:8',
            'ciclo_comision' => 'required|numeric|digits:4', // Validamos que sea un año (4 dígitos)
            'docentes'       => 'required|array|min:1',
            'lider_id'       => 'required|exists:users,id',
        ], [
            'name_comision.required' => 'El nombre de la comisión es obligatorio.',
            'name_comision.unique'   => 'Ya existe una comisión registrada con este nombre.',
            'ciclo_comision.required'=> 'El año del ciclo es obligatorio.',
            'ciclo_comision.digits'  => 'El ciclo debe ser un año válido (ej. 2026).',
            'docentes.required'      => 'Debes seleccionar al menos un docente.',
            'lider_id.required'      => 'Debes asignar un líder a la comisión.'
        ]);

        // TRUCO: Convertimos el año (2026) en una fecha válida para MySQL (2026-01-01)
        $fechaCiclo = $data['ciclo_comision'] . '-01-01';

        DB::transaction(function () use ($data, $fechaCiclo) {
            $comision = Comision::create([
                'name_comision'  => $data['name_comision'],
                'color_comision' => $data['color_comision'],
                'ciclo_comision' => $fechaCiclo,
                'lider_id'       => $data['lider_id'],
            ]);
            $comision->docentes()->sync($data['docentes']);
        });

        return redirect()->route('comisiones.index')->with('success', 'Comisión creada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $comision = Comision::findOrFail($id);

        $data = $request->validate([
            'name_comision'  => 'required|string|max:75|unique:comisions,name_comision,' . $id,
            'color_comision' => 'required|string|max:8',
            'ciclo_comision' => 'required|numeric|digits:4', 
            'docentes'       => 'required|array|min:1',
            'lider_id'       => 'required|exists:users,id',
        ], [
            'name_comision.required' => 'El nombre de la comisión es obligatorio.',
            'name_comision.unique'   => 'Ya existe una comisión registrada con este nombre.',
            'ciclo_comision.required'=> 'El año del ciclo es obligatorio.',
            'ciclo_comision.digits'  => 'El ciclo debe ser un año válido (ej. 2026).',
            'docentes.required'      => 'Debes seleccionar al menos un docente.',
            'lider_id.required'      => 'Debes asignar un líder a la comisión.'
        ]);

        $fechaCiclo = $data['ciclo_comision'] . '-01-01';

        DB::transaction(function () use ($comision, $data, $fechaCiclo) {
            $comision->update([
                'name_comision'  => $data['name_comision'],
                'color_comision' => $data['color_comision'],
                'ciclo_comision' => $fechaCiclo,
                'lider_id'       => $data['lider_id'],
            ]);
            $comision->docentes()->sync($data['docentes']);
        });

        return redirect()->route('comisiones.index')->with('success', 'Comisión actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $comision = Comision::findOrFail($id);
        
        if ($comision->actividades()->exists()) {
            return redirect()->route('comisiones.index')
                ->with('error', 'No se puede eliminar: la comisión tiene actividades registradas.');
        }
        
        $comision->docentes()->detach();
        $comision->delete();

        return redirect()->route('comisiones.index')->with('success', 'La comisión ha sido eliminada de forma permanente.');
    }
}