<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * (J3-03) Painel Principal do Criador
     */
    public function index()
    {
        $user = auth()->user();
        $community = $user->community;

        // Segurança: Se o usuário tentar acessar aqui sem ter comunidade, chuta ele pra criação
        if (!$community) {
            return redirect()->route('creator.community.create');
        }

        // Estatísticas Básicas (MVP)
        $stats = [
            'followers' => $community->followers()->count(),
            'posts' => $community->posts()->count(),
            'products' => $community->products()->count(),
            // 'orders' => ... (Faremos depois)
        ];

        return view('creator.dashboard', compact('community', 'stats'));
    }

    /**
     * (J3-08) Lista de Pedidos
     */
    public function orders()
    {
        // Placeholder para não dar erro se clicar no link
        return view('creator.orders.index'); 
    }
}