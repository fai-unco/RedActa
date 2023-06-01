<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\IssuerController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AnexoController;


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
Route::middleware('auth:sanctum')->group(function () {
  Route::apiResource('documents', DocumentController::class);
  Route::apiResource('anexos', AnexoController::class);
  Route::post('/logout', [AuthenticationController::class, 'logout']);
  Route::apiResource('files', FileController::class);
});

Route::apiResource('document_types', DocumentTypeController::class);
Route::apiResource('issuers', IssuerController::class);


Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::get('/export', [DocumentController::class, 'export']);

Route::get('/export_anexo/{id}', [DocumentController::class, 'exportAnexo']);
