<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\Api\PagoFacilWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Webhook PagoFácil (sin autenticación - acceso público para callbacks)
Route::post('/pagofacil/callback', [PagoFacilWebhookController::class, 'handleCallback']);

Route::middleware('web')->group(function () {
    // Búsqueda global (solo administradores autenticados)
    Route::get('/search', [GlobalSearchController::class, 'search'])->middleware('auth');
});
