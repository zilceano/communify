<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adicionar Produto à Loja') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ selectedBasePrice: 0 }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('creator.produtos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Selecione o Tipo de Produto</label>
                            <select name="base_product_id" required 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    @change="selectedBasePrice = parseFloat($event.target.selectedOptions[0].dataset.price)">
                                <option value="" data-price="0">-- Escolha um item --</option>
                                @foreach($baseProducts as $base)
                                    <option value="{{ $base->id }}" data-price="{{ $base->base_price }}">
                                        {{ $base->name }} (Custo Base: R$ {{ number_format($base->base_price, 2, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Este é o custo de produção e taxas da plataforma.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nome do Produto</label>
                                <input type="text" name="name" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Ex: Caneca Dev Senior">
                            </div>

                            <div x-data="{ profit: 0 }">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Seu Lucro (R$)</label>
                                <input type="number" name="profit" step="0.50" min="0" required x-model="profit"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="0.00">
                                
                                <div class="mt-2 p-3 bg-gray-50 rounded text-sm" x-show="selectedBasePrice > 0">
                                    <span class="text-gray-500">Preço Final na Loja:</span>
                                    <span class="font-bold text-green-600 text-lg">
                                        R$ <span x-text="(selectedBasePrice + parseFloat(profit || 0)).toFixed(2)"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Descrição</label>
                            <textarea name="description" rows="4" required
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Descreva seu produto... Dica: Mencione aqui se houver doação para alguma causa!"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Foto para a Loja (Mockup)</label>
                                <input type="file" name="image_mockup" accept="image/*" required class="block w-full text-sm text-gray-500">
                                <p class="text-xs text-gray-400 mt-1">O que o cliente vê.</p>
                            </div>

                            <div class="border-2 border-dashed border-indigo-300 rounded-lg p-6 text-center bg-indigo-50">
                                <label class="block text-sm font-bold text-indigo-700 mb-2">Arte para Impressão</label>
                                <input type="file" name="file_artwork" accept="image/*" required class="block w-full text-sm text-gray-500">
                                <p class="text-xs text-gray-400 mt-1">Arquivo em alta qualidade para produzirmos.</p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <a href="{{ route('creator.dashboard') }}" class="mr-4 px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancelar</a>
                            <button type="submit" class="px-6 py-2 bg-purple-600 text-white font-bold rounded-md hover:bg-purple-700">Adicionar Produto</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>