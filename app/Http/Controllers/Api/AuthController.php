<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credenciales)) {
            return response()->json([
                'mensaje' => 'Credenciales incorrectas'
            ], 401);
        }

        $usuario = Auth::user();

        return response()->json([
            'mensaje' => 'Login exitoso',
            'usuario' => $usuario
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            'mensaje' => 'Sesión cerrada correctamente'
        ]);
    }
}