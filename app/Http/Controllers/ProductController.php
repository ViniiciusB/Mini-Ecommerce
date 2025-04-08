<?php

namespace App\Http\Controllers;

use Exception;
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
    /**
     * Cria um novo produto.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Valida os dados enviados na requisição
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4|max:30',
            'price' => 'required|numeric',
            'description' => 'required|string|min:10',
            'stock' => 'required|integer|min:0'
        ]);

        // Retorna erro 422 caso a validação falhe
        if ($validator->fails()) {
            return response()->json(['erros' => $validator->errors()], 422);
        }

        try {
            // Cria o produto no banco de dados
            $product = Product::create(([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'stock' => $request->stock
            ]));
        } catch (Exception $e) {
            // Retorna erro 500 caso ocorra uma exceção
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Retorna sucesso 201 informando que o produto foi criado
        return response()->json(['message' => 'Produto criado com sucesso!'], 201);
    }

    /**
     * Exibe os dados de um produto específico.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Busca o produto pelo ID
        $product = Product::find($id);

        // Retorna erro 404 caso o produto não seja encontrado
        if (!$product) {
            return response()->json(['erro' => 'Produto não encontrado'], 404);
        }

        // Retorna o produto encontrado
        return response()->json($product);
    }

    /**
     * Atualiza as informações de um produto específico.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Busca o produto pelo ID
        $product = Product::find($id);

        // Retorna erro 404 caso o produto não seja encontrado
        if (!$product) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        // Atualiza os dados do produto
        $product->update($request->all());

        // Retorna os dados atualizados do produto
        return response()->json($product);
    }

    /**
     * Remove um produto específico do banco de dados.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Busca o produto pelo ID
        $product = Product::find($id);

        // Retorna erro 404 caso o produto não seja encontrado
        if (!$product) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        // Deleta o produto
        $product->delete();

        // Retorna mensagem de sucesso
        return response()->json(['message' => 'Produto deletado com sucesso']);
    }

    /**
     * Retorna uma lista de todos os produtos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        // Retorna lista de todos os produtos
        return Product::all();
    }
}

