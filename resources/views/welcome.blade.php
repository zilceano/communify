<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Communify - Crie sua Comunidade</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="//unpkg.com/alpinejs" defer></script>
    </head>
    <body class="antialiased bg-gray-50 text-gray-800">

        <div class="relative w-full max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-indigo-600 tracking-tighter">
                Communify.
            </div>
            <div>
                @if (Route::has('login'))
                    <div class="space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline hover:text-indigo-600">Meu Painel</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Entrar</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 text-sm font-semibold text-white bg-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-500">Criar Conta</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-16 sm:py-24 text-center">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
                Construa comunidades <br class="hidden sm:block" />
                <span class="text-indigo-600">com propósito real.</span>
            </h1>
            <p class="mt-4 text-xl text-gray-500 max-w-2xl mx-auto mb-10">
                O lugar onde criadores conectam pessoas, compartilham ideias e monetizam produtos exclusivos. Tudo em um só lugar.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3 text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-xl md:px-10">
                    Começar Agora
                </a>
                <a href="{{ route('community.index') }}" class="px-8 py-3 text-lg font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-xl md:px-10">
                    Explorar Comunidades
                </a>
            </div>
        </div>

        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Comunidades em Destaque</h2>

                @if($featuredCommunities->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach($featuredCommunities as $community)
                            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                                <div class="h-32 bg-indigo-500 relative">
                                    @if($community->cover_image)
                                        <img src="{{ asset('storage/' . $community->cover_image) }}" class="w-full h-full object-cover opacity-80">
                                    @endif
                                </div>
                                <div class="p-6 pt-12 relative">
                                    <div class="absolute -top-10 left-6 w-20 h-20 bg-white rounded-full border-4 border-white overflow-hidden shadow">
                                        @if($community->profile_image)
                                            <img src="{{ asset('storage/' . $community->profile_image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $community->name }}</h3>
                                    <p class="text-gray-500 text-sm mt-1">por {{ $community->user->name ?? 'Criador' }}</p>
                                    <p class="mt-4 text-gray-600 line-clamp-2">
                                        {{ $community->description ?? 'Sem descrição.' }}
                                    </p>
                                    <a href="{{ route('community.show', $community->slug) }}" class="mt-4 inline-block text-indigo-600 font-semibold hover:underline">
                                        Visitar Comunidade &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-10">
                        <p>Ainda não há comunidades criadas. Seja o primeiro!</p>
                    </div>
                @endif
            </div>
        </div>

        <footer class="bg-gray-50 border-t border-gray-200 mt-12 py-12">
            <div class="max-w-7xl mx-auto px-6 text-center text-gray-400">
                &copy; {{ date('Y') }} Communify. Todos os direitos reservados.
            </div>
        </footer>
    </body>
</html>