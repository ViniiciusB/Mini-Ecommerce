<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder que cria ou atualiza o usuário administrador padrão.
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Executa os comandos de seed para criar ou atualizar o administrador.
     */
    public function run(): void
    {
        // Cria ou atualiza o usuário com o email especificado.
        User::updateOrCreate(
            ['email' => 'admin@teste.com'], // Verifica se existe um usuário com este email.
            [
                'name' => 'Administrador', // Define nome do administrador.
                'password' => Hash::make('admin123'), // Cria senha encriptada.
                'cpf' => '000.000.000-00', // Define CPF do administrador.
            ]
        );
    }
}
