<?php

namespace App\Providers;

use Brazanation\Documents\Cpf;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra qualquer serviço de aplicação.
     */
    public function register(): void
    {
        //
    }

    /**
     * Inicializa qualquer serviço de aplicação.
     */
    public function boot(): void
    {
        // Registrando uma regra de validação personalizada para CPF.
        // Utiliza a biblioteca Brazanation para validar o CPF.
        Validator::extend('cpf', function ($attribute, $value) {
            try {
                $cpf = new Cpf($value); // Instancia um novo CPF com o valor informado.
                return $cpf->isValid($cpf); // Verifica se o CPF é válido.
            } catch (Exception $e) {
                // Retorna false caso ocorra uma exceção na validação.
                return false;
            }
        });
    }
}


