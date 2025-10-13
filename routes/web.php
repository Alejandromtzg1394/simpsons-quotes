<?php

use App\Http\Controllers\SimpsonsController;
use Illuminate\Support\Facades\Route;

// Página principal - Personaje aleatorio
Route::get('/', [SimpsonsController::class, 'index'])->name('index');

// Nuevo personaje aleatorio
Route::get('/random', [SimpsonsController::class, 'randomCharacter'])->name('random');

// Personaje por ID
Route::get('/character/{id}', [SimpsonsController::class, 'characterById'])->name('character');

// Galería de personajes
Route::get('/gallery', [SimpsonsController::class, 'gallery'])->name('gallery');

// Búsqueda
Route::get('/search', [SimpsonsController::class, 'search'])->name('search');

// API endpoints
Route::get('/api/random', [SimpsonsController::class, 'apiRandom']);
Route::get('/api/status', [SimpsonsController::class, 'apiStatus']);

