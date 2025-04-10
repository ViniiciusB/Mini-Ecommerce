<?php

/**
 * Class OrderController
 *
 * Controla as ordens de serviço.
 */

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderController extends Controller
{
    /**
     * Cria um novo pedido com os itens fornecidos.
     */
    public function store(Request $request)
    {
        try {
            // Obter os dados básicos do pedido
            $userId = $request->input('user_id');
            $itemsData = $request->input('items');

            DB::beginTransaction();

            // Criar um novo pedido com status inicial
            $order = Order::create([
                'user_id' => $userId,
                'status' => Order::STATUS_AWAITING_PAYMENT,
                'subTotal' => 0,
                'total' => 0
            ]);
            $subtotal = 0;

            // Processar cada item do pedido
            foreach ($itemsData as $item) {
                // Buscar o produto
                $product = Product::findOrFail($item['product_id']);

                // Verificar o estoque disponível
                if ($product->stock < $item['quantity']) {
                    throw new Exception("Estoque insuficiente para o produto $product->name.");
                }

                // Atualizar o estoque do produto
                $product->stock -= $item['quantity'];
                $product->save();

                // Calcular subtotal do item
                $price = $product->price;
                $itemSubtotal = $price * $item['quantity'];
                $subtotal += $itemSubtotal;

                // Cria o item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price
                ]);
            }
            // Verificar se há descontos aplicáveis
            $discountPercent = $this->getDiscount($subtotal);
            $discount = ($subtotal * $discountPercent) / 100;
            $total = $subtotal - $discount;

            // Atualizar o subtotal e total do pedido
            $order->subTotal = $subtotal;
            $order->total = $total;
            $order->save();

            DB::commit();

            // Retornar resposta de sucesso
            return response()->json([
                'order' => $order->load('items')
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            // Retornar erro em caso de exceção
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
    /**
     * Obtém o percentual de desconto com base no total do pedido.
     */
    protected function getDiscount(float $subtotal): float
    {
        $rule = Discount::where('min_total', '<=', $subtotal)
            ->orderBy('min_total', 'desc')
            ->first();

        if ($rule) {
            return (float)$rule->discount_percent;
        }

        return 0;
    }

    /**
     * Exibe um pedido específico com seus itens e os produtos relacionados.
     */
    public function show($id)
    {
        return Order::with('items.product')->findOrFail($id);
    }
}

