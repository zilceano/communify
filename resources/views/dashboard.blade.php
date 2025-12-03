<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meu Feed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2 space-y-6">
                    @forelse($posts as $post)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                            @if($post->community->profile_image)
                                                <img src="{{ asset('storage/' . $post->community->profile_image) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-gray-500 font-bold text-xs">{{ substr($post->community->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('community.show', $post->community->slug) }}" class="text-sm font-bold text-gray-900 hover:underline">
                                                {{ $post->community->name }}
                                            </a>
                                            <p class="text-xs text-gray-500">
                                                Postado por {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $post->title }}</h3>
                                <div class="prose max-w-none text-gray-600 mb-4">
                                    {!! nl2br(e($post->body)) !!}
                                </div>

                                @if($post->image)
                                    <img src="{{ asset('storage/' . $post->image) }}" class="w-full rounded-lg mb-4">
                                @endif

                                <div class="border-t border-gray-100 pt-4 flex items-center space-x-4">
                                    <button class="flex items-center text-gray-500 hover:text-indigo-600 transition">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <span>{{ $post->comments->count() }} Comentários</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center">
                            <div class="mb-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Seu feed está vazio</h3>
                            <p class="mt-1 text-gray-500">Comece a seguir comunidades para ver posts aqui.</p>
                            <div class="mt-6">
                                <a href="{{ route('community.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Explorar Comunidades
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Área do Criador</h3>
                        
                        @if(auth()->user()->community)
                            <div class="text-center">
                                <div class="mx-auto h-16 w-16 bg-indigo-100 rounded-full flex items-center justify-center mb-3">
                                    @if(auth()->user()->community->profile_image)
                                        <img src="{{ asset('storage/' . auth()->user()->community->profile_image) }}" class="rounded-full w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    @endif
                                </div>
                                <h4 class="font-bold text-gray-800">{{ auth()->user()->community->name }}</h4>
                                <p class="text-sm text-gray-500 mb-4">{{ auth()->user()->community->followers->count() }} seguidores</p>
                                
                                <a href="{{ route('creator.dashboard') }}" class="block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                    Gerenciar Comunidade
                                </a>
                            </div>
                        @else
                            <div class="text-center">
                                <p class="text-sm text-gray-600 mb-4">Você ainda não tem uma comunidade. Crie a sua e comece a monetizar!</p>
                                <a href="{{ route('creator.community.create') }}" class="block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Criar Minha Comunidade
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Menu</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('order.index') }}" class="flex items-center text-gray-700 hover:text-indigo-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    Meus Pedidos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}" class="flex items-center text-gray-700 hover:text-indigo-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Editar Perfil
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>