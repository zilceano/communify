<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pedido #{{ $order->id }} 
            @if($order->status == 'draft')
                - <span class="text-indigo-600">Finalizar Compra</span>
            @else
                - <span class="uppercase">{{ str_replace('_', ' ', $order->status) }}</span>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($order->status == 'draft')
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <div class="md:col-span-2 space-y-6">
                        
                        <div class="bg-white p-6 shadow sm:rounded-lg">
                            <h3 class="font-bold text-gray-800 mb-4">Itens do Pedido</h3>
                            
                            @foreach($order->items as $item)
                                <div class="flex items-center border-b border-gray-100 pb-4 mb-4 last:border-0 last:mb-0">
                                    <img src="{{ asset('storage/' . $item->product->image_mockup) }}" class="w-20 h-20 rounded object-cover mr-4 border border-gray-200">
                                    
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-800 text-sm">{{ $item->product->name }}</h4>
                                        <p class="text-xs text-gray-500 mb-2">
                                            {{ $item->product->baseProduct->name }}
                                            @if($item->selected_options_json)
                                                <br>
                                                @foreach($item->selected_options_json as $key => $val)
                                                    <span class="inline-block bg-gray-100 px-1 rounded text-[10px] uppercase font-semibold mr-1 mt-1">
                                                        {{ $key }}: {{ $val }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        </p>
                                        
                                        <div class="flex items-center">
                                            <form action="{{ route('order.item.update', $item->id) }}" method="POST" class="inline-flex items-center border border-gray-300 rounded-md">
                                                @csrf
                                                @method('PATCH')
                                                
                                                <button type="submit" name="action" value="decrease" 
                                                        class="px-2 py-1 text-gray-600 hover:bg-gray-100 border-r border-gray-300 text-sm {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                                                    -
                                                </button>
                                                
                                                <span class="px-3 py-1 text-sm font-bold text-gray-700 bg-white">
                                                    {{ $item->quantity }}
                                                </span>
                                                
                                                <button type="submit" name="action" value="increase" 
                                                        class="px-2 py-1 text-gray-600 hover:bg-gray-100 border-l border-gray-300 text-sm">
                                                    +
                                                </button>
                                            </form>

                                            <form action="{{ route('order.item.remove', $item->id) }}" method="POST" class="ml-3">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 p-1 rounded hover:bg-red-50 transition" title="Remover item do carrinho">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="ml-4 text-right">
                                        <div class="font-bold text-gray-900">R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</div>
                                        @if($item->quantity > 1)
                                            <div class="text-xs text-gray-400">R$ {{ number_format($item->price, 2, ',', '.') }} un.</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form id="checkout-form" action="{{ route('order.finalize', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="bg-white p-6 shadow sm:rounded-lg">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Endereço de Entrega
                                </h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-gray-600 uppercase">Nome Completo</label>
                                        <input type="text" name="full_name" required value="{{ auth()->user()->full_name ?? auth()->user()->name }}" class="w-full rounded border-gray-300 text-sm">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-gray-600 uppercase">CPF</label>
                                            <input type="text" name="cpf" required value="{{ auth()->user()->cpf }}" class="w-full rounded border-gray-300 text-sm" placeholder="000.000.000-00">
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-gray-600 uppercase">CEP</label>
                                            <input type="text" name="address_zip" required value="{{ auth()->user()->address_zip }}" class="w-full rounded border-gray-300 text-sm">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="col-span-2">
                                            <label class="text-xs font-bold text-gray-600 uppercase">Cidade</label>
                                            <input type="text" name="address_city" required value="{{ auth()->user()->address_city }}" class="w-full rounded border-gray-300 text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-gray-600 uppercase">UF</label>
                                            <input type="text" name="address_state" required maxlength="2" value="{{ auth()->user()->address_state }}" class="w-full rounded border-gray-300 text-sm">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="col-span-2">
                                            <label class="text-xs font-bold text-gray-600 uppercase">Rua</label>
                                            <input type="text" name="address_street" required value="{{ auth()->user()->address_street }}" class="w-full rounded border-gray-300 text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-gray-600 uppercase">Número</label>
                                            <input type="text" name="address_number" required value="{{ auth()->user()->address_number }}" class="w-full rounded border-gray-300 text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-gray-600 uppercase">Complemento</label>
                                        <input type="text" name="address_complement" value="{{ auth()->user()->address_complement }}" class="w-full rounded border-gray-300 text-sm">
                                    </div>
                                </div>
                            </div>
                        
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white p-6 shadow sm:rounded-lg" x-data="{ 
                            donation: 0, 
                            shipping: 30.00, 
                            subtotal: {{ $order->items->sum(fn($item) => $item->price * $item->quantity) }} 
                        }">
                            <h3 class="font-bold text-gray-800 mb-4">Resumo do Pagamento</h3>

                            <div class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                                <label class="block text-sm font-bold text-indigo-800 mb-2">Apoie a Causa (Opcional)</label>
                                <div class="flex items-center">
                                    <span class="text-gray-500 mr-2">R$</span>
                                    <input type="number" name="donation_amount" min="0" step="1.00" x-model.number="donation"
                                           class="w-full rounded border-indigo-200 focus:ring-indigo-500">
                                </div>
                                <p class="text-xs text-indigo-600 mt-1">Todo valor ajuda!</p>
                            </div>

                            <div class="space-y-2 text-sm border-t pt-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal Itens:</span>
                                    <span class="font-bold">R$ <span x-text="subtotal.toFixed(2).replace('.', ',')"></span></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Frete (Fixo):</span>
                                    <span class="font-bold text-gray-800">R$ 30,00</span>
                                </div>
                                <div class="flex justify-between text-indigo-600" x-show="donation > 0">
                                    <span>Doação:</span>
                                    <span class="font-bold">R$ <span x-text="parseFloat(donation).toFixed(2)"></span></span>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Total:</span>
                                <span class="text-2xl font-bold text-indigo-600">
                                    R$ <span x-text="(subtotal + shipping + parseFloat(donation || 0)).toFixed(2)"></span>
                                </span>
                            </div>

                            <button type="submit" form="checkout-form" class="w-full mt-6 bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 shadow-lg transition">
                                Finalizar e Pagar
                            </button>
                            <p class="text-xs text-center text-gray-400 mt-2">Ao clicar, você confirmará o pedido.</p>
                        </div>
                    </div>
                    </form> </div>

            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Pedido Confirmado</h3>
                        
                        <div class="space-y-4 mb-4 border-b pb-4">
                            @foreach($order->items as $item)
                                <div class="flex items-start">
                                    <img src="{{ asset('storage/' . $item->product->image_mockup) }}" class="w-12 h-12 rounded object-cover mr-3">
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-sm">{{ $item->product->name }}</h4>
                                        <p class="text-xs text-gray-500">
                                            {{ $item->product->baseProduct->name }} <br>
                                            @if($item->quantity > 1)
                                                <span class="font-bold">Qtd: {{ $item->quantity }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="ml-auto font-bold text-sm">
                                        R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-4 bg-gray-50 p-3 rounded">
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-1">Entregar em:</h4>
                            <p class="text-sm text-gray-800">
                                {{ $order->user->address_street }}, {{ $order->user->address_number }}<br>
                                {{ $order->user->address_city }} - {{ $order->user->address_state }}<br>
                                CEP: {{ $order->user->address_zip }}
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200 space-y-2">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Frete</span>
                                <span>R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</span>
                            </div>
                            @if($order->donation_amount > 0)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Doação</span>
                                    <span>R$ {{ number_format($order->donation_amount, 2, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t">
                                <span>Total a Pagar</span>
                                <span>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Pagamento via PIX</h3>

                        @if($order->status == 'awaiting_payment')
                            @if(!$order->proof_of_payment)
                                <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-6">
                                    <p class="text-xs text-gray-500 mb-2">Escaneie o QR Code abaixo</p>
                                    <div class="w-32 h-32 bg-gray-800 mx-auto mb-2 flex items-center justify-center text-white text-xs">
                                        [QR CODE FAKE]
                                    </div>
                                    <p class="text-xs text-indigo-600 mt-2 font-bold">Valor exato: R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                                </div>

                                <form action="{{ route('order.upload_proof', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Envie o Comprovante</label>
                                    <input type="file" name="proof" required accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mb-4">
                                    <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700">Enviar Comprovante</button>
                                </form>
                            @else
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                    <p class="text-sm text-yellow-700">Comprovante enviado! Estamos analisando.</p>
                                </div>
                                <img src="{{ asset('storage/' . $order->proof_of_payment) }}" class="mt-4 w-full rounded border">
                            @endif
                        @elseif($order->status == 'paid')
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                                <h3 class="text-sm font-medium text-green-800">Pagamento Aprovado!</h3>
                                <p class="text-xs text-green-700">Seu pedido será enviado em breve.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>