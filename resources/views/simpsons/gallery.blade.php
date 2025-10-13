<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Personajes - Los Simpson</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <header class="text-center mb-8">
            <h1 class="text-4xl font-bold text-yellow-600 mb-2">Galería de Personajes</h1>
            <p class="text-lg text-gray-700 mb-4">Descubre los personajes de Springfield</p>
            <a href="{{ route('index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-300">
                ← Volver al inicio
            </a>
        </header>

        <!-- Grid de Personajes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @forelse($characters as $character)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">

            
                <!-- Imagen con fallback -->
                <div class="bg-yellow-500 p-4 h-64 flex items-center justify-center">
                    <!-- En gallery.blade.php -->
                        <img 
                            src="https://cdn.thesimpsonsapi.com/200/character/{{ $character['id'] }}.webp" 
                            alt="{{ $character['name'] }}"
                            class="max-w-full max-h-48 object-cover rounded-lg">
                </div>
                
                <!-- Información -->
                <div class="p-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $character['name'] }}</h3>
                    
                    <p class="text-gray-600 mb-2">
                        <span class="font-semibold">Ocupación:</span> 
                        {{ $character['occupation'] ?? 'No especificada' }}
                    </p>
                    
                    @if(isset($character['age']))
                    <p class="text-gray-600 mb-3">
                        <span class="font-semibold">Edad:</span> 
                        {{ $character['age'] }} años
                    </p>
                    @endif
                    
                    <!-- Botón de detalles -->
                    <div class="text-center">
                        <a href="{{ route('character', ['id' => $character['id']]) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                            👁️ Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <!-- Mensaje si no hay personajes -->
            <div class="col-span-full text-center py-8">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <p>No se pudieron cargar los personajes. Intenta recargar la página.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Botón para recargar galería -->
        <div class="text-center">
            <a href="{{ route('gallery') }}" 
               class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                🔄 Nueva Galería Aleatoria
            </a>
        </div>

        <!-- Información de la API -->
        <footer class="text-center mt-8 text-sm text-gray-500">
            <p>Datos proporcionados por The Simpsons API</p>
            <p class="mt-2">
                <strong>{{ count($characters) }}</strong> personajes mostrados
            </p>
        </footer>
    </div>
</body>
</html>