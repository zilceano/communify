<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Painel da Comunidade: <span class="text-indigo-600">{{ $community->name }}</span>
            </h2>
            <a href="{{ route('community.show', $community->slug) }}" target="_blank" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center">
                Ver Página Pública 
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500 text-sm font-medium">Seguidores</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['followers'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm font-medium">Posts Publicados</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['posts'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-gray-500 text-sm font-medium">Produtos na Loja</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['products'] }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Conteúdo</h3>
                        <p class="text-gray-500 mb-6">Mantenha sua comunidade engajada com novidades.</p>
                        
                        <div class="space-y-3">
                            <a href="{{ route('creator.posts.create') }}" class="block w-full text-center px-4 py-3 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 font-semibold border border-indigo-200">
                                + Escrever Novo Post
                            </a>
                            <a href="{{ route('creator.posts.index') }}" class="block w-full text-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg border border-gray-200">
                                Ver Todos os Posts
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Loja & Produtos</h3>
                        <p class="text-gray-500 mb-6">Venda produtos físicos e digitais para seus membros.</p>
                        
                        <div class="space-y-3">
                            <a href="{{ route('creator.produtos.create') }}" class="block w-full text-center px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 font-semibold border border-purple-200">
                                + Adicionar Produto à Loja
                            </a>
                            <a href="{{ route('creator.produtos.index') }}" class="block w-full text-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg border border-gray-200">
                                Gerenciar Produtos
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>