<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SimpsonsService
{
    protected $client;
    protected $baseUrl = 'https://thesimpsonsapi.com/api/';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 10,
            'verify'   => false,
        ]);
    }

    /**
     * Obtener un personaje aleatorio (sin traducción)
     */
    public function getRandomCharacter()
    {
        try {
            $randomId = rand(1, 500);
            $response = $this->client->get("characters/{$randomId}");
            $character = json_decode($response->getBody(), true);
            
            if (!$character) {
                return $this->getFallbackCharacter();
            }

            // Solo agregar la URL de imagen
            $character['optimized_image'] = $this->getImageUrl($character);
            
            return $character;
            
        } catch (\Exception $e) {
            Log::error('Error obteniendo personaje aleatorio: ' . $e->getMessage());
            return $this->getFallbackCharacter();
        }
    }

    /**
     * Obtener personaje por ID (sin traducción)
     */
    public function getCharacterById($id)
    {
        try {
            $response = $this->client->get("characters/{$id}");
            $character = json_decode($response->getBody(), true);
            
            if ($character) {
                $character['optimized_image'] = $this->getImageUrl($character);
            }
            
            return $character;
        } catch (\Exception $e) {
            Log::error("Error obteniendo personaje ID {$id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener URL de imagen
     */
    private function getImageUrl($character, $size = '500')
    {
        $characterId = $character['id'] ?? 1;
        return "https://cdn.thesimpsonsapi.com/{$size}/character/{$characterId}.webp";
    }

    /**
     * Obtener múltiples personajes aleatorios
     */
    public function getMultipleRandomCharacters($count = 6)
    {
        try {
            $characters = [];
            $usedIds = [];
            
            for ($i = 0; $i < $count; $i++) {
                $randomId = rand(1, 500);
                
                if (in_array($randomId, $usedIds)) {
                    $i--;
                    continue;
                }
                
                $character = $this->getCharacterById($randomId);
                if ($character) {
                    $character['optimized_image'] = $this->getImageUrl($character, '200');
                    $characters[] = $character;
                    $usedIds[] = $randomId;
                }
            }
            
            return $characters;
            
        } catch (\Exception $e) {
            Log::error('Error obteniendo múltiples personajes: ' . $e->getMessage());
            return $this->getFallbackCharacters($count);
        }
    }

    /**
     * Personaje de respaldo
     */
    private function getFallbackCharacter()
    {
        return [
            'id' => 1,
            'name' => 'Homer Simpson',
            'age' => 39,
            'birthdate' => '1956-05-12',
            'description' => 'Homer Jay Simpson (born May 12, 1956) is a man from Springfield and the protagonist of the animated television series The Simpsons. He is a crude, ignorant, and slobbish individual, although he is fundamentally a good person and shows great caring and loyalty to his family, friends and on occasion, to those he barely knows or those he considers his enemies.',
            'gender' => 'Male',
            'occupation' => 'Safety Inspector',
            'phrases' => ['Doh!', 'Mmm... donuts...', 'Woo-hoo!'],
            'status' => 'Alive',
            'optimized_image' => 'https://cdn.thesimpsonsapi.com/500/character/1.webp',
            'first_appearance_ep' => [
                'name' => 'Simpsons Roasting on an Open Fire',
                'episode_number' => 1,
                'season' => 1,
                'airdate' => '1989-12-17',
                'synopsis' => 'When Mr. Burns announces that none of the workers will be getting Christmas bonuses and Marge reveals that she spent the extra Christmas gift money on getting Bart\'s "Mother" tattoo removed, Homer keeps his lack of funds for the holidays a secret and gets a job as a mall Santa.'
            ]
        ];
    }

    private function getFallbackCharacters($count)
    {
        $mainCharacters = [
            [
                'id' => 1,
                'name' => 'Homer Simpson',
                'occupation' => 'Safety Inspector',
                'optimized_image' => 'https://cdn.thesimpsonsapi.com/200/character/1.webp'
            ],
            [
                'id' => 2, 
                'name' => 'Marge Simpson',
                'occupation' => 'Homemaker',
                'optimized_image' => 'https://cdn.thesimpsonsapi.com/200/character/2.webp'
            ],
            [
                'id' => 3,
                'name' => 'Bart Simpson', 
                'occupation' => 'Student',
                'optimized_image' => 'https://cdn.thesimpsonsapi.com/200/character/3.webp'
            ],
            [
                'id' => 4,
                'name' => 'Lisa Simpson',
                'occupation' => 'Student', 
                'optimized_image' => 'https://cdn.thesimpsonsapi.com/200/character/4.webp'
            ]
        ];
        
        return array_slice($mainCharacters, 0, $count);
    }

    /**
     * Verificar si la API está funcionando
     */
    public function checkApiStatus()
    {
        try {
            $response = $this->client->get('characters/1');
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }
}