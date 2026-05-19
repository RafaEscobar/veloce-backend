<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Controlador para la gestión de autenticación de la API.
 *
 * Proporciona métodos para registrar nuevos usuarios, iniciar sesión y cerrar sesión,
 * gestionando los tokens de acceso de los usuarios mediante Sanctum.
 * @group Autenticación
 */
class AuthController extends Controller
{
    /**
     * Registra un nuevo usuario en el sistema.
     *
     * Crea un nuevo registro de usuario en la base de datos, encripta su contraseña
     * y genera un token de acceso personal (PAT) para el nuevo usuario.
     *
     * @bodyParam name string required Nombre del usuario. Example: Alejandro
     * @bodyParam last_name string required Apellidos del usuario. Example: Ramirez
     * @bodyParam email string required Correo electrónico del usuario. Example: alejandro1@gmail.com
     * @bodyParam password string required Contraseña del usuario. Example: secret123
     *
     * @param StoreRegisterRequest $request Objeto de petición que contiene los datos validados del usuario.
     * @return JsonResponse Respuesta en formato JSON con los datos del usuario creado y el token de acceso generado. Retorna un código HTTP 201.
     */
    public function register(StoreRegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = new User();
        $user->name = $validated['name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Inicia sesión para un usuario existente en la aplicación.
     *
     * Verifica las credenciales de acceso (email y contraseña). Si son válidas,
     * autentica al usuario y genera un nuevo token de acceso. Si las credenciales
     * son incorrectas, lanza una excepción de validación.
     *
     * @bodyParam email string required Correo electrónico del usuario. Example: alejandro@example.com
     * @bodyParam password string required Contraseña del usuario. Example: secret123
     *
     * @param LoginRequest $request Objeto de petición que contiene las credenciales de inicio de sesión validadas.
     * @return JsonResponse Respuesta en formato JSON con los datos del usuario y su nuevo token de acceso.
     * @throws ValidationException Si las credenciales proporcionadas no son correctas.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Cierra la sesión del usuario autenticado actualmente.
     * @authenticated
     * Revoca y elimina todos los tokens de acceso personal asociados al usuario,
     * invalidando así cualquier sesión activa generada previamente con la API.
     *
     * @param Request $request La petición HTTP actual que proporciona la instancia del usuario autenticado.
     * @return JsonResponse Respuesta en formato JSON con un mensaje confirmando el cierre de sesión exitoso.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }
}
