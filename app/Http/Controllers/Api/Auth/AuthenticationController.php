<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AuthLoginRequest;
use App\Http\Requests\V1\AuthRegisterRequest;
use App\Http\Resources\V1\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    /**
     * Metodo responsavel para cria um novo usuario
     */
    public function register(AuthRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Conta criada com sucesso.',
            'user' =>  new AuthResource($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // LOGIN
    public function login(AuthLoginRequest $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais estão incorretas.'],
            ]);
        }
        // Deleta TOKENS ANTIGOS (opcional, para manter apenas 1 sessão ativa)
        $user->tokens()->delete();
        // NOVO TOKEN
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login efetuado com sucesso.',
            'user' => new AuthResource($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout efetuado com sucesso.',
        ],204);
    }

    // PERFIL DO UTILIZADOR
    public function profile(Request $request)
    {
        return new AuthResource($request->user());
    }
}
