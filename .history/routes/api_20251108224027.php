<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MeetReportController; // <-- Make sure this line is here

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

// This is the default API route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// --- THIS IS THE MISSING ROUTE ---
// This is the route the extension is looking for.
Route::middleware(['auth:sanctum', 'role:teacher'])
    ->post('/sync-meet-report', [MeetReportController::class, 'store']);