<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use function Laravel\Prompts\password;

/**
 * Classe responsável pelo gerenciamento de usuários, incluindo registro, login, informações do usuário,
 * logout e renovação de token.
 */
class UserController extends Controller
{
    /**
     * Método para registrar um novo usuário.
     * Valida a requisição, cria o usuário e retorna um token JWT.
     */
    public function register(StoreUserRequest $request)
    {
        try {
            // Criação do usuário baseado nos dados do request
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hash da senha para segurança
                'cpf' => $request->cpf,
                'is_admin' => $data['is_admin'] ?? false, // Adiciona admin com valor padrão false
            ]);
        } catch (\Exception $e) {
            // Retorna erro em caso de falha na criação do usuário
            return response()->json(['error' => 'Erro ao criar usuário: ' . $e->getMessage()], 500);
        }

        try {
            // Geração do token JWT para o usuário criado
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            // Retorna erro caso a geração do token falhe
            return response()->json(['error' => 'Erro ao gerar token: ' . $e->getMessage()], 500);
        }

        // Resposta com sucesso do registro e o token gerado
        return response()->json([
            'message' => 'Usuário registrado com sucesso!',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Método para autenticar um usuário e gerar token de acesso.
     */
    public function login(Request $request)
    {
        // Coleta apenas as credenciais necessárias
        $credentials = $request->only(['email', 'password']);

        try {
            // Valida as credenciais do usuário e gera o token
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Credenciais inválidas'], 401);
            }
        } catch (JWTException $e) {
            // Retorna erro caso a geração do token falhe
            return response()->json(['error' => 'Erro ao criar o token: ' . $e->getMessage()], 500);
        }

        // Resposta com sucesso do login e os detalhes do token
        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Método para retornar os dados do usuário autenticado.
     */
    public function me()
    {
        // Recupera o usuário autenticado
        $user = auth()->user();

        // Retorna os dados do usuário
        return response()->json([
            'message' => 'Seus dados de usuário',
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
        return response()->json(['message' => 'Logout realizado com sucesso!']);
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
