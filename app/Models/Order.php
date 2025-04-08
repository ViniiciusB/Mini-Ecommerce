<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe que representa um Pedido na aplicação.
 *
 * @property int $id Identificador único do pedido.
 * @property int $user_id ID do usuário associado ao pedido.
 * @property string $status Status atual do pedido (ex: pendente, concluído, cancelado).
 * @property float $total Valor total do pedido.
 */
class Order extends Model
{
    // Define os atributos que podem ser preenchidos em massa.
    protected $fillable = [
        'user_id', // ID do usuário que fez o pedido.
        'status',  // Status do pedido.
        'total',   // Valor total do pedido.
    ];

    /**
     * Retorna os itens associados a este pedido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class); // Relacionamento com os itens do pedido.
    }
}
