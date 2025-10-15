<?php

namespace App\Http\Controllers;

use App\Services\SimpsonsService;
use Illuminate\Support\Facades\Http;

class SimpsonsController extends Controller
{
    protected $simpsonsService;

    public function __construct(SimpsonsService $simpsonsService)
    {
        $this->simpsonsService = $simpsonsService;
    }

    // Página de inicio - Muestra personaje aleatorio
    public function index()
    {
        $character = $this->simpsonsService->getRandomCharacter();
        return view('simpsons.index', compact('character'));
    }

    // Obtener nuevo personaje aleatorio
    public function randomCharacter()
    {
        $character = $this->simpsonsService->getRandomCharacter();
        return view('simpsons.index', compact('character'));
    }

    // Obtener personaje por ID específico
    public function characterById($id)
    {
        $character = $this->simpsonsService->getCharacterById($id);

        if (!$character) {
            return redirect()->route('index')->with('error', 'Personaje no encontrado');
        }

        return view('simpsons.character', compact('character'));
    }

    // Galería de personajes
    public function gallery()
    {
        $characters = $this->simpsonsService->getMultipleRandomCharacters(6);
        return view('simpsons.gallery', compact('characters'));
    }


    // API Endpoint JSON
    public function apiRandom()
    {
        $character = $this->simpsonsService->getRandomCharacter();
        return response()->json($character);
    }

    // Verificar estado de la API
    public function apiStatus()
    {
        $status = $this->simpsonsService->checkApiStatus();
        return response()->json([
            'status' => $status ? 'en línea' : 'fuera de línea',
            'timestamp' => now()->toDateTimeString()
        ]);
    }




    public function locations($page = 1)
    {
        try {
            // Validar página
            $page = max(1, intval($page));

            // Hacer request a la API
            $response = Http::get("https://thesimpsonsapi.com/api/locations", [
                'page' => $page
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return view('simpsons.locations', [
                    'locations' => $data['results'] ?? [],
                    'currentPage' => $page,
                    'totalPages' => $data['pages'] ?? 1,
                    'totalLocations' => $data['count'] ?? 0,
                    'nextPage' => $data['next'] ?? null,
                    'prevPage' => $data['prev'] ?? null
                ]);
            } else {
                throw new Exception('Error al cargar los lugares');
            }

        } catch (Exception $e) {
            return view('simpsons.locations', [
                'locations' => [],
                'currentPage' => 1,
                'totalPages' => 1,
                'totalLocations' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function locationDetails($id)
    {
        try {
            // Hacer request a la API para obtener UNA location específica
            $url = "https://thesimpsonsapi.com/api/locations/{$id}";
            $response = file_get_contents($url);

            if ($response === false) {
                throw new Exception('Error al cargar el lugar de la API');
            }

            $location = json_decode($response, true);

            return view('simpsons.location-details', [
                'location' => $location
            ]);

        } catch (Exception $e) {
            return view('simpsons.location-details', [
                'location' => null,
                'error' => $e->getMessage()
            ]);
        }
    }

}
