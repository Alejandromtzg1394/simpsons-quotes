<?php

namespace App\Http\Controllers;

use App\Services\SimpsonsService;
use Illuminate\Http\Request;

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

    // Búsqueda de personajes
    public function search(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $characters = [];
        
        if (!empty($searchTerm)) {
            $characters = $this->simpsonsService->searchCharacters($searchTerm);
        }
        
        return view('simpsons.search', compact('characters', 'searchTerm'));
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
}