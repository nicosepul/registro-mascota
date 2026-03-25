<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dueno;
use App\Models\Mascota;
use App\Models\Raza;
use Illuminate\Http\Request;

class MascotaController extends Controller
{
    // LISTAR todas las mascotas con dueño y raza
    public function index()
    {
        $mascotas = Mascota::with(['dueno', 'raza'])->get();

        return response()->json($mascotas);
    }

    // LISTAR razas para el select
    public function razas()
    {
        return response()->json(Raza::all());
    }

    // GUARDAR nueva mascota
    public function store(Request $request)
    {
        $request->validate([
            'rut' => 'required|string|max:20',
            'nombre_dueno' => 'required|string|max:255',
            'apellido_dueno' => 'required|string|max:255',
            'telefono' => 'required|string|max:25',
            'direccion' => 'required|string|max:255',
            'nombre_mascota' => 'required|string|max:255',
            'raza_id' => 'required|exists:razas,id',
            'edad' => 'required|integer|min:0',
        ]);

        // Buscar dueño por rut o crearlo
        $dueno = Dueno::firstOrCreate(
            ['rut' => $request->rut],
            [
                'nombre' => $request->nombre_dueno,
                'apellido' => $request->apellido_dueno,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]
        );

        // Si ya existía, actualizamos sus datos
        $dueno->update([
            'nombre' => $request->nombre_dueno,
            'apellido' => $request->apellido_dueno,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        // Crear mascota
        $mascota = Mascota::create([
            'dueno_id' => $dueno->id,
            'raza_id' => $request->raza_id,
            'nombre' => $request->nombre_mascota,
            'edad' => $request->edad,
        ]);

        return response()->json([
            'mensaje' => 'Mascota registrada correctamente',
            'mascota' => $mascota->load(['dueno', 'raza'])
        ], 201);
    }

    // MOSTRAR una mascota
    public function show($id)
    {
        $mascota = Mascota::with(['dueno', 'raza'])->findOrFail($id);

        return response()->json($mascota);
    }

    // ACTUALIZAR mascota
    public function update(Request $request, $id)
    {
        $request->validate([
            'rut' => 'required|string|max:20',
            'nombre_dueno' => 'required|string|max:255',
            'apellido_dueno' => 'required|string|max:255',
            'telefono' => 'required|string|max:25',
            'direccion' => 'required|string|max:255',
            'nombre_mascota' => 'required|string|max:255',
            'raza_id' => 'required|exists:razas,id',
            'edad' => 'required|integer|min:0',
        ]);

        $mascota = Mascota::findOrFail($id);
        $dueno = $mascota->dueno;

        // Actualizar dueño
        $dueno->update([
            'rut' => $request->rut,
            'nombre' => $request->nombre_dueno,
            'apellido' => $request->apellido_dueno,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        // Actualizar mascota
        $mascota->update([
            'raza_id' => $request->raza_id,
            'nombre' => $request->nombre_mascota,
            'edad' => $request->edad,
        ]);

        return response()->json([
            'mensaje' => 'Mascota actualizada correctamente',
            'mascota' => $mascota->load(['dueno', 'raza'])
        ]);
    }

    // ELIMINAR mascota
    public function destroy($id)
    {
        $mascota = Mascota::findOrFail($id);
        $mascota->delete();

        return response()->json([
            'mensaje' => 'Mascota eliminada correctamente'
        ]);
    }
}