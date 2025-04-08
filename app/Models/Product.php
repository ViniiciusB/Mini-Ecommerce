<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa um produto na aplicação.
 *
 * @property int $id Identificador único do produto.
 * @property string $name Nome do produto.
 * @property float $price Preço do produto.
 * @property string|null $description Descrição do produto (pode ser nula).
 * @property int $stock Quantidade em estoque do produto.
 */
class Product extends Model
{
    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',        // Nome do produto.
        'price',       // Preço do produto.
        'description', // Descrição do produto.
        'stock',       // Quantidade em estoque.
    ];
}
