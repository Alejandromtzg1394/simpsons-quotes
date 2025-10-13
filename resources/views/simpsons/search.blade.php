@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-center simpsons-red">Buscar Frases por Personaje</h2>

    <!-- Selector de personajes -->
    <div class="mb-8">
        <form action="{{ route('search') }}" method="GET" class="mb-6">
            <div class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Selecciona un personaje:
                    </label>
                    <select name="character" onchange="this.form.submit()" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        @foreach($availableCharacters as $key => $name)
                            <option value="{{ $key }}" {{ $character == $key ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg h-fit">
                        🔍 Buscar
                    </button>
                </div>
            </div>
        </form>

        <!-- Búsqueda por texto (alternativa) -->
        <div class="mt-4">
            <form action="{{ route('search') }}" method="GET">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    O busca por nombre:
                </label>
                <div class="flex gap-4">
                    <input type="text" name="character" value="{{ $character }}" 
                           placeholder="Escribe el nombre del personaje..." 
                           class="flex-1 p-3 border border-gray-300 rounded-lg">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg">
                        🔍 Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    @if($quotes && count($quotes) > 0)
        <div class="mb-4">
            <h3 class="text-lg font-semibold simpsons-red">
                Mostrando {{ count($quotes) }} frases de {{ $availableCharacters[$character] ?? $character }}
            </h3>
        </div>
        
        <div class="grid gap-6 md:grid-cols-2">
            @foreach($quotes as $quote)
                <div class="border-2 border-yellow-400 rounded-lg p-4 bg-yellow-50">
                    <p class="italic mb-3 text-lg">"{{ $quote['quote'] }}"</p>
                    <p class="font-bold simpsons-red text-md">- {{ $quote['character'] }}</p>
                    
                    @if(isset($quote['image']))
                        <div class="mt-3 flex justify-center">
                            <img src="{{ $quote['image'] }}" alt="{{ $quote['character'] }}" 
                                 class="rounded-lg shadow-md max-w-24">
                        </div>
                    @endif
                    
                    <form action="{{ route('favorites.save') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="quote" value="{{ $quote['quote'] }}">
                        <input type="hidden" name="character" value="{{ $quote['character'] }}">
                        <input type="hidden" name="image" value="{{ $quote['image'] ?? '' }}">
                        <input type="hidden" name="character_direction" value="{{ $quote['characterDirection'] ?? '' }}">
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded">
                            💾 Guardar en Favoritos
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 bg-white rounded-lg shadow">
            <p class="text-gray-600 text-lg">No se encontraron frases para "{{ $availableCharacters[$character] ?? $character }}"</p>
            <p class="text-sm text-gray-500 mt-2">Intenta con otro personaje de la lista</p>
        </div>
    @endif
</div>

<script>
// Auto-submit del select cuando cambia
document.querySelector('select[name="character"]').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endsection