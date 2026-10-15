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
        $comisiones = Comision::with('docentes')->get();
        return view('comision.index', compact('comisiones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $comisiones = Comision::all();
        $docentes = User::all();
        return view('comision.create', compact('comisiones', 'docentes'));
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
    $comisionFinal->docentes()->attach($request->docentes);

    return redirect()->route('comisiones.index')
        ->with('success', 'Comisión creada exitosamente.');
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:75',
            'color' => 'required|string|max:8',
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
    public function edit(Comision $comision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comision $comision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comision $comision)
    {
        //
    }
}
