<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Community;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas Gerais da Plataforma
        $stats = [
            'users' => User::count(),
            'communities' => Community::count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'revenue' => Order::where('status', 'paid')->sum('total_amount'),
            'pending_orders' => Order::where('status', 'awaiting_payment')
                                     ->whereNotNull('proof_of_payment') // Só os que enviaram comprovante
                                     ->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}