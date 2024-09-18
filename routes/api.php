<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/scan-qr', [\App\Http\Controllers\Api\ScanController::class, 'scan']);
Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index']);
Route::get('/event/{slug}', [\App\Http\Controllers\Api\EventController::class, 'show']);
// Route::post('/manual-check-in', [\App\Http\Controllers\Api\ScanController::class, 'manualCheckIn']);

Route::fallback(function() {
    abort(404, 'API Resource Not Found.');
});
