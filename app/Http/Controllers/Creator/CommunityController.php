<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    /**
     * Mostra o formulário de criação
     */
    public function create()
    {
        // Verifica se o usuário JÁ tem comunidade (só pode ter uma)
        if (auth()->user()->community) {
            return redirect()->route('creator.dashboard');
        }

        return view('creator.community.create');
    }

    /**
     * Salva a nova comunidade no banco
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255|unique:communities',
            'description' => 'required|string|max:1000',
            'cover_image' => 'nullable|image|max:2048', // Max 2MB
            'profile_image' => 'nullable|image|max:1024', // Max 1MB
        ]);

        // 2. Upload das Imagens (se enviadas)
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('communities/covers', 'public');
        }

        $profilePath = null;
        if ($request->hasFile('profile_image')) {
            $profilePath = $request->file('profile_image')->store('communities/profiles', 'public');
        }

        // 3. Criação no Banco
        // O Slug é gerado automaticamente pelo Model, lembra?
        auth()->user()->community()->create([
            'name' => $request->name,
            'description' => $request->description,
            'cover_image' => $coverPath,
            'profile_image' => $profilePath,
        ]);

        // 4. Redireciona para o Painel do Criador com sucesso
        return redirect()->route('creator.dashboard')->with('success', 'Comunidade criada com sucesso!');
    }

    // --- Métodos de Edição (Faremos depois) ---
    public function edit() {}
    public function update(Request $request) {}
}