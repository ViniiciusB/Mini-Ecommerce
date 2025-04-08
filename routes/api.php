<?php

use App\Http\Controllers\DiscountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

// Rotas de usuários autenticados
Route::middleware(['auth:api',])->group(function () {
    Route::get('/me', [UserController::class, 'me']); // Retorna dados do usuário autenticado
    Route::post('/logout', [UserController::class, 'logout']); // Realiza logout do usuário autenticado
    Route::post('/refresh', [UserController::class, 'refresh']); // Gera um novo token JWT para o usuário
});

// Rotas públicas de autenticação
Route::post('/login', [UserController::class, 'login']); // Autentica o usuário e retorna um token JWT
Route::post('/register', [UserController::class, 'register']); // Registra um novo usuário

// Rotas de produtos (restritas a administradores)
Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']); // Criação de novo produto
    Route::get('/products', [ProductController::class, 'index']); // Listagem de todos os produtos
    Route::get('/products/{id}', [ProductController::class, 'show']); // Detalhes de um produto específico
    Route::put('/products/{id}', [ProductController::class, 'update']); // Atualização de um produto específico
});

// Rotas de pedidos (restritas a administradores)
Route::middleware('auth:api', 'admin')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']); // Criação de um novo pedido
    Route::get('/orders/{id}', [OrderController::class, 'show']); // Exibe detalhes de um pedido específico

    // Rota de pagamento simulado
    Route::post('/orders/{id}/pay', [PaymentController::class, 'payOrder']); // Marcar um pedido como pago
});

// Rotas de desconto (restritas a administradores)
Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::get('/discount-rules', [DiscountController::class, 'index']); // Listar todas as regras de desconto
    Route::post('/discount-rules', [DiscountController::class, 'store']); // Criar uma nova regra de desconto
    Route::put('/discount-rules/{id}', [DiscountController::class, 'update']); // Atualizar uma regra de desconto específica
    Route::delete('/discount-rules/{id}', [DiscountController::class, 'destroy']); // Excluir uma regra de desconto específica
});

