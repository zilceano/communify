<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Produtos da Plataforma') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo Base</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comunidade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lucro Criador</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded object-cover mr-3" src="{{ asset('storage/'.$product->image_mockup) }}">
                                        <span class="font-bold">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $product->baseProduct->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $product->community->name }}</td>
                                <td class="px-6 py-4 text-sm text-green-600 font-bold">R$ {{ number_format($product->profit, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>