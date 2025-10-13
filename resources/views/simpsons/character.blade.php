<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $character['name'] }} - Los Simpson</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Navegación -->
        <div class="mb-6">
            <a href="{{ route('index') }}" class="text-blue-600 hover:underline">← Inicio</a>
            <span class="mx-2">/</span>
            <a href="{{ route('gallery') }}" class="text-blue-600 hover:underline">Galería</a>
            <span class="mx-2">/</span>
            <span class="text-gray-600">{{ $character['name'] }}</span>
        </div>

        @if(isset($character) && $character)
            <!-- Misma tarjeta detallada que en index.blade.php -->
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Imagen -->
                <div class="bg-yellow-500 p-6 text-center">
                    <img 
                        src="{{ $character['optimized_image'] }}" 
                        alt="{{ $character['name'] }}"
                        class="w-64 h-64 mx-auto rounded-full border-4 border-white shadow-lg object-cover"
                        onerror="this.onerror=null; this.src='https://cdn.thesimpontagol.com/98b/character/1.webp';"
                    >
                </div>

                <!-- Información -->
                <div class="p-6">
                    <h1 class="text-4xl font-bold text-gray-800 text-center mb-2">{{ $character['name'] }}</h1>
                    <p class="text-xl text-gray-600 text-center mb-6">{{ $character['occupation'] ?? 'Ocupación no especificada' }}</p>

                    <!-- Datos en grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 text-sm">
                        <div class="text-center">
                            <span class="font-semibold block">Edad</span>
                            <p>{{ $character['age'] ?? '?' }} años</p>
                        </div>
                        <div class="text-center">
                            <span class="font-semibold block">Género</span>
                            <p>{{ $character['gender'] ?? 'No especificado' }}</p>
                        </div>
                        <div class="text-center">
                            <span class="font-semibold block">Estado</span>
                            <p>{{ $character['status'] ?? 'Desconocido' }}</p>
                        </div>
                        <div class="text-center">
                            <span class="font-semibold block">Cumpleaños</span>
                            <p>{{ $character['birthdate'] ?? 'Desconocido' }}</p>
                        </div>
                    </div>

                    <!-- Descripción -->
                    @if(isset($character['description']))
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-3">Descripción</h2>
                        <p class="text-gray-600 leading-relaxed">{{ $character['description'] }}</p>
                    </div>
                    @endif

                    <!-- Frases -->
                    @if(isset($character['phrases']) && count($character['phrases']) > 0)
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-3">Frases Famosas</h2>
                        <div class="space-y-3">
                            @foreach($character['phrases'] as $phrase)
                            <blockquote class="text-gray-600 bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                                "{{ $phrase }}"
                            </blockquote>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Primera Aparición -->
                    @if(isset($character['first_appearance_ep']))
                    <div class="border-t pt-6">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-3">Primera Aparición</h2>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-bold text-lg text-yellow-600">{{ $character['first_appearance_ep']['name'] }}</h3>
                            <p class="text-sm text-gray-600 mb-2">
                                Temporada {{ $character['first_appearance_ep']['season'] }} - Episodio {{ $character['first_appearance_ep']['episode_number'] }}
                                @if(isset($character['first_appearance_ep']['airdate']))
                                ({{ \Carbon\Carbon::parse($character['first_appearance_ep']['airdate'])->format('d/m/Y') }})
                                @endif
                            </p>
                            <p class="text-gray-600">{{ $character['first_appearance_ep']['synopsis'] ?? 'Sin sinopsis disponible' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Error -->
            <div class="text-center bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <p>No se pudo cargar la información del personaje.</p>
            </div>
        @endif

        <!-- Botones de acción -->
        <div class="text-center mt-8 space-x-4">
            <a href="{{ route('random') }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                🔄 Nuevo Aleatorio
            </a>
            <a href="{{ route('gallery') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                🖼️ Ver Galería
            </a>
        </div>
    </div>
</body>
</html>