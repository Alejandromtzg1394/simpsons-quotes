<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lugares de Springfield - Los Simpson</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/translations.js') }}"></script>
    <style>
        .location-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .location-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-yellow-100 min-h-screen">
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <header class="text-center mb-8">
        <h1 class="text-4xl font-bold text-yellow-600 mb-2" id="locations-title">Lugares de Springfield</h1>
        <p class="text-lg text-gray-700 mb-4" id="locations-subtitle">Descubre los lugares icónicos de Los Simpson</p>

        <!-- Botón de traducción -->
        <div class="mt-4">
            <button onclick="translateLocations()"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                Traducir Lugares
            </button>
        </div>

        <div class="mt-4">
            <a href="{{ route('index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-300" id="back-home-btn">
                ← Volver al inicio
            </a>
        </div>
    </header>

    <!-- Información de paginación -->
    <div class="text-center mb-6 text-gray-600">
        <p id="pagination-info">
            Mostrando página {{ $currentPage }} de {{ $totalPages }} -
            <strong>{{ $totalLocations }}</strong> <span id="locations-count-text">lugares en total</span>
        </p>
    </div>

    <!-- Grid de Lugares -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" id="locations-grid">
        @forelse($locations as $location)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden location-card" data-location-id="{{ $location['id'] }}">
            <!-- Imagen CORREGIDA - usa directamente el ID -->
            <div class="bg-yellow-500 p-4 h-64 flex items-center justify-center">
                <img
                    src="https://cdn.thesimpsonsapi.com/500/location/{{ $location['id'] }}.webp"
                    alt="{{ $location['name'] }}"
                    class="max-w-full max-h-48 object-cover rounded-lg"
                    onerror="this.onerror=null; this.src='https://cdn.thesimpsonsapi.com/600/location/1.webp';"
                >
            </div>

            <!-- Información -->
            <div class="p-4">
                <h3 class="text-xl font-bold text-gray-800 mb-2 location-name">{{ $location['name'] }}</h3>

                <p class="text-gray-600 mb-2">
                    <span class="font-semibold town-label">Ciudad:</span>
                    <span class="location-town">{{ $location['town'] ?? 'Springfield' }}</span>
                </p>

                <p class="text-gray-600 mb-3">
                    <span class="font-semibold use-label">Uso:</span>
                    <span class="location-use">{{ $location['use'] ?? 'No especificado' }}</span>
                </p>

                <!-- Botón de detalles PARA LOCATIONS -->
                <div class="text-center">
                    <a href="{{ route('location.details', ['id' => $location['id']]) }}"
                       class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-300 view-details-btn">
                        Ver Detalles
                    </a>
                </div>

            </div>

            <!-- Datos originales ocultos para traducción -->
            <div class="original-data" style="display: none;">
                <span class="original-name">{{ $location['name'] }}</span>
                <span class="original-town">{{ $location['town'] ?? 'Springfield' }}</span>
                <span class="original-use">{{ $location['use'] ?? 'No especificado' }}</span>
                @if(isset($location['description']))
                <span class="original-description">{{ $location['description'] }}</span>
                @endif
            </div>
        </div>
        @empty
        <!-- Mensaje si no hay lugares -->
        <div class="col-span-full text-center py-8">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <p>No se pudieron cargar los lugares. Intenta recargar la página.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="flex justify-center items-center space-x-4 mb-8">
        <!-- Botón Anterior -->
        @if($currentPage > 1)
        <a href="{{ route('locations', ['page' => $currentPage - 1]) }}"
           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
            ← Anterior
        </a>
        @else
        <span class="bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed">
                ← Anterior
            </span>
        @endif

        <!-- Información de página -->
        <span class="text-gray-600 font-medium">
            Página {{ $currentPage }} de {{ $totalPages }}
        </span>

        <!-- Botón Siguiente -->
        @if($currentPage < $totalPages)
        <a href="{{ route('locations', ['page' => $currentPage + 1]) }}"
           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
            Siguiente →
        </a>
        @else
        <span class="bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed">
                Siguiente →
            </span>
        @endif
    </div>

    <!-- Navegación rápida de páginas -->
    <div class="text-center mb-8">
        <div class="inline-flex flex-wrap gap-2 justify-center">
            @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
            @if($i == $currentPage)
            <span class="bg-yellow-500 text-white font-bold py-2 px-4 rounded">
                        {{ $i }}
                    </span>
            @else
            <a href="{{ route('locations', ['page' => $i]) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                {{ $i }}
            </a>
            @endif
            @endfor
        </div>
    </div>

    <!-- Información de la API -->
    <footer class="text-center mt-8 text-sm text-gray-500">
        <p id="api-info">Datos proporcionados por The Simpsons API</p>
    </footer>
</div>

<script>
    // Elementos a traducir
    const elements = [
        { id: 'locations-title' },
        { id: 'locations-subtitle' },
        { id: 'back-home-btn' },
        { id: 'pagination-info' },
        { id: 'locations-count-text' },
        { id: 'api-info' }
    ];

    // Traducir locations
    async function translateLocations() {
        if (!simpsonTranslator) {
            alert('Error: Sistema de traducción no cargado');
            return;
        }

        simpsonTranslator.showLoading('button[onclick="translateLocations()"]');

        try {
            // Traducir elementos estáticos
            for (const element of elements) {
                const el = document.getElementById(element.id);
                if (el) {
                    el.textContent = await simpsonTranslator.translateText(el.textContent);
                }
            }

            // Traducir tarjetas de lugares
            await translateLocationCards();

            simpsonTranslator.showMessage('Lugares traducidos', 'success');
        } catch (error) {
            console.error('Error:', error);
            simpsonTranslator.showMessage('Error en traducción', 'error');
        } finally {
            simpsonTranslator.hideLoading('button[onclick="translateLocations()"]', 'Traducir Lugares');
        }
    }

    // Traducir tarjetas de lugares
    async function translateLocationCards() {
        const cards = document.querySelectorAll('.location-card');

        for (const card of cards) {
            const name = card.querySelector('.location-name');
            const town = card.querySelector('.location-town');
            const use = card.querySelector('.location-use');
            const description = card.querySelector('.location-description');

            const originalName = card.querySelector('.original-name');
            const originalTown = card.querySelector('.original-town');
            const originalUse = card.querySelector('.original-use');
            const originalDescription = card.querySelector('.original-description');

            if (name && originalName) {
                name.textContent = await simpsonTranslator.translateText(originalName.textContent);
            }

            if (town && originalTown) {
                town.textContent = await simpsonTranslator.translateText(originalTown.textContent);
            }

            if (use && originalUse) {
                use.textContent = await simpsonTranslator.translateText(originalUse.textContent);
            }

            if (description && originalDescription) {
                description.textContent = await simpsonTranslator.translateText(originalDescription.textContent);
            }
        }
    }

    // Auto-traducir
    document.addEventListener('DOMContentLoaded', () => {
        simpsonTranslator?.autoTranslateIfSpanish(translateLocations);
    });
</script>
</body>
</html>
