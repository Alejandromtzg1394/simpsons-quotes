@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-center simpsons-red">Mis Frases Favoritas</h2>

    @if($favorites->count() > 0)
        <div class="grid gap-6 md:grid-cols-2">
            @foreach($favorites as $favorite)
                <div class="border-2 border-green-400 rounded-lg p-4 bg-green-50 relative">
                    <p class="italic mb-3">"{{ $favorite->quote }}"</p>
                    <p class="font-bold simpsons-red">- {{ $favorite->character }}</p>
                    
                    @if($favorite->image)
                        <div class="mt-2 flex justify-center">
                            <img src="{{ $favorite->image }}" alt="{{ $favorite->character }}" 
                                 class="rounded-lg shadow-md max-w-20">
                        </div>
                    @endif
                    
                    <p class="text-sm text-gray-500 mt-2">{{ $favorite->created_at->format('d/m/Y H:i') }}</p>
                    
                    <form action="{{ route('favorites.delete', $favorite->id) }}" method="POST" class="absolute top-2 right-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700" 
                                onclick="return confirm('¿Eliminar esta frase de favoritos?')">
                            🗑️
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-600 text-lg">Aún no tienes frases favoritas guardadas.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                Buscar Frases
            </a>
        </div>
    @endif
</div>
@endsection