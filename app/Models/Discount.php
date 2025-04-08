<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Representa um modelo de desconto na aplicação.
 *
 * @property int $id ID do desconto.
 * @property float $min_total Valor mínimo para ativação do desconto.
 * @property float $discount_percent Percentual de desconto aplicado.
 */
class Discount extends Model
{
    // Define a tabela "discounts" para o modelo.
    protected $table = 'discounts';

    // Define os atributos que podem ser preenchidos em massa.
    protected $fillable = [
        'min_total', // Valor mínimo necessário para aplicação do desconto.
        'discount_percent' // Percentual do desconto que será aplicado.
    ];
}


