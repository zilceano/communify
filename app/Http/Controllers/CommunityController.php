<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * (J1-02) Página Explorar (Listagem de Comunidades)
     */
    public function index()
    {
        $communities = Community::with('user')->latest()->get();
        return view('community.index', compact('communities'));
    }

    /**
     * (J1-03) Página Pública da Comunidade (Feed + Loja)
     */
    public function show($slug)
    {
        // Busca a comunidade pelo slug
        // Carrega também os posts (com autor e comentários) e os produtos
        $community = Community::where('slug', $slug)
            ->with(['user', 'posts.user', 'posts.comments', 'products.baseProduct'])
            ->firstOrFail();

        return view('community.show', compact('community'));
    }

    /**
     * (J2-02) Seguir / Deixar de Seguir
     */
    public function follow(Request $request, Community $community)
    {
        $user = auth()->user();

        // Toggle: Se já segue, remove. Se não segue, adiciona.
        $user->follows()->toggle($community->id);

        return back();
    }
}