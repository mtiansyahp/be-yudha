<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PelatihanController;
use App\Http\Controllers\Api\PenilaianController;
use App\Http\Controllers\Api\LogPenilaianController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login',  [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::apiResource('users', UserController::class);
Route::apiResource('pelatihans', PelatihanController::class);
Route::apiResource('penilaians', PenilaianController::class);
Route::apiResource('log-penilaians', LogPenilaianController::class);
/* ↧ tambahan kustom  */
Route::get(
    'log-penilaians/penilaian/{penilaian_id}',
    [LogPenilaianController::class, 'byPenilaian']
);

Route::get(
    'log-penilaians/penilaian/{penilaian_id}/user/{user_id}',
    [LogPenilaianController::class, 'byPenilaianUser']
);
Route::post('penilaian/proses/{user_id}/{pelatihan_id}', [PenilaianController::class, 'prosesPenilaian']);
Route::post(
    '/penilaian/tsukamoto-batch/{pelatihan}',
    [PenilaianController::class, 'prosesBatchTsukamoto']
);
Route::post('/penilaian/proses-semua', [PenilaianController::class, 'prosesSemua']);
Route::post('/penilaian/proses-batch/{pelatihanId}', [PenilaianController::class, 'prosesBatch']);

Route::get('log-penilaian/{id}', [LogPenilaianController::class, 'show']);

Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
Route::delete('/penilaian/pelatihan/{pelatihanId}', [PenilaianController::class, 'destroyByPelatihan']);
