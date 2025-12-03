<?php

use Illuminate\Support\Facades\Route;

// Importando os Controladores Principais
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

// Importando os Controladores do CRIADOR
use App\Http\Controllers\Creator\DashboardController as CreatorDashboardController;
use App\Http\Controllers\Creator\CommunityController as CreatorCommunityController;
use App\Http\Controllers\Creator\PostController as CreatorPostController;
use App\Http\Controllers\Creator\ProductController as CreatorProductController;

// Importando os Controladores do ADMIN
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CommunityController as AdminCommunityController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === JORNADA 1: PÚBLICA (Visitante) ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/explorar', [CommunityController::class, 'index'])->name('community.index');
Route::get('/c/{slug}', [CommunityController::class, 'show'])->name('community.show');
Route::get('/p/{slug}', [ProductController::class, 'show'])->name('product.show');

// === ROTAS PADRÃO DO BREEZE (Login/Dashboard) ===
Route::get('/dashboard', [HomeController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === JORNADA 2: LEITOR/COMPRADOR (Logado) ===
Route::middleware('auth')->group(function () {
    // Seguir Comunidade
    Route::post('/community/{community}/follow', [CommunityController::class, 'follow'])->name('community.follow');
    
    // Comentar
    Route::post('/post/{post}/comment', [PostController::class, 'storeComment'])->name('post.comment.store');

    // Carrinho e Checkout (Fluxo Novo)
    Route::get('/carrinho', [OrderController::class, 'cart'])->name('order.cart');
    Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
    
    // Gestão do Carrinho (Remover/Atualizar)
    Route::delete('/order/item/{item}', [OrderController::class, 'removeItem'])->name('order.item.remove');
    Route::patch('/order/item/{item}/update', [OrderController::class, 'updateItemQuantity'])->name('order.item.update');

    // Finalização e Histórico
    Route::put('/meus-pedidos/{order}/finalize', [OrderController::class, 'finalize'])->name('order.finalize');
    Route::get('/meus-pedidos', [OrderController::class, 'index'])->name('order.index');
    Route::get('/meus-pedidos/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/meus-pedidos/{order}/upload', [OrderController::class, 'uploadProof'])->name('order.upload_proof');
});

// === JORNADA 3: CRIADOR (Logado) ===
Route::middleware(['auth'])->prefix('criador')->name('creator.')->group(function () {
    // Criação da Comunidade
    Route::get('/comunidade/criar', [CreatorCommunityController::class, 'create'])->name('community.create');
    Route::post('/comunidade', [CreatorCommunityController::class, 'store'])->name('community.store');
    
    // Painel e Gestão
    Route::get('/painel', [CreatorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/comunidade/editar', [CreatorCommunityController::class, 'edit'])->name('community.edit');
    Route::put('/comunidade', [CreatorCommunityController::class, 'update'])->name('community.update');
    
    // CRUDs
    Route::resource('/posts', CreatorPostController::class);
    Route::resource('/produtos', CreatorProductController::class);
    
    // Pedidos da Comunidade
    Route::get('/pedidos', [CreatorDashboardController::class, 'orders'])->name('orders.index');
});

// === JORNADA 4: ADMIN (Logado + Admin) ===
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Gestão de Usuários
    Route::resource('/usuarios', AdminUserController::class);
    // [CORREÇÃO] Esta é a linha que faltava para o erro sumir:
    Route::get('/usuarios/{user}/login-as', [AdminUserController::class, 'loginAs'])->name('users.login-as');

    Route::resource('/comunidades', AdminCommunityController::class);
    Route::resource('/produtos', AdminProductController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('/pedidos', AdminOrderController::class)->only(['index', 'show', 'update']);
});

// Carrega as rotas de autenticação (Login/Registro)
require __DIR__.'/auth.php';