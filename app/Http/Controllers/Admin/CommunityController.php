<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        $communities = Community::with('user')->withCount(['posts', 'products', 'followers'])->latest()->paginate(20);
        return view('admin.communities.index', compact('communities'));
    }

    // Para o MVP, não precisamos de Edit/Update aqui, apenas visualização
}