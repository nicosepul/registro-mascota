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

        $rutNormalizado = $this->normalizarRut($request->rut);
        if (!$this->esRutValido($rutNormalizado)) {
            return response()->json([
                'mensaje' => 'El RUT ingresado no es válido'
            ], 422);
        }

        $mascota = Mascota::with(['dueno', 'raza'])
            ->where('nombre', $request->nombre_mascota)
            ->whereHas('dueno', function ($query) use ($rutNormalizado) {
                $query->whereRaw("REPLACE(REPLACE(UPPER(rut), '.', ''), '-', '') = ?", [$rutNormalizado]);
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
        $rutNormalizado = $this->normalizarRut($rut);
        if (!$this->esRutValido($rutNormalizado)) {
            return response()->json([
                'mensaje' => 'El RUT ingresado no es válido'
            ], 422);
        }

        $mascotas = Mascota::with(['dueno', 'raza'])
            ->whereHas('dueno', function ($query) use ($rutNormalizado) {
                $query->whereRaw("REPLACE(REPLACE(UPPER(rut), '.', ''), '-', '') = ?", [$rutNormalizado]);
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

    private function normalizarRut(string $rut): string
    {
        return strtoupper(str_replace(['.', '-'], '', trim($rut)));
    }

    private function esRutValido(string $rutNormalizado): bool
    {
        if (!preg_match('/^[0-9]{7,8}[0-9K]$/', $rutNormalizado)) {
            return false;
        }

        $cuerpo = substr($rutNormalizado, 0, -1);
        $dvIngresado = substr($rutNormalizado, -1);

        $suma = 0;
        $multiplicador = 2;

        for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
            $suma += (int) $cuerpo[$i] * $multiplicador;
            $multiplicador = $multiplicador === 7 ? 2 : $multiplicador + 1;
        }

        $resto = 11 - ($suma % 11);
        $dvEsperado = $resto === 11 ? '0' : ($resto === 10 ? 'K' : (string) $resto);

        return $dvIngresado === $dvEsperado;
    }
}