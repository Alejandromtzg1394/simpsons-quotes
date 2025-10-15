<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personaje Aleatorio - Los Simpson</title>
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
        <h1 class="text-4xl font-bold text-yellow-600 mb-2">Los Simpson</h1>
        <p class="text-lg text-gray-700" id="page-subtitle">Personaje Aleatorio</p>

        <!-- Botón de traducción -->
        <div class="mt-4">
            <button onclick="translatePage()"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                Traducir a Español
            </button>
        </div>
    </header>

    @if(isset($character) && $character)
    <!-- Tarjeta del Personaje -->
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden character-card">
        <!-- Imagen del Personaje -->
        <div class="bg-yellow-500 p-4">
            <img
                src="{{ $character['image'] ?? $character['optimized_image'] }}"
                alt="{{ $character['name'] }}"
                class="w-48 h-48 mx-auto rounded-full border-4 border-white shadow-lg object-cover"
                onerror="this.onerror=null; this.src='https://cdn.thesimpsonsapi.com/500/character/1.webp';"
            >
        </div>

        <!-- Información del Personaje -->
        <div class="p-6">
            <!-- Nombre y Ocupación -->
            <div class="text-center mb-4">
                <h2 class="text-3xl font-bold text-gray-800" id="character-name">{{ $character['name'] }}</h2>
                <p class="text-lg text-gray-600" id="character-occupation">{{ $character['occupation'] ?? 'Unknown occupation' }}</p>
            </div>

            <!-- Datos Básicos -->
            <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                <div class="text-center">
                    <span class="font-semibold">Edad:</span>
                    <p id="character-age">{{ $character['age'] ?? 'Unknown' }}</p>
                </div>
                <div class="text-center">
                    <span class="font-semibold">Género:</span>
                    <p id="character-gender">{{ $character['gender'] ?? 'Unknown' }}</p>
                </div>
                <div class="text-center">
                    <span class="font-semibold">Estado:</span>
                    <p id="character-status">{{ $character['status'] ?? 'Unknown' }}</p>
                </div>
                <div class="text-center">
                    <span class="font-semibold">Cumpleaños:</span>
                    <p id="character-birthdate">{{ $character['birthdate'] ?? 'Unknown' }}</p>
                </div>
            </div>

            <!-- Descripción -->
            @if(isset($character['description']))
            <div class="mb-4">
                <h3 class="font-semibold text-gray-700 mb-2">Descripción:</h3>
                <p class="text-gray-600 leading-relaxed" id="character-description">{{ $character['description'] }}</p>
            </div>
            @endif

            <!-- Frases Famosas -->
            @if(isset($character['phrases']) && count($character['phrases']) > 0)
            <div class="mb-4">
                <h3 class="font-semibold text-gray-700 mb-2">Frases Famosas:</h3>
                <div class="space-y-2" id="character-phrases">
                    @foreach($character['phrases'] as $phrase)
                    <p class="text-gray-600 bg-yellow-50 p-3 rounded-lg">"{{ $phrase }}"</p>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Primera Aparición -->
            @if(isset($character['first_appearance_ep']))
            <div class="border-t pt-4">
                <h3 class="font-semibold text-gray-700 mb-2">Primera Aparición:</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold text-lg" id="episode-name">{{ $character['first_appearance_ep']['name'] }}</h4>
                    <p class="text-sm text-gray-600 mb-2">
                        Temporada {{ $character['first_appearance_ep']['season'] }} - Episodio {{ $character['first_appearance_ep']['episode_number'] }}
                    </p>
                    <p class="text-gray-600 text-sm" id="episode-synopsis">{{ $character['first_appearance_ep']['synopsis'] ?? 'No synopsis available' }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Datos originales ocultos para traducción -->
    <div id="original-data" style="display: none;">
        <span id="original-name">{{ $character['name'] }}</span>
        <span id="original-occupation">{{ $character['occupation'] ?? 'Unknown occupation' }}</span>
        <span id="original-gender">{{ $character['gender'] ?? 'Unknown' }}</span>
        <span id="original-status">{{ $character['status'] ?? 'Unknown' }}</span>
        <span id="original-description">{{ $character['description'] ?? '' }}</span>
        <span id="original-synopsis">{{ $character['first_appearance_ep']['synopsis'] ?? '' }}</span>
        <span id="original-phrases">{{ json_encode($character['phrases'] ?? []) }}</span>
    </div>
    @else
    <!-- Mensaje de error -->
    <div class="text-center bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <p>No se pudo cargar el personaje. Intenta de nuevo.</p>
    </div>
    @endif

    <!-- Botones de Navegación -->
    <div class="text-center mt-8 space-x-4">
        <a href="{{ route('random') }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300" id="new-random-btn">
            Nuevo Personaje Aleatorio
        </a>
        <!--Galeria-->
        <a href="{{ route('gallery') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300" id="view-gallery-btn">
            Ver Galería
        </a>
        <!--Localidades-->
        <a href="{{ route('locations') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300" id="view-locations-btn">
            Ver locaciones
        </a>
    </div>
</div>
<script>

    // Datos originales para traducción
    const originalData = {
        name: document.getElementById('original-name')?.textContent || '',
        occupation: document.getElementById('original-occupation')?.textContent || '',
        gender: document.getElementById('original-gender')?.textContent || '',
        status: document.getElementById('original-status')?.textContent || '',
        description: document.getElementById('original-description')?.textContent || '',
        synopsis: document.getElementById('original-synopsis')?.textContent || '',
        phrases: JSON.parse(document.getElementById('original-phrases')?.textContent || '[]')
    };

    // Campos a traducir con sus IDs
    const fields = [
        { id: 'character-name', key: 'name' },
        { id: 'character-occupation', key: 'occupation' },
        { id: 'character-gender', key: 'gender' },
        { id: 'character-status', key: 'status' },
        { id: 'character-description', key: 'description' },
        { id: 'episode-synopsis', key: 'synopsis' }
    ];


    // Traducir página
    async function translatePage() {
        if (!simpsonTranslator) {
            alert('Error: Sistema de traducción no cargado');
            return;
        }

        simpsonTranslator.showLoading('button[onclick="translatePage()"]');

        try {
            // Traducir campos de texto
            for (const field of fields) {
                if (originalData[field.key]) {
                    document.getElementById(field.id).textContent =
                        await simpsonTranslator.translateText(originalData[field.key]);
                }
            }
            // Traducir frases
            await translatePhrases();

            simpsonTranslator.showMessage('Traducción completada', 'success');
        } catch (error) {
            console.error('Error:', error);
            simpsonTranslator.showMessage('Error en traducción', 'error');
        } finally {
            simpsonTranslator.hideLoading('button[onclick="translatePage()"]', 'Traducir a Español');
        }
    }

    // Traducir frases famosas
    async function translatePhrases() {
        if (!originalData.phrases?.length) return;

        const container = document.getElementById('character-phrases');
        if (!container) return;

        container.innerHTML = '';

        for (const phrase of originalData.phrases) {
            const translated = await simpsonTranslator.translatePhrase(phrase);
            const element = document.createElement('p');
            element.className = 'text-gray-600 bg-yellow-50 p-3 rounded-lg';
            element.textContent = `"${translated}"`;
            container.appendChild(element);
        }
    }

    // Auto-traducir
    document.addEventListener('DOMContentLoaded', () => {
        simpsonTranslator?.autoTranslateIfSpanish(translatePage);
    });
</script>
</body>
</html>
