<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - {{ $product->community->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <a href="{{ route('community.show', $product->community->slug) }}" class="flex items-center space-x-2 hover:opacity-80 transition">
                @if($product->community->profile_image)
                    <img src="{{ asset('storage/' . $product->community->profile_image) }}" class="w-8 h-8 rounded-full object-cover">
                @else
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                        {{ substr($product->community->name, 0, 1) }}
                    </div>
                @endif
                <span class="font-bold text-gray-700">{{ $product->community->name }}</span>
            </a>
            
            <div class="flex items-center space-x-4">
                @auth
                    @php
                        $cartCount = auth()->user()->orders()->where('status', 'draft')->first()?->items->count() ?? 0;
                    @endphp
                    <a href="{{ route('order.cart') }}" class="relative text-gray-600 hover:text-indigo-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                        @endif
                    </a>
                @endauth

                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-indigo-600">Meu Painel</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">Entrar</a>
                @endauth
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <a href="{{ route('order.cart') }}" class="text-sm font-bold underline hover:text-green-900">Ir para o Carrinho &rarr;</a>
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                
                <div class="bg-gray-100 p-8 flex items-center justify-center">
                    <img src="{{ asset('storage/' . $product->image_mockup) }}" class="max-h-[500px] w-auto rounded-lg shadow-lg object-contain transform transition hover:scale-105 duration-500">
                </div>

                <div class="p-8 md:p-12 flex flex-col justify-center" 
                     x-data="{ 
                        price: {{ $product->baseProduct->base_price + $product->profit }},
                        options: {{ json_encode($product->baseProduct->options_json) }},
                        selections: {},
                        
                        // Verifica se todas as variações foram escolhidas
                        isValid() {
                            if (!this.options) return true; // Se não tem variação, tá válido
                            return Object.keys(this.selections).length === Object.keys(this.options).length;
                        }
                     }">

                    <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold mb-1">
                        {{ $product->baseProduct->name }}
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4 leading-tight">
                        {{ $product->name }}
                    </h1>

                    <div class="text-3xl font-bold text-gray-900 mb-6">
                        R$ <span x-text="price.toFixed(2).replace('.', ',')"></span>
                    </div>

                    <p class="text-gray-600 mb-8 leading-relaxed">
                        {{ $product->description }}
                    </p>

                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <template x-if="options">
                            <div class="space-y-4 mb-8">
                                <template x-for="(values, name) in options" :key="name">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2" x-text="name"></label>
                                        <select class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                x-model="selections[name]" required>
                                            <option value="">Selecione...</option>
                                            <template x-for="value in values">
                                                <option :value="value" x-text="value"></option>
                                            </template>
                                        </select>
                                    </div>
                                </template>
                            </div>
                        </template>
                        
                        <input type="hidden" name="selected_options" :value="JSON.stringify(selections)">

                        @auth
                            <button type="submit" 
                                    :disabled="!isValid()"
                                    :class="!isValid() ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700 transform hover:-translate-y-1'"
                                    class="w-full bg-indigo-600 text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 text-lg flex justify-center items-center">
                                
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span>Adicionar ao Carrinho</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center bg-gray-800 text-white font-bold py-4 rounded-xl hover:bg-gray-900 transition">
                                Faça Login para Comprar
                            </a>
                        @endauth
                    </form>

                    <p class="text-xs text-gray-400 mt-4 text-center">
                        Pagamento via PIX • Compra Segura
                    </p>
                </div>
            </div>
        </div>
    </main>

</body>
</html>