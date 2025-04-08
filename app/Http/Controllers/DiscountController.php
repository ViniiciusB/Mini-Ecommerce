<?php


/**
 * Class DiscountController
 * Cuida das regras de desconto, listing, creating, updating, and deleting.
 */

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    // Exibir regras
    public function index()
    {
        // Retorna lista de todas as regras, ordenadas por min_total desc, por exemplo
        return Discount::orderBy('min_total', 'desc')->get();
    }

    // Criar regra
    public function store(Request $request)
    {
        $rule = Discount::create([
            'min_total' => $request->input('min_total'),
            'discount_percent' => $request->input('discount_percent')
        ]);

        return response()->json([
            'message' => 'Regra de desconto criada!',
            'rule' => $rule
        ], 201);
    }

    // Atualizar regra
    public function update(Request $request, $id)
    {
        $rule = Discount::findOrFail($id);
        $rule->update([
            'min_total' => $request->input('min_total'),
            'discount_percent' => $request->input('discount_percent')
        ]);

        return response()->json([
            'message' => 'Regra de desconto atualizada!',
            'rule' => $rule
        ]);
    }

    // Deletar regra
    public function destroy($id)
    {
        $rule = Discount::findOrFail($id);
        $rule->delete();

        return response()->json([
            'message' => 'Regra de desconto deletada com sucesso!'
        ]);
    }
}

