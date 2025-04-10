<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware para verificação de acesso de administradores.
 *
 * Este middleware verifica se o usuário autenticado é um administrador.
 * Caso contrário, retorna uma resposta JSON com mensagem de erro e código 403.
 */
class AdminMiddleware
{
    /**
     * Lida com a solicitação HTTP e verifica se o usuário atual é um administrador.
     *
     * @param Request $request A solicitação HTTP recebida.
     * @param Closure $next O próximo middleware na pipeline.
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user(); // Obtém o usuário autenticado.

        // Verifica se o usuário não está autenticado ou não é administrador.
        if (!$user || !$user->is_admin) {
            return response()->json(['error' => 'Acesso negado. Apenas administradores têm permissão.'], 422);
        }

        return $next($request); // Continua para o próximo middleware ou controlador.
    }
}
