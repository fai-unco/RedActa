<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\IssuerController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AnexoController;
use App\Http\Controllers\IssuerSettingsController;
use App\Http\Controllers\HeadingController;
use App\Http\Controllers\OperativeSectionBeginningController;
use App\Http\Controllers\StampController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\DocumentSharedAccessController;
use App\Http\Controllers\RedactaUserController;
use App\Http\Controllers\VisibilityLevelController;
use App\Http\Controllers\AccessModeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMembershipController;
use App\Http\Controllers\SignupInvitationController;
use App\Http\Controllers\RoleController;

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
  Route::get('/documents/search', [DocumentController::class, 'search']);
  Route::apiResource('documents', DocumentController::class);
  Route::apiResource('anexos', AnexoController::class);
  Route::post('/logout', [AuthenticationController::class, 'logout']);
  Route::apiResource('files', FileController::class);
  Route::apiResource('issuer_settings', IssuerSettingsController::class);
  Route::apiResource('headings', HeadingController::class);
  Route::apiResource('document_types', DocumentTypeController::class);
  Route::apiResource('issuers', IssuerController::class);
  Route::apiResource('operative_section_beginnings', OperativeSectionBeginningController::class);
  Route::apiResource('stamps', StampController::class);
  Route::apiResource('signatures', SignatureController::class);
  Route::apiResource('document_shared_accesses', DocumentSharedAccessController::class);
  Route::apiResource('redacta_users', RedactaUserController::class);
  Route::apiResource('visibility_levels', VisibilityLevelController::class);
  Route::patch('/documents/{id}/visibility_level', [DocumentController::class, 'setVisibilityLevel']);
  Route::apiResource('access_modes', AccessModeController::class);
  Route::post('document_shared_access/{id}/notify', [DocumentSharedAccessController::class, 'notify']);
  Route::apiResource('groups', GroupController::class);
  Route::apiResource('group_memberships', GroupMembershipController::class);
  Route::get('/export_anexo/{id}', [DocumentController::class, 'exportAnexo']);
  Route::apiResource('signup_invitations', SignupInvitationController::class);
  Route::apiResource('roles', RoleController::class);
  Route::patch('/redacta_users/{redacta_user}/reactivate', [RedactaUserController::class, 'restore']);
  Route::patch('/issuers/{issuer}/reactivate', [IssuerController::class, 'restore']);
});
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::get('/validate_signup_invitation', [AuthenticationController::class, 'validateSignUpInvitation']);

// Rutas públicas para restablecer contraseña
Route::post('/password/forgot', [AuthenticationController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthenticationController::class, 'resetPassword']);
