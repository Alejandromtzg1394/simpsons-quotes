<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personaje Aleatorio - Los Simpson</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <header class="text-center mb-8">
            <h1 class="text-4xl font-bold text-yellow-600 mb-2">Los Simpson</h1>
            <p class="text-lg text-gray-700">Personaje Aleatorio</p>
            
            <!-- Botón de traducción -->
            <div class="mt-4">
                <button onclick="translatePage()" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                    🌍 Traducir a Español
                </button>
                <button onclick="showOriginal()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ml-2">
                    🔄 Mostrar Original
                </button>
            </div>
        </header>

        @if(isset($character) && $character)
            <!-- Tarjeta del Personaje -->
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Imagen del Personaje -->
                <div class="bg-yellow-500 p-4">
                    <img 
                        src="{{ $character['optimized_image'] }}" 
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
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                🔄 Nuevo Personaje Aleatorio
            </a>
            <a href="{{ route('gallery') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                🖼️ Ver Galería
            </a>
        </div>
    </div>

    <!-- Script de Traducción -->
    <script>
        // Datos originales
        let originalData = {
            name: document.getElementById('original-name')?.textContent || '',
            occupation: document.getElementById('original-occupation')?.textContent || '',
            gender: document.getElementById('original-gender')?.textContent || '',
            status: document.getElementById('original-status')?.textContent || '',
            description: document.getElementById('original-description')?.textContent || '',
            synopsis: document.getElementById('original-synopsis')?.textContent || '',
            phrases: JSON.parse(document.getElementById('original-phrases')?.textContent || '[]')
        };

        // Traducciones manuales para términos comunes
        const manualTranslations = {
            // Géneros
            'Male': 'Masculino',
            'Female': 'Femenino',
            'Other': 'Otro',
            'Unknown': 'Desconocido',
            
            // Estados
            'Alive': 'Vivo',
            'Deceased': 'Fallecido',
            'Presumed Dead': 'Presuntamente Muerto',
            
            // Ocupaciones comunes
            'Safety Inspector': 'Inspector de Seguridad',
            'Nuclear Safety Inspector': 'Inspector de Seguridad Nuclear',
            'Homemaker': 'Ama de Casa',
            'Student': 'Estudiante',
            'Elementary School Student': 'Estudiante de Primaria',
            'Baby': 'Bebé',
            'Owner': 'Dueño',
            'Nuclear Power Plant Owner': 'Dueño de la Planta Nuclear',
            'Assistant': 'Asistente',
            'Executive Assistant': 'Asistente Ejecutivo',
            'Television Clown': 'Payaso de Televisión',
            'School Bully': 'Bravucón de la Escuela',
            'Store Owner': 'Dueño de Tienda',
            'Bartender': 'Cantinero',
            'Police Chief': 'Jefe de Policía',
            'Police Officer': 'Oficial de Policía',
            'Teacher': 'Profesor',
            'Scientist': 'Científico',
            'Inventor': 'Inventor',
            'Cartoonist': 'Dibujante',
            'Artist': 'Artista',
            'Actor': 'Actor',
            'Musician': 'Músico',
            'Doctor': 'Médico',
            'Attorney': 'Abogado',
            'Mayor': 'Alcalde',
            'Unemployed': 'Desempleado',
            'Retired': 'Jubilado',
            'Veteran': 'Veterano',
            
            // Términos comunes
            'Unknown occupation': 'Ocupación desconocida',
            'The Simpsons': 'Los Simpson',
            'Springfield': 'Springfield',
            'Nuclear Power Plant': 'Planta de Energía Nuclear'
        };

        // Frases icónicas que NO se deben traducir
        const iconicPhrases = [
            'doh', 'woo-hoo', 'ay caramba', 'eat my shorts', 'excellent',
            'ha-ha', 'why you little', 'mmmm', 'dude', 'whatever',
            'okily dokily', 'hi-diddly-ho', 'cowabunga', 'don\'t have a cow'
        ];

        // Función para traducir texto usando la API del navegador
        async function translateText(text, targetLang = 'es') {
            if (!text || text.trim() === '') return text;
            
            // Primero verificar si hay traducción manual
            if (manualTranslations[text]) {
                return manualTranslations[text];
            }
            
            // Verificar si el texto ya está en español
            if (isSpanish(text)) {
                return text;
            }
            
            try {
                // Usar la Translation API del navegador
                if ('translations' in navigator) {
                    const translation = await navigator.translations.translate({
                        text: text,
                        targetLanguage: targetLang,
                        sourceLanguage: 'en'
                    });
                    return translation.translatedText || text;
                }
                
                // Fallback: usar Google Translate API v2 (gratuita)
                return await translateWithGoogleAPI(text, targetLang);
                
            } catch (error) {
                console.warn('Error en traducción:', error);
                return text; // Devolver original si falla
            }
        }

        // Función fallback con Google Translate API
        async function translateWithGoogleAPI(text, targetLang = 'es') {
            try {
                const response = await fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`);
                const data = await response.json();
                return data[0][0][0] || text;
            } catch (error) {
                console.warn('Error con Google Translate:', error);
                return text;
            }
        }

        // Detectar si el texto ya está en español
        function isSpanish(text) {
            const spanishWords = ['el', 'la', 'los', 'las', 'de', 'en', 'y', 'que', 'es', 'un', 'una'];
            const englishWords = ['the', 'and', 'is', 'in', 'of', 'to', 'a', 'an'];
            
            let spanishCount = 0;
            let englishCount = 0;
            
            const words = text.toLowerCase().split(/\s+/);
            
            words.forEach(word => {
                if (spanishWords.includes(word)) spanishCount++;
                if (englishWords.includes(word)) englishCount++;
            });
            
            return spanishCount > englishCount;
        }

        // Traducir una frase (con detección de frases icónicas)
        async function translatePhrase(phrase) {
            const lowerPhrase = phrase.toLowerCase();
            
            // Verificar si es una frase icónica
            for (const iconic of iconicPhrases) {
                if (lowerPhrase.includes(iconic)) {
                    return phrase; // No traducir frases icónicas
                }
            }
            
            // Traducir frases descriptivas
            return await translateText(phrase);
        }

        // Traducir toda la página
        async function translatePage() {
            try {
                // Mostrar loading
                showLoading();
                
                // Traducir campos individuales
                if (originalData.name) {
                    document.getElementById('character-name').textContent = await translateText(originalData.name);
                }
                
                if (originalData.occupation) {
                    document.getElementById('character-occupation').textContent = await translateText(originalData.occupation);
                }
                
                if (originalData.gender) {
                    document.getElementById('character-gender').textContent = await translateText(originalData.gender);
                }
                
                if (originalData.status) {
                    document.getElementById('character-status').textContent = await translateText(originalData.status);
                }
                
                if (originalData.description) {
                    document.getElementById('character-description').textContent = await translateText(originalData.description);
                }
                
                if (originalData.synopsis) {
                    document.getElementById('episode-synopsis').textContent = await translateText(originalData.synopsis);
                }
                
                // Traducir frases
                if (originalData.phrases && originalData.phrases.length > 0) {
                    const phrasesContainer = document.getElementById('character-phrases');
                    if (phrasesContainer) {
                        phrasesContainer.innerHTML = '';
                        for (const phrase of originalData.phrases) {
                            const translatedPhrase = await translatePhrase(phrase);
                            const phraseElement = document.createElement('p');
                            phraseElement.className = 'text-gray-600 bg-yellow-50 p-3 rounded-lg';
                            phraseElement.textContent = `"${translatedPhrase}"`;
                            phrasesContainer.appendChild(phraseElement);
                        }
                    }
                }
                
                hideLoading();
                showMessage('✅ Traducción completada', 'success');
                
            } catch (error) {
                console.error('Error en traducción:', error);
                hideLoading();
                showMessage('❌ Error en la traducción', 'error');
            }
        }

        // Mostrar texto original
        function showOriginal() {
            if (originalData.name) {
                document.getElementById('character-name').textContent = originalData.name;
            }
            if (originalData.occupation) {
                document.getElementById('character-occupation').textContent = originalData.occupation;
            }
            if (originalData.gender) {
                document.getElementById('character-gender').textContent = originalData.gender;
            }
            if (originalData.status) {
                document.getElementById('character-status').textContent = originalData.status;
            }
            if (originalData.description) {
                document.getElementById('character-description').textContent = originalData.description;
            }
            if (originalData.synopsis) {
                document.getElementById('episode-synopsis').textContent = originalData.synopsis;
            }
            
            // Restaurar frases originales
            if (originalData.phrases && originalData.phrases.length > 0) {
                const phrasesContainer = document.getElementById('character-phrases');
                if (phrasesContainer) {
                    phrasesContainer.innerHTML = '';
                    originalData.phrases.forEach(phrase => {
                        const phraseElement = document.createElement('p');
                        phraseElement.className = 'text-gray-600 bg-yellow-50 p-3 rounded-lg';
                        phraseElement.textContent = `"${phrase}"`;
                        phrasesContainer.appendChild(phraseElement);
                    });
                }
            }
            
            showMessage('🔠 Texto original restaurado', 'info');
        }

        // Utilidades de UI
        function showLoading() {
            const button = document.querySelector('button[onclick="translatePage()"]');
            if (button) {
                button.innerHTML = '⏳ Traduciendo...';
                button.disabled = true;
            }
        }

        function hideLoading() {
            const button = document.querySelector('button[onclick="translatePage()"]');
            if (button) {
                button.innerHTML = '🌍 Traducir a Español';
                button.disabled = false;
            }
        }

        function showMessage(message, type = 'info') {
            // Crear o actualizar mensaje
            let messageDiv = document.getElementById('translation-message');
            if (!messageDiv) {
                messageDiv = document.createElement('div');
                messageDiv.id = 'translation-message';
                messageDiv.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50';
                document.body.appendChild(messageDiv);
            }
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white'
            };
            
            messageDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${colors[type]}`;
            messageDiv.textContent = message;
            
            // Auto-ocultar después de 3 segundos
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }

        // Auto-traducir si el usuario prefiere español
        document.addEventListener('DOMContentLoaded', function() {
            const userLang = navigator.language || navigator.userLanguage;
            if (userLang.startsWith('es')) {
                // Pequeño delay para mejor UX
                setTimeout(() => {
                    translatePage();
                }, 1000);
            }
        });
    </script>
</body>
</html>