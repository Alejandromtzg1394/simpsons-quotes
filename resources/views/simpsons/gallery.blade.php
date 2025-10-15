<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Personajes - Los Simpson</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/translations.js') }}"></script>
    <style>
        .character-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .character-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-yellow-100 min-h-screen">
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <header class="text-center mb-8">
        <h1 class="text-4xl font-bold text-yellow-600 mb-2" id="gallery-title">Galería de Personajes</h1>
        <p class="text-lg text-gray-700 mb-4" id="gallery-subtitle">Descubre los personajes de Springfield</p>

        <!-- Botón de traducción -->
        <div class="mt-4">
                <a href="{{ route('index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-300" id="back-home-btn">
                    ← Volver al inicio
                </a>
            <button onclick="translateGallery()"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                Traducir Galería
            </button>
        </div>


    </header>

    <!-- Grid de Personajes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" id="characters-grid">
        @forelse($characters as $character)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden character-card" data-character-id="{{ $character['id'] }}">
            <!-- Imagen con fallback -->
            <div class="bg-yellow-500 p-4 h-64 flex items-center justify-center">
                <img
                    src="https://cdn.thesimpsonsapi.com/200/character/{{ $character['id'] }}.webp"
                    alt="{{ $character['name'] }}"
                    class="max-w-full max-h-48 object-cover rounded-lg"
                    onerror="this.onerror=null; this.src='https://cdn.thesimpsonsapi.com/500/character/1.webp';"
                >
            </div>

            <!-- Información -->
            <div class="p-4">
                <h3 class="text-xl font-bold text-gray-800 mb-2 character-name">{{ $character['name'] }}</h3>

                <p class="text-gray-600 mb-2">
                    <span class="font-semibold occupation-label">Ocupación:</span>
                    <span class="character-occupation">{{ $character['occupation'] ?? 'No especificada' }}</span>
                </p>

                @if(isset($character['age']))
                <p class="text-gray-600 mb-3">
                    <span class="font-semibold age-label">Edad:</span>
                    <span class="character-age">{{ $character['age'] }}</span> <span class="age-unit">años</span>
                </p>
                @endif

                <!-- Botón de detalles -->
                <div class="text-center">
                    <a href="{{ route('character', ['id' => $character['id']]) }}"
                       class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-300 view-details-btn">
                        Ver Detalles
                    </a>
                </div>
            </div>

            <!-- Datos originales ocultos para traducción -->
            <div class="original-data" style="display: none;">
                <span class="original-name">{{ $character['name'] }}</span>
                <span class="original-occupation">{{ $character['occupation'] ?? 'No especificada' }}</span>
                @if(isset($character['age']))
                <span class="original-age">{{ $character['age'] }}</span>
                @endif
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
           class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300" id="new-gallery-btn">
            Nueva Galería Aleatoria
        </a>
    </div>

    <!-- Información de la API -->
    <footer class="text-center mt-8 text-sm text-gray-500">
        <p id="api-info">Datos proporcionados por The Simpsons API</p>
        <p class="mt-2">
            <strong>{{ count($characters) }}</strong> <span id="characters-count-text">personajes mostrados</span>
        </p>
    </footer>
</div>

<script>
    // Elementos a traducir
    const elements = [
        { id: 'gallery-title' },
        { id: 'gallery-subtitle' },
        { id: 'back-home-btn' },
        { id: 'new-gallery-btn' },
        { id: 'api-info' },
        { id: 'characters-count-text' }
    ];

    // Traducir galería
    async function translateGallery() {
        if (!simpsonTranslator) {
            alert('Error: Sistema de traducción no cargado');
            return;
        }

        simpsonTranslator.showLoading('button[onclick="translateGallery()"]');

        try {
            // Traducir elementos estáticos
            for (const element of elements) {
                const el = document.getElementById(element.id);
                if (el) {
                    el.textContent = await simpsonTranslator.translateText(el.textContent);
                }
            }

            // Traducir tarjetas de personajes
            await translateCharacterCards();

            simpsonTranslator.showMessage('Galería traducida', 'success');
        } catch (error) {
            console.error('Error:', error);
            simpsonTranslator.showMessage('Error en traducción', 'error');
        } finally {
            simpsonTranslator.hideLoading('button[onclick="translateGallery()"]', 'Traducir Galería');
        }
    }

    // Traducir tarjetas de personajes
    async function translateCharacterCards() {
        const cards = document.querySelectorAll('.character-card');

        for (const card of cards) {
            const name = card.querySelector('.character-name');
            const occupation = card.querySelector('.character-occupation');
            const originalName = card.querySelector('.original-name');
            const originalOccupation = card.querySelector('.original-occupation');

            if (name && originalName) {
                name.textContent = await simpsonTranslator.translateText(originalName.textContent);
            }

            if (occupation && originalOccupation) {
                occupation.textContent = await simpsonTranslator.translateText(originalOccupation.textContent);
            }
        }
    }

    // Auto-traducir
    document.addEventListener('DOMContentLoaded', () => {
        simpsonTranslator?.autoTranslateIfSpanish(translateGallery);
    });
</script>
</body>
</html>
