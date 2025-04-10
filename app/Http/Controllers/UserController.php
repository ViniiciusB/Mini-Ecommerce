<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Obtém as mensagens definidas no model
        $messages = User::validate([]);

        // Define as regras necessárias
        $rules = [
            'name'     => 'required|string|min:3',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'cpf'      => 'required|unique:users|cpf',
            //'is_admin' => 'boolean',
        ];
        // Cria validador usando as regras e as mensagens do model
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Dados validados
        $validatedData = $validator->validated();

        try {
            // Cria o usuário
            $user = User::create([
                'name'     => $validatedData['name'],
                'email'    => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'cpf'      => $validatedData['cpf'],
                'is_admin' => false,
            ]);

            // Gera token JWT
            $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar usuário: ' . $e->getMessage()], 422);
        }

        return response()->json([
            'user'  => $user, 'token' => $token
        ], 201);
    }


    public function login(Request $request)
    {
        // Coleta apenas as credenciais necessárias
        $credentials = $request->only(['email', 'password']);

        try {
            // Valida as credenciais do usuário e gera o token
            if (!$token = auth()->attempt($credentials)) {
                throw new \Exception('Credenciais inválidas', 422);
            }
        } catch (JWTException $e) {
            throw new \Exception('Erro ao criar token', 422);
        }

        // Resposta com sucesso do login e os detalhes do token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ,200]);
    }

    /**
     * Método para retornar os dados do usuário autenticado.
     */
    public function userLoginData()
    {
        // Recupera o usuário autenticado
        $user = auth()->user();

        // Retorna os dados do usuário
        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Método para realizar o logout do usuário autenticado.
     */
    public function logout()
    {
        // Finaliza a autenticação do usuário
        auth()->logout();
    }

    /**
     * Método para renovar o token JWT do usuário.
     */
    public function refresh()
    {
        // Retorna o novo token com as mesmas informações
        return response()->json([
            'access_token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
