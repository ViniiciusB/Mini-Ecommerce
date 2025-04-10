<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    public const STATUS_AWAITING_PAYMENT = 'awaiting_payment';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELED = 'canceled';

    // Define os atributos que podem ser preenchidos em massa.
    protected $fillable = [
        'user_id', // ID do usuário que fez o pedido.
        'status',  // Status do pedido.
        'subTotal', //subtotal sem desconto
        'total'   // Valor total do pedido com desconto caso tenha.
    ];
    /**
     * Retorna os itens associados a este pedido.
     *
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class); // Relacionamento com os itens do pedido.
    }
    public function getStatusOrder(){
        return [
            self::STATUS_AWAITING_PAYMENT,
            self::STATUS_PAID,
            self::STATUS_CANCELED
        ];
    }
}
