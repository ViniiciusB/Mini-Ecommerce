<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',   // Nome do usuário.
        'email',  // Email do usuário.
        'password', // Senha do usuário.
        'cpf',    // CPF do usuário.
        'is_admin'   // Define se o usuário é administrador.
    ];

    /**
     * Atributos que devem ser ocultados na serialização.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',       // Senha não deve ser visível.
        'remember_token', // Token de "lembrar-me".
    ];

    /**
     * Atributos que devem ser convertidos para tipos nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Converte o campo de verificação de email para datetime.
            'password' => 'hashed',           // A senha será automaticamente encriptada.
        ];
    }

    /**
     * Validação dos dados do usuário em português.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public static function validate(array $data): array
    {
        $messages = [
            'required' => 'O campo :attribute é obrigatório.',
            'email' => 'O :attribute deve ser um endereço de e-mail válido.',
            'unique' => 'Este :attribute já está sendo utilizado.',
            'min' => 'O campo :attribute deve ter pelo menos :min caracteres.',
            'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
        ];
        return $messages;
    }

    /**
     * Obtém o identificador único do JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Retorna a chave primária do usuário.
    }

    /**
     * Retorna um array de claims personalizados do JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return []; // Sem claims personalizadas neste exemplo.
    }
}
