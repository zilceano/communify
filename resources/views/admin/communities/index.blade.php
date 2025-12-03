<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Comunidades') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dono (Criador)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stats</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($communities as $community)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            @if($community->profile_image)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/'.$community->profile_image) }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">{{ substr($community->name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $community->name }}</div>
                                            <div class="text-sm text-gray-500">/c/{{ $community->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $community->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $community->followers_count }} seguidores<br>
                                    {{ $community->products_count }} produtos
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('community.show', $community->slug) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Visitar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $communities->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>