<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // Lista todos os usuários, mais recentes primeiro
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    // (Opcional) Funcionalidade "Ver como Usuário" (Login As)
    public function loginAs(User $user)
    {
        // Segurança: Admin não pode logar como outro Admin para evitar confusão
        if ($user->is_admin) {
            return back()->with('error', 'Não é possível logar como outro Admin.');
        }

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Você está logado como ' . $user->name);
    }
    
    // Métodos create, store, edit, update, destroy podem ficar vazios por enquanto no MVP
}