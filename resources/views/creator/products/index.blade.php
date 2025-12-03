<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gerenciar Produtos') }}
            </h2>
            <a href="{{ route('creator.produtos.create') }}" class="px-4 py-2 bg-purple-600 text-white text-sm font-bold rounded hover:bg-purple-700">
                + Adicionar Produto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="h-48 bg-gray-100 relative">
                            <img src="{{ asset('storage/' . $product->image_mockup) }}" class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2 bg-white px-2 py-1 text-xs font-bold rounded shadow">
                                {{ $product->baseProduct->name }}
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 truncate">{{ $product->name }}</h3>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-green-600 font-bold">Lucro: R$ {{ number_format($product->profit, 2, ',', '.') }}</span>
                                <span class="text-gray-500 text-sm">Custo: {{ number_format($product->baseProduct->base_price, 2, ',', '.') }}</span>
                            </div>
                            <div class="mt-4 flex justify-between items-center border-t pt-4">
                                <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="text-indigo-600 text-sm hover:underline">Ver na Loja</a>
                                
                                <form action="{{ route('creator.produtos.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Remover este produto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-sm hover:text-red-700">Remover</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-10 text-center rounded-lg shadow-sm">
                        <p class="text-gray-500 mb-4">Sua loja está vazia.</p>
                        <a href="{{ route('creator.produtos.create') }}" class="text-purple-600 font-bold hover:underline">Adicione seu primeiro produto!</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>