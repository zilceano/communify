<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->get();
        return view('order.index', compact('orders'));
    }

    // LISTAR O CARRINHO
    public function cart()
    {
        // Busca o pedido que está em rascunho (draft)
        $order = auth()->user()->orders()->where('status', 'draft')->with('items.product')->first();

        if (!$order) {
            return view('order.empty'); // Se não tiver carrinho, mostra vazio
        }

        return view('order.show', compact('order'));
    }

    // ADICIONAR AO CARRINHO
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'selected_options' => 'nullable|json',
        ]);

        $product = Product::findOrFail($request->product_id);
        $unitPrice = $product->baseProduct->base_price + $product->profit;

        // 1. Tenta achar um carrinho aberto
        $order = Order::where('user_id', auth()->id())->where('status', 'draft')->first();

        // 2. Se não achar, cria um NOVO com status 'draft'
        if (!$order) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'draft', // <--- ISSO É CRUCIAL
                'total_amount' => 0, 
                'shipping_cost' => 0,
                'donation_amount' => 0,
            ]);
        }

        // 3. Adiciona item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $unitPrice,
            'selected_options_json' => json_decode($request->selected_options, true),
        ]);

        // 4. Atualiza total
        $order->total_amount = $order->items()->sum('price');
        $order->save();

        return back()->with('success', 'Produto adicionado ao carrinho!'); 
    }


    /**
         * Remover item do carrinho
     */
    public function removeItem(OrderItem $item)
    {
        // Segurança: O item deve pertencer a um rascunho do usuário logado
        if ($item->order->user_id !== auth()->id() || $item->order->status !== 'draft') {
            abort(403);
        }

        $order = $item->order;
        $item->delete();

        // Recalcula o total do pedido
        $this->recalculateOrderTotal($order);

        // Se o pedido ficou vazio, deleta o pedido também para limpar
        if ($order->items()->count() === 0) {
            $order->delete();
            return redirect()->route('order.cart'); // Vai cair na tela "Vazio"
        }

        return back()->with('success', 'Item removido.');
    }

    /**
     * Atualizar Quantidade (+ ou -)
     */
    public function updateItemQuantity(Request $request, OrderItem $item)
    {
        if ($item->order->user_id !== auth()->id() || $item->order->status !== 'draft') {
            abort(403);
        }

        // Ação: 'increase' ou 'decrease'
        $action = $request->input('action');

        if ($action === 'increase') {
            $item->increment('quantity');
        } elseif ($action === 'decrease') {
            if ($item->quantity > 1) {
                $item->decrement('quantity');
            } else {
                // Se for 1 e tentar diminuir, remove o item? 
                // Geralmente é melhor deixar o botão remover cuidar disso, 
                // mas vamos manter o mínimo 1 aqui.
            }
        }

        $this->recalculateOrderTotal($item->order);

        return back();
    }

    /**
     * Função auxiliar para recalcular totais (Privada)
     */
    private function recalculateOrderTotal(Order $order)
    {
        // Soma: (Preço Unitário * Quantidade) de todos os itens
        $itemsTotal = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $order->total_amount = $itemsTotal; // Ainda sem frete/doação (só soma itens)
        $order->save();
    }



    // MOSTRAR CHECKOUT (Se draft) OU PEDIDO (Se pago)
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        return view('order.show', compact('order'));
    }

    // FINALIZAR COMPRA (Salvar endereço e frete)
    public function finalize(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        $request->validate([
            'full_name' => 'required|string',
            'cpf' => 'required|string',
            'address_zip' => 'required|string',
            'address_street' => 'required|string',
            'address_number' => 'required|string',
            'address_city' => 'required|string',
            'address_state' => 'required|string|max:2',
        ]);

        // Atualiza usuário
        auth()->user()->update([
            'full_name' => $request->full_name,
            'cpf' => $request->cpf,
            'address_zip' => $request->address_zip,
            'address_street' => $request->address_street,
            'address_number' => $request->address_number,
            'address_complement' => $request->address_complement,
            'address_city' => $request->address_city,
            'address_state' => $request->address_state,
        ]);

        $shippingCost = 30.00; // Frete fixo simulado
        $donationAmount = $request->input('donation_amount', 0);
        
        $itemsTotal = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        $finalTotal = $itemsTotal + $shippingCost + $donationAmount;

        // Muda status para 'awaiting_payment' (Sai do modo Rascunho)
        $order->update([
            'status' => 'awaiting_payment',
            'shipping_cost' => $shippingCost,
            'donation_amount' => $donationAmount,
            'total_amount' => $finalTotal
        ]);

        return back()->with('success', 'Pedido atualizado! Realize o pagamento.');
    }

    // UPLOAD COMPROVANTE
    public function uploadProof(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        $request->validate(['proof' => 'required|image|max:2048']);
        $path = $request->file('proof')->store('orders/proofs', 'public');
        $order->update(['proof_of_payment' => $path]);
        return back()->with('success', 'Comprovante enviado!');
    }
}