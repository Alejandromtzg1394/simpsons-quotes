<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $character['name'] }} - Los Simpson</title>
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

<div class="text-center mb-6"">
    <!-- Navegación -->
    <div class="mb-6">
        <a href="{{ route('index') }}" class="text-blue-600 hover:underline">← Inicio</a>
        <span class="mx-2">/</span>
        <a href="{{ route('gallery') }}" class="text-blue-600 hover:underline">Galería</a>
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ $character['name'] }}</span>
    </div>

    <!-- Botón de traducción -->
    <div class="text-center mb-6">
        <button onclick="translateCharacterPage()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
            Traducir a Español
        </button>
    </div>

    @if(isset($character) && $character)
    <!-- Tarjeta detallada del personaje -->
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden character-card">
        <!-- Imagen -->
        <div class="bg-yellow-500 p-6 text-center">
            <img
                src="{{ $character['optimized_image'] }}"
                alt="{{ $character['name'] }}"
                class="w-64 h-64 mx-auto rounded-full border-4 border-white shadow-lg object-cover"
                onerror="this.onerror=null; this.src='https://cdn.thesimpsonsapi.com/500/character/1.webp';"
            >
        </div>

        <!-- Información -->
        <div class="p-6">
            <h1 class="text-4xl font-bold text-gray-800 text-center mb-2" id="character-name">{{ $character['name'] }}</h1>
            <p class="text-xl text-gray-600 text-center mb-6" id="character-occupation">{{ $character['occupation'] ?? 'Ocupación no especificada' }}</p>

            <!-- Datos en grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 text-sm">
                <div class="text-center">
                    <span class="font-semibold block">Edad</span>
                    <p id="character-age">{{ $character['age'] ?? '?' }} años</p>
                </div>
                <div class="text-center">
                    <span class="font-semibold block">Género</span>
                    <p id="character-gender">{{ $character['gender'] ?? 'No especificado' }}</p>
                </div>
                <div class="text-center">
                    <span class="font-semibold block">Estado</span>
                    <p id="character-status">{{ $character['status'] ?? 'Desconocido' }}</p>
                </div>
                <div class="text-center">
                    <span class="font-semibold block">Cumpleaños</span>
                    <p id="character-birthdate">{{ $character['birthdate'] ?? 'Desconocido' }}</p>
                </div>
            </div>

            <!-- Descripción -->
            @if(isset($character['description']))
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-700 mb-3">Descripción</h2>
                <p class="text-gray-600 leading-relaxed" id="character-description">{{ $character['description'] }}</p>
            </div>
            @endif

            <!-- Frases -->
            @if(isset($character['phrases']) && count($character['phrases']) > 0)
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-700 mb-3">Frases Famosas</h2>
                <div class="space-y-3" id="character-phrases">
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
                    <h3 class="font-bold text-lg text-yellow-600" id="episode-name">{{ $character['first_appearance_ep']['name'] }}</h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Temporada {{ $character['first_appearance_ep']['season'] }} - Episodio {{ $character['first_appearance_ep']['episode_number'] }}
                        @if(isset($character['first_appearance_ep']['airdate']))
                        ({{ \Carbon\Carbon::parse($character['first_appearance_ep']['airdate'])->format('d/m/Y') }})
                        @endif
                    </p>
                    <p class="text-gray-600" id="episode-synopsis">{{ $character['first_appearance_ep']['synopsis'] ?? 'Sin sinopsis disponible' }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Datos originales ocultos para traducción -->
    <div id="original-data" style="display: none;">
        <span id="original-name">{{ $character['name'] }}</span>
        <span id="original-occupation">{{ $character['occupation'] ?? 'Ocupación no especificada' }}</span>
        <span id="original-gender">{{ $character['gender'] ?? 'No especificado' }}</span>
        <span id="original-status">{{ $character['status'] ?? 'Desconocido' }}</span>
        <span id="original-description">{{ $character['description'] ?? '' }}</span>
        <span id="original-synopsis">{{ $character['first_appearance_ep']['synopsis'] ?? '' }}</span>
        <span id="original-phrases">{{ json_encode($character['phrases'] ?? []) }}</span>
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
           class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300" id="new-random-btn">
            Nuevo Aleatorio
        </a>
        <a href="{{ route('gallery') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300" id="view-gallery-btn">
            Ver Galería
        </a>
    </div>
</div>

<script>
    // Datos originales
    const originalData = {
        name: document.getElementById('original-name')?.textContent || '',
        occupation: document.getElementById('original-occupation')?.textContent || '',
        gender: document.getElementById('original-gender')?.textContent || '',
        status: document.getElementById('original-status')?.textContent || '',
        description: document.getElementById('original-description')?.textContent || '',
        synopsis: document.getElementById('original-synopsis')?.textContent || '',
        phrases: JSON.parse(document.getElementById('original-phrases')?.textContent || '[]')
    };

    // Campos a traducir
    const fields = [
        { id: 'character-name', key: 'name' },
        { id: 'character-occupation', key: 'occupation' },
        { id: 'character-gender', key: 'gender' },
        { id: 'character-status', key: 'status' },
        { id: 'character-description', key: 'description' },
        { id: 'episode-synopsis', key: 'synopsis' }
    ];

    // Traducir página
    async function translateCharacterPage() {
        if (!simpsonTranslator) {
            alert('Error: Sistema de traducción no cargado');
            return;
        }

        simpsonTranslator.showLoading('button[onclick="translateCharacterPage()"]');

        try {
            // Traducir campos
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
            simpsonTranslator.hideLoading('button[onclick="translateCharacterPage()"]', 'Traducir a Español');
        }
    }

    // Traducir frases
    async function translatePhrases() {
        if (!originalData.phrases?.length) return;

        const container = document.getElementById('character-phrases');
        if (!container) return;

        container.innerHTML = '';

        for (const phrase of originalData.phrases) {
            const translated = await simpsonTranslator.translatePhrase(phrase);
            const element = document.createElement('blockquote');
            element.className = 'text-gray-600 bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500';
            element.textContent = `"${translated}"`;
            container.appendChild(element);
        }
    }

    // Auto-traducir
    document.addEventListener('DOMContentLoaded', () => {
        simpsonTranslator?.autoTranslateIfSpanish(translateCharacterPage);
    });
</script>
</body>
</html>
