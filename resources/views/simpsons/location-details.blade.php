<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $location['name'] ?? 'Lugar' }} - Los Simpson</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/translations.js') }}"></script>
</head>
<body class="bg-yellow-100 min-h-screen">
<div class="container mx-auto px-4 py-8">
    <!-- Navegación -->
    <div class="mb-6">
        <a href="{{ route('index') }}" class="text-blue-600 hover:underline">← Inicio</a>
        <span class="mx-2">/</span>
        <a href="{{ route('locations') }}" class="text-blue-600 hover:underline">Lugares</a>
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ $location['name'] ?? 'Detalles' }}</span>
    </div>

    @if(isset($location) && $location)
    <!-- Tarjeta detallada del lugar -->
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Imagen -->
        <div class="bg-yellow-500 p-6 text-center">
            <img
                src="https://cdn.thesimpsonsapi.com/1280/location/{{ $location['id'] }}.webp"
                alt="{{ $location['name'] }}"
                class="w-96 h-64 mx-auto object-cover rounded-lg"
                onerror="this.onerror=null; this.src='https://cdn.thesimpsonsapi.com/800/location/1.webp';"
            >
        </div>

        <!-- Información -->
        <div class="p-6">
            <h1 class="text-4xl font-bold text-gray-800 text-center mb-2" id="location-name">{{ $location['name'] }}</h1>

            <!-- Datos en grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="text-center">
                    <span class="font-semibold block text-lg">Ciudad</span>
                    <p class="text-gray-600" id="location-town">{{ $location['town'] ?? 'Springfield' }}</p>
                </div>
                <div class="text-center">
                    <span class="font-semibold block text-lg">Uso</span>
                    <p class="text-gray-600" id="location-use">{{ $location['use'] ?? 'No especificado' }}</p>
                </div>
            </div>

            <!-- Descripción si existe -->
            @if(isset($location['description']))
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-700 mb-3">Descripción</h2>
                <p class="text-gray-600 leading-relaxed" id="location-description">{{ $location['description'] }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Datos originales ocultos para traducción -->
    <div id="original-data" style="display: none;">
        <span id="original-name">{{ $location['name'] }}</span>
        <span id="original-town">{{ $location['town'] ?? 'Springfield' }}</span>
        <span id="original-use">{{ $location['use'] ?? 'No especificado' }}</span>
        @if(isset($location['description']))
        <span id="original-description">{{ $location['description'] }}</span>
        @endif
    </div>
    @else
    <!-- Error -->
    <div class="text-center bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <p>{{ $error ?? 'No se pudo cargar la información del lugar.' }}</p>
    </div>
    @endif

    <!-- Botones de acción -->
    <div class="text-center mt-8 space-x-4">
        <a href="{{ route('locations') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
            ← Volver a Lugares
        </a>
    </div>
</div>

<script>
    // Datos originales para traducción
    const originalData = {
        name: document.getElementById('original-name')?.textContent || '',
        town: document.getElementById('original-town')?.textContent || '',
        use: document.getElementById('original-use')?.textContent || '',
        description: document.getElementById('original-description')?.textContent || ''
    };

    // Campos a traducir
    const fields = [
        { id: 'location-name', key: 'name' },
        { id: 'location-town', key: 'town' },
        { id: 'location-use', key: 'use' },
        { id: 'location-description', key: 'description' }
    ];

    // Traducir página
    async function translateLocationPage() {
        if (!simpsonTranslator) {
            alert('Error: Sistema de traducción no cargado');
            return;
        }

        try {
            // Traducir campos
            for (const field of fields) {
                if (originalData[field.key]) {
                    document.getElementById(field.id).textContent =
                        await simpsonTranslator.translateText(originalData[field.key]);
                }
            }

            simpsonTranslator.showMessage('Traducción completada', 'success');
        } catch (error) {
            console.error('Error:', error);
            simpsonTranslator.showMessage('Error en traducción', 'error');
        }
    }

    // Auto-traducir
    document.addEventListener('DOMContentLoaded', () => {
        simpsonTranslator?.autoTranslateIfSpanish(translateLocationPage);
    });
</script>
</body>
</html>
