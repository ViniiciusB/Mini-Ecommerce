<?php

namespace App\Http\Controllers;

use App\Models\Order;
use PHPUnit\Framework\Exception;

class PaymentController extends Controller
{
    public function payOrder($id)
    {
        // Obtem o pedido com seus itens e produtos associados.
        $order = Order::with('items.product')->findOrFail($id);

        // Verifica se o pedido já foi pago para evitar pagamentos duplicados.
        if ($order->status === Order::STATUS_PAID) {
            return response()->json([
                'message' => 'Este pedido já foi pago.',
                'order' => $order
            ], 422);
        }

        // Simula o processamento de pagamento externo como aprovado.
        $order->status = Order::STATUS_PAID;
        $order->save(); // Atualiza o pedido com o novo status.

        // Retorna a resposta de sucesso da operação.
        return response()->json([
            'order' => $order
        ],200);
    }
    public function cancelOrder($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status == Order::STATUS_AWAITING_PAYMENT) {
            $order->status = 'canceled';
            $order->save();

            // Verifica se a alteração foi aplicada
            if ($order->wasChanged('status')) {
                return response()->json([
                    'order' => $order
                ], 200);
            } throw new Exception("Falha ao alterar o status do pedido");
        }
        // Caso o pedido não possa ser cancelado
        return response()->json([
            'message' => 'Este pedido não pode ser cancelado devido ao seu status atual: ' . $order->status = Order::STATUS_PAID,
            'order' => $order
        ], 422);
    }
}
