<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Lista todos os posts da comunidade
     */
    public function index()
    {
        // Busca os posts da comunidade do usuário logado, do mais recente pro mais antigo
        $posts = auth()->user()->community->posts()->latest()->get();
        return view('creator.posts.index', compact('posts'));
    }

    /**
     * Mostra o formulário de criar post
     */
    public function create()
    {
        return view('creator.posts.create');
    }

    /**
     * Salva o post no banco
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        // Cria o post vinculado à Comunidade E ao Usuário
        auth()->user()->community->posts()->create([
            'user_id' => auth()->id(), // O autor é o próprio criador
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imagePath,
        ]);

        return redirect()->route('creator.posts.index')->with('success', 'Post publicado com sucesso!');
    }

    /**
     * Deleta um post
     */
    public function destroy(Post $post)
    {
        // Segurança: Garante que o post pertence à comunidade do usuário logado
        if ($post->community_id !== auth()->user()->community->id) {
            abort(403);
        }

        $post->delete();

        return back()->with('success', 'Post deletado.');
    }
    
    // Métodos edit/update deixamos para depois para agilizar
}