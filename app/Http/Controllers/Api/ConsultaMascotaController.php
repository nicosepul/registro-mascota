<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Atencion;
use App\Models\Mascota;
use Illuminate\Http\Request;

class ConsultaMascotaController extends Controller
{
    // 1) Buscar mascota por RUT del dueño + nombre de mascota
    public function buscarPorRutYNombre(Request $request)
    {
        $request->validate([
            'rut' => 'required|string',
            'nombre_mascota' => 'required|string',
        ]);

        $mascota = Mascota::with(['dueno', 'raza'])
            ->where('nombre', $request->nombre_mascota)
            ->whereHas('dueno', function ($query) use ($request) {
                $query->where('rut', $request->rut);
            })
            ->first();

        if (!$mascota) {
            return response()->json([
                'mensaje' => 'No se encontró la mascota'
            ], 404);
        }

        return response()->json($mascota);
    }

    // 2) Buscar mascotas por RUT del dueño
    public function mascotasPorRut($rut)
    {
        $mascotas = Mascota::with(['dueno', 'raza'])
            ->whereHas('dueno', function ($query) use ($rut) {
                $query->where('rut', $rut);
            })
            ->get();

        if ($mascotas->isEmpty()) {
            return response()->json([
                'mensaje' => 'No se encontraron mascotas para este RUT'
            ], 404);
        }

        return response()->json($mascotas);
    }

    // 3) Registrar atención de mascota
public function registrarAtencion(Request $request)
{
    $request->validate([
        'mascota_id' => 'required|exists:mascotas,id',
        'fecha_atencion' => 'required|date',
        'motivo_consulta' => 'required|string|max:255',
        'sintomas' => 'nullable|string',
        'diagnostico' => 'nullable|string',
        'tratamiento' => 'nullable|string',
        'observaciones' => 'nullable|string',
        'atendido' => 'required|boolean',
    ]);

    $atencion = Atencion::create([
        'mascota_id' => $request->mascota_id,
        'fecha_atencion' => $request->fecha_atencion,
        'motivo_consulta' => $request->motivo_consulta,
        'sintomas' => $request->sintomas,
        'diagnostico' => $request->diagnostico,
        'tratamiento' => $request->tratamiento,
        'observaciones' => $request->observaciones,
        'atendido' => $request->atendido,
    ]);

    return response()->json([
        'mensaje' => 'Atención registrada correctamente',
        'atencion' => $atencion
    ], 201);
}

    // 4) Ver historial de atenciones de una mascota
public function verAtenciones($mascota_id)
{
    $atenciones = \App\Models\Atencion::where('mascota_id', $mascota_id)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($atenciones);
}
}