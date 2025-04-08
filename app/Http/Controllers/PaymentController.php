<?php

namespace App\Http\Controllers;

use App\Models\Order;

class PaymentController extends Controller
{
    public function payOrder($id)
    {
        // Obtem o pedido com seus itens e produtos associados.
        $order = Order::with('items.product')->findOrFail($id);

        // Verifica se o pedido já foi pago para evitar pagamentos duplicados.
        if ($order->status === 'paid') {
            return response()->json([
                'message' => 'Este pedido já foi pago.',
                'order' => $order
            ], 400);
        }

        // Simula o processamento de pagamento externo como aprovado.
        $order->status = 'paid';
        $order->save(); // Atualiza o pedido com o novo status.

        // Retorna a resposta de sucesso da operação.
        return response()->json([
            'message' => 'Pagamento aprovado! Status do pedido atualizado.',
            'order' => $order
        ]);
    }
}
