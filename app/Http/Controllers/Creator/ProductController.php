<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\BaseProduct;

class ProductController extends Controller
{
    /**
     * Lista os produtos da loja do criador
     */
    public function index()
    {
        // Busca os produtos da comunidade
        $products = auth()->user()->community->products()->with('baseProduct')->latest()->get();
        return view('creator.products.index', compact('products'));
    }

    /**
     * Mostra o formulário de adicionar produto
     */
    public function create()
    {
        // Busca a lista de produtos base (Camiseta, Caneca, etc) para o criador escolher
        $baseProducts = BaseProduct::all();
        return view('creator.products.create', compact('baseProducts'));
    }

    /**
     * Salva o novo produto
     */
    public function store(Request $request)
    {
        $request->validate([
            'base_product_id' => 'required|exists:base_products,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'profit' => 'required|numeric|min:0', // O lucro do criador
            'image_mockup' => 'required|image|max:2048', // Foto para a loja
            'file_artwork' => 'required|image|max:5120', // Arquivo original (até 5MB)
        ]);

        // Uploads
        $mockupPath = $request->file('image_mockup')->store('products/mockups', 'public');
        $artworkPath = $request->file('file_artwork')->store('products/artwork', 'public'); // Idealmente seria privado, mas no MVP vai public

        // Cria o produto
        auth()->user()->community->products()->create([
            'base_product_id' => $request->base_product_id,
            'name' => $request->name,
            'description' => $request->description,
            'profit' => $request->profit,
            'image_mockup' => $mockupPath,
            'file_artwork' => $artworkPath,
            'is_active' => true,
        ]);

        return redirect()->route('creator.produtos.index')->with('success', 'Produto adicionado à loja!');
    }

    /**
     * Remove um produto
     */
    public function destroy(Product $produto) // O Laravel entende 'produto' por causa da rota resource em pt-br? Vamos garantir com ID
    {
        // Nota: Como a rota é /produtos/{produto}, o Laravel injeta a variável como $produto
        // Vamos verificar se pertence à comunidade
        if ($produto->community_id !== auth()->user()->community->id) {
            abort(403);
        }

        $produto->delete();
        return back()->with('success', 'Produto removido.');
    }
}