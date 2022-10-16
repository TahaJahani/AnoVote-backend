<?php

use App\Http\Controllers\PollController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('login')->any('/access-denied', function () {
    return response()->json(['error' => 'access denied'], 403);
});

Route::get('/test', [TestController::class, 'test']);

Route::post('/auth/login', [UserController::class, 'login']);

Route::prefix('poll')->middleware(['auth:sanctum'])->group(function() {
    Route::middleware(['ability:admin'])->post('/create', [PollController::class, 'make']);
    Route::get('/list', [PollController::class, 'listAll']);
    Route::get('/{slug}', [PollController::class, 'get']);
    Route::get('/{slug}/results', [PollController::class, 'results']);
});

Route::prefix('vote')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/', [VoteController::class, 'submit']);
});
