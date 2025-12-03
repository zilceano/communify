<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Lista todos os pedidos
     */
    public function index()
    {
        // Traz os pedidos, ordenando os "Pendentes com Comprovante" primeiro
        $orders = Order::with(['user', 'items'])
            ->orderByRaw("CASE WHEN status = 'awaiting_payment' AND proof_of_payment IS NOT NULL THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Mostra detalhes para aprovação
     */
    public function show(Order $pedido) // Laravel faz o bind automático pelo ID
    {
        return view('admin.orders.show', compact('pedido')); // Passamos como $pedido para a view
    }

    /**
     * Atualiza o status (Aprovar Pagamento)
     */
    public function update(Request $request, Order $pedido)
    {
        // Se clicar em "Aprovar Pagamento"
        if ($request->has('approve_payment')) {
            $pedido->update(['status' => 'paid']);
            return redirect()->route('admin.pedidos.index')->with('success', 'Pagamento aprovado! Pedido liberado.');
        }

        return back();
    }
}