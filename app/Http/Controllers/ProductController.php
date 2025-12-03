<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * (J1-04) Página Pública do Produto
     */
    public function show($slug)
    {
        // Busca o produto pelo slug, trazendo junto o Produto Base (para pegar as opções de tamanho/cor)
        // e a Comunidade (para mostrar o header)
        $product = Product::where('slug', $slug)
            ->with(['baseProduct', 'community'])
            ->where('is_active', true)
            ->firstOrFail();

        return view('product.show', compact('product'));
    }
}