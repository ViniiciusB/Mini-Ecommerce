<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

/**
 * Class ProductController
 *
 * Controller responsável por gerenciar as operações relacionadas ao modelo Product.
 * Inclui a criação, atualização, exclusão, exibição e listagem de produtos.
 */
class ProductController extends Controller
{
    public function store(Request $request)
    {
        // Valida os dados enviados na requisição
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4|max:30',
            'price' => 'required|numeric',
            'description' => 'nullable|string|min:10',
            'stock' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            throw new Exception('Erros de validação: ' . $validator->errors(), 422);
        }

        try {
            // Cria o produto no banco de dados
            $product = Product::create([
                'name'        => $request->name,
                'price'       => $request->price,
                'description' => $request->description,
                'stock'       => $request->stock
            ]);
        } catch (Exception $e) {
            throw new Exception('Erro ao criar produto: ' . $e->getMessage(), 422);
        }
        return response()->json(201);
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            throw new Exception('Produto não encontrado', 422);
        }
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            throw new Exception('Produto não encontrado', 422);
        }

        // Atualiza os dados do produto
        $product->update($request->all());

        // Retorna os dados atualizados do produto
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            throw new Exception('Produto não encontrado', 422);
        }

        $product->delete();
        return response()->json(202);
    }
    public function index($id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            throw new Exception('Produto não encontrado', 422);
        }
        $product->all();
        return response()->json($product);
    }
}
