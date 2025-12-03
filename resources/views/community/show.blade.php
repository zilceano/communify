<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $community->name }} - Communify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-indigo-600">Communify.</a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @php
                            $cartCount = auth()->user()->orders()->where('status', 'draft')->first()?->items->count() ?? 0;
                        @endphp
                        <a href="{{ route('order.cart') }}" class="relative text-gray-600 hover:text-indigo-600 mr-4">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            @if($cartCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-indigo-600">Meu Painel</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-indigo-600">Entrar</a>
                    @endauth
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-indigo-600">Meu Painel</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-indigo-600">Entrar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-white shadow-md pb-6">
        <div class="h-48 md:h-64 w-full bg-gray-300 relative overflow-hidden">
            @if($community->cover_image)
                <img src="{{ asset('storage/' . $community->cover_image) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-r from-indigo-500 to-purple-600"></div>
            @endif
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="flex flex-col md:flex-row items-start md:items-end -mt-12 mb-4">
                <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white bg-white overflow-hidden shadow-lg z-10">
                    @if($community->profile_image)
                        <img src="{{ asset('storage/' . $community->profile_image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-400">
                            {{ substr($community->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                
                <div class="mt-4 md:mt-0 md:ml-6 flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $community->name }}</h1>
                    <p class="text-gray-500 text-sm">Criado por {{ $community->user->name }} • {{ $community->followers->count() }} seguidores</p>
                </div>

                <div class="mt-4 md:mt-0">
                    @auth
                        @if(auth()->id() !== $community->user_id)
                            <form action="{{ route('community.follow', $community->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-2 rounded-full font-bold shadow-sm transition
                                    {{ auth()->user()->follows->contains($community->id) 
                                        ? 'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' 
                                        : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                                    {{ auth()->user()->follows->contains($community->id) ? 'Seguindo' : 'Seguir Comunidade' }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('creator.dashboard') }}" class="px-6 py-2 bg-gray-100 text-gray-700 font-bold rounded-full border border-gray-300 hover:bg-gray-200">
                                Gerenciar
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-full hover:bg-indigo-700">
                            Seguir
                        </a>
                    @endauth
                </div>
            </div>

            <div class="mt-6 max-w-3xl">
                <p class="text-gray-700 leading-relaxed">{{ $community->description }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ tab: 'feed' }">
        
        <div class="flex border-b border-gray-200 mb-8">
            <button @click="tab = 'feed'" 
                    :class="tab === 'feed' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-6 border-b-2 font-medium text-lg focus:outline-none transition">
                Feed de Conteúdo
            </button>
            <button @click="tab = 'store'" 
                    :class="tab === 'store' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-6 border-b-2 font-medium text-lg focus:outline-none transition flex items-center">
                Loja Oficial 
                <span class="ml-2 bg-purple-100 text-purple-700 py-0.5 px-2 rounded-full text-xs">{{ $community->products->count() }}</span>
            </button>
        </div>

        <div x-show="tab === 'feed'" class="max-w-3xl">
            @forelse($community->posts as $post)
                <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">
                                {{ substr($post->user->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-bold text-gray-900">{{ $post->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $post->title }}</h2>
                        <div class="text-gray-700 whitespace-pre-line mb-4">{{ $post->body }}</div>
                        
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="w-full rounded-lg mb-4">
                        @endif

                        <div class="border-t pt-4">
                            <h4 class="font-bold text-sm text-gray-500 mb-3">Comentários ({{ $post->comments->count() }})</h4>
                            
                            <div class="space-y-3 mb-4">
                                @foreach($post->comments as $comment)
                                    <div class="bg-gray-50 p-3 rounded text-sm">
                                        <span class="font-bold text-gray-800">{{ $comment->user->name }}:</span>
                                        <span class="text-gray-600">{{ $comment->content }}</span>
                                    </div>
                                @endforeach
                            </div>

                            @auth
                                <form action="{{ route('post.comment.store', $post->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="text" name="content" required placeholder="Escreva um comentário..." 
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-md hover:bg-indigo-700">
                                        Enviar
                                    </button>
                                </form>
                            @else
                                <p class="text-sm text-gray-400">Faça login para comentar.</p>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-white rounded-lg border border-gray-200 border-dashed">
                    <p class="text-gray-500">Nenhum post publicado ainda.</p>
                </div>
            @endforelse
        </div>

        <div x-show="tab === 'store'" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($community->products as $product)
                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition group">
                        <div class="h-64 overflow-hidden relative">
                            <img src="{{ asset('storage/' . $product->image_mockup) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-6">
                            <div class="text-xs text-indigo-500 font-bold uppercase mb-1">{{ $product->baseProduct->name }}</div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 truncate">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-xl font-bold text-gray-900">
                                    R$ {{ number_format($product->baseProduct->base_price + $product->profit, 2, ',', '.') }}
                                </span>
                                <a href="{{ route('product.show', $product->slug) }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded hover:bg-indigo-700 transition">
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-10 bg-white rounded-lg border border-gray-200 border-dashed">
                        <p class="text-gray-500">A loja ainda está vazia.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</body>
</html>