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

    // VALIDAR si el RUT del dueño existe
    public function existeRut(string $rut)
    {
        $rutNormalizado = $this->normalizarRut($rut);

        if (!$this->esRutValido($rutNormalizado)) {
            return response()->json([
                'mensaje' => 'El RUT ingresado no es válido'
            ], 422);
        }

        $dueno = $this->buscarDuenoPorRutNormalizado($rutNormalizado);

        if (!$dueno) {
            return response()->json([
                'existe' => false,
                'mensaje' => 'RUT no registrado'
            ]);
        }

        return response()->json([
            'existe' => true,
            'dueno' => [
                'rut' => $dueno->rut,
                'nombre' => $dueno->nombre,
                'apellido' => $dueno->apellido,
                'telefono' => $dueno->telefono,
                'direccion' => $dueno->direccion,
            ]
        ]);
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

        $rutNormalizado = $this->normalizarRut($request->rut);
        if (!$this->esRutValido($rutNormalizado)) {
            return response()->json([
                'mensaje' => 'El RUT ingresado no es válido'
            ], 422);
        }

        $dueno = $this->buscarDuenoPorRutNormalizado($rutNormalizado);

        if (!$dueno) {
            $dueno = Dueno::create([
                'rut' => $rutNormalizado,
                'nombre' => $request->nombre_dueno,
                'apellido' => $request->apellido_dueno,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);
        } else {
            $dueno->update([
                'nombre' => $request->nombre_dueno,
                'apellido' => $request->apellido_dueno,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);
        }

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

        $rutNormalizado = $this->normalizarRut($request->rut);
        if (!$this->esRutValido($rutNormalizado)) {
            return response()->json([
                'mensaje' => 'El RUT ingresado no es válido'
            ], 422);
        }

        $mascota = Mascota::findOrFail($id);
        $dueno = $this->buscarDuenoPorRutNormalizado($rutNormalizado);

        if (!$dueno) {
            $dueno = Dueno::create([
                'rut' => $rutNormalizado,
                'nombre' => $request->nombre_dueno,
                'apellido' => $request->apellido_dueno,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);
        } else {
            $dueno->update([
                'nombre' => $request->nombre_dueno,
                'apellido' => $request->apellido_dueno,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);
        }

        // Actualizar mascota
        $mascota->update([
            'dueno_id' => $dueno->id,
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

    private function buscarDuenoPorRutNormalizado(string $rutNormalizado): ?Dueno
    {
        return Dueno::whereRaw("REPLACE(REPLACE(UPPER(rut), '.', ''), '-', '') = ?", [$rutNormalizado])->first();
    }
}