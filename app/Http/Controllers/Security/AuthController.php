<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Crear un nuevo usuario
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        // Guardar el usuario en la base de datos
        $user->save();

        // Asignar el rol de cliente al usuario
        $user->assignRole('cliente');

        $accessToken = $user->createToken('MyApp')->accessToken;

        // Retornar una respuesta
        return response()->json(['user' => $user, 'access_token' => $accessToken], 201);

    }

    public function login(Request $request)
    {
        // Validar las credenciales
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Verificar las credenciales
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Si las credenciales son correctas, obtener el usuario
            $user = Auth::user();

            // Obtener los permisos del usuario
            $roles = $user->roles()->with('permissions')->get();

            $tokenResult = $user->createToken('MyApp');
            // Establecer la duración del token para que expire en 60 minutos
            $token = $tokenResult->token;
            $token->expires_at = now()->addMinutes(60);
            $token->save();

            // Retornar una respuesta con el token y los permisos
            return response()->json([
                'user' => $user,
                'roles' => $roles, // Roles con permisos anidados
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
            ], 200);
        } else {
            // Si las credenciales son incorrectas, enviar un mensaje de error
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Obtener el token del usuario autenticado
            $token = $request->user()->token();

            // Revocar el token
            $token->revoke();

            // Respuesta de éxito
            return response()->json(['message' => 'Se ha cerrado sesion'], 200);
        } catch (\Exception $e) {
            // Manejar la excepción y enviar una respuesta de error
            return response()->json(['error' => 'A ocurrido un error', 'detalles' => $e->getMessage()], 500);
        }
    }

}
