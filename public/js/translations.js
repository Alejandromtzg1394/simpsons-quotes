// translations.js - Lógica de traducción reutilizable
class SimpsonTranslator {
    constructor() {
        this.manualTranslations = {
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
            'Nuclear Power Plant': 'Planta de Energía Nuclear',

            // Textos de la galería
            'Character Gallery': 'Galería de Personajes',
            'Discover Springfield characters': 'Descubre los personajes de Springfield',
            'Back to home': 'Volver al inicio',
            'Occupation:': 'Ocupación:',
            'Age:': 'Edad:',
            'years': 'años',
            'View Details': 'Ver Detalles',
            'New Random Gallery': 'Nueva Galería Aleatoria',
            'Data provided by The Simpsons API': 'Datos proporcionados por The Simpsons API',
            'characters displayed': 'personajes mostrados',
            'Random Character': 'Personaje Aleatorio',
            'New Random Character': 'Nuevo Personaje Aleatorio',
            'View Gallery': 'Ver Galería'
        };

        this.iconicPhrases = [
            'doh', 'woo-hoo', 'ay caramba', 'eat my shorts', 'excellent',
            'ha-ha', 'why you little', 'mmmm', 'dude', 'whatever',
            'okily dokily', 'hi-diddly-ho', 'cowabunga', 'don\'t have a cow'
        ];
    }

    // Función para traducir texto
    async translateText(text, targetLang = 'es') {
        if (!text || text.trim() === '') return text;

        // Primero verificar si hay traducción manual
        if (this.manualTranslations[text]) {
            return this.manualTranslations[text];
        }

        // Verificar si el texto ya está en español
        if (this.isSpanish(text)) {
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
            return await this.translateWithGoogleAPI(text, targetLang);

        } catch (error) {
            console.warn('Error en traducción:', error);
            return text; // Devolver original si falla
        }
    }

    // Función fallback con Google Translate API
    async translateWithGoogleAPI(text, targetLang = 'es') {
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
    isSpanish(text) {
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
    async translatePhrase(phrase) {
        const lowerPhrase = phrase.toLowerCase();

        // Verificar si es una frase icónica
        for (const iconic of this.iconicPhrases) {
            if (lowerPhrase.includes(iconic)) {
                return phrase; // No traducir frases icónicas
            }
        }

        // Traducir frases descriptivas
        return await this.translateText(phrase);
    }

    // Utilidades de UI
    showLoading(buttonSelector) {
        const button = document.querySelector(buttonSelector);
        if (button) {
            button.innerHTML = '⏳ Traduciendo...';
            button.disabled = true;
        }
    }

    hideLoading(buttonSelector, originalText) {
        const button = document.querySelector(buttonSelector);
        if (button) {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }

    showMessage(message, type = 'info') {
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
    autoTranslateIfSpanish(callback) {
        const userLang = navigator.language || navigator.userLanguage;
        if (userLang.startsWith('es')) {
            // Pequeño delay para mejor UX
            setTimeout(() => {
                callback();
            }, 1000);
        }
    }
}

// Crear instancia global
const simpsonTranslator = new SimpsonTranslator();
