<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administração do Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <div class="text-2xl font-bold">{{ $stats['users'] }}</div>
                    <div class="text-xs text-gray-500 uppercase">Usuários</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <div class="text-2xl font-bold">{{ $stats['communities'] }}</div>
                    <div class="text-xs text-gray-500 uppercase">Comunidades</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <div class="text-2xl font-bold">{{ $stats['orders'] }}</div>
                    <div class="text-xs text-gray-500 uppercase">Pedidos</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center border-l-4 border-green-500">
                    <div class="text-2xl font-bold text-green-600">R$ {{ number_format($stats['revenue'], 2, ',', '.') }}</div>
                    <div class="text-xs text-gray-500 uppercase">Receita Total</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center border-l-4 border-yellow-500">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</div>
                    <div class="text-xs text-gray-500 uppercase">Aguardando Aprovação</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Gerenciamento</h3>
                    <div class="flex gap-4">
                        <a href="{{ route('admin.pedidos.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Gerenciar Pedidos
                        </a>
                        <button class="px-4 py-2 bg-gray-200 text-gray-400 cursor-not-allowed">Usuários (Fase 2)</button>
                        <button class="px-4 py-2 bg-gray-200 text-gray-400 cursor-not-allowed">Produtos Base (Fase 2)</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>