<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\Post;

class HomeController extends Controller
{
    /**
     * (J1-01) Landing Page (Página Inicial Pública)
     */
    public function index()
    {
        // Busca 3 comunidades aleatórias para mostrar na capa como "Destaque"
        // Se não tiver nenhuma, retorna uma lista vazia.
        $featuredCommunities = Community::inRandomOrder()->take(3)->get();

        return view('welcome', compact('featuredCommunities'));
    }

    /**
     * (J2-01) Dashboard (Área do Usuário Logado)
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Busca os IDs das comunidades que o usuário segue
        $followedCommunityIds = $user->follows()->pluck('communities.id');

        // Busca os posts dessas comunidades (ordem cronológica)
        $posts = Post::whereIn('community_id', $followedCommunityIds)
                     ->with(['user', 'community']) // Carrega dados do autor e comunidade para não ficar lento
                     ->latest()
                     ->get();

        return view('dashboard', compact('posts'));
    }
}