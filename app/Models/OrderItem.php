<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Classe que representa um item de pedido na aplicação.
 *
 * @property int $id Identificador único do item.
 * @property int $order_id ID do pedido ao qual o item pertence.
 * @property int $product_id ID do produto associado ao item.
 * @property int $quantity Quantidade do produto no pedido.
 * @property float $price Preço do item no pedido.
 */
class OrderItem extends Model
{
    // Define os atributos que podem ser preenchidos em massa.
    protected $fillable = [
        'order_id',   // Relaciona o item ao pedido.
        'product_id', // Identifica o produto deste item.
        'quantity',   // Quantidade comprada do produto.
        'price',      // Preço unitário do produto no pedido.
    ];

    /**
     * Define a relação do item de pedido com o produto.
     *
     * @return BelongsTo Relação com o modelo Product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class); // Liga o item ao produto.
    }
}
