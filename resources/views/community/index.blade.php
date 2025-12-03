<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Explorar Comunidades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($communities as $community)
                    <a href="{{ route('community.show', $community->slug) }}" class="block group">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition h-full flex flex-col">
                            <div class="h-32 bg-gray-200 relative">
                                @if($community->cover_image)
                                    <img src="{{ asset('storage/' . $community->cover_image) }}" class="w-full h-full object-cover group-hover:opacity-90 transition">
                                @endif
                            </div>
                            <div class="p-6 pt-10 relative flex-1 flex flex-col">
                                <div class="absolute -top-8 left-6 w-16 h-16 bg-white rounded-full border-4 border-white overflow-hidden shadow">
                                    @if($community->profile_image)
                                        <img src="{{ asset('storage/' . $community->profile_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-100 flex items-center justify-center font-bold text-gray-400">
                                            {{ substr($community->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $community->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1 mb-4 flex-1 line-clamp-3">{{ $community->description }}</p>
                                <div class="text-indigo-600 text-sm font-semibold">Visitar &rarr;</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 text-center py-10">
                        <p class="text-gray-500">Nenhuma comunidade encontrada.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>