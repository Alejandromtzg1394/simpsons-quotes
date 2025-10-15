<?php

use App\Http\Controllers\SimpsonsController;
use Illuminate\Support\Facades\Route;

// Página principal - Personaje aleatorio
Route::get('/', [SimpsonsController::class, 'index'])->name('index');

// Nuevo personaje aleatorio
Route::get('/random', [SimpsonsController::class, 'randomCharacter'])->name('random');

// Personaje por ID
Route::get('/character/{id}', [SimpsonsController::class, 'characterById'])->name('character');

// Locacion por ID
// Ruta dada por el API ---> https://thesimpsonsapi.com/api/locations/1
Route::get('/locationscharacter/{id}', [SimpsonsController::class, 'locationDetails'])->name('location.details');

// Galería de personajes
Route::get('/gallery', [SimpsonsController::class, 'gallery'])->name('gallery');

// En routes/web.php
Route::get('/locations/{page?}', [SimpsonsController::class, 'locations'])
    ->name('locations')
    ->where('page', '[0-9]+');

/*
// Detalles de una location específica
Route::get('/locations/{id}', [SimpsonsController::class, 'locationDetails'])
    ->name('location.details')
    ->where('id', '[0-9]+');
*/

// API endpoints
Route::get('/api/random', [SimpsonsController::class, 'apiRandom']);
Route::get('/api/status', [SimpsonsController::class, 'apiStatus']);

