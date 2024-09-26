<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\UtilisateurProjetController;
use App\Http\Controllers\TacheController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\FichierController;





Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
   
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('projets', ProjetController::class);
    
    Route::get('user/projets', [ProjetController::class, 'userProjets']);
    Route::get('/accept-invitation/{projetId}', [UtilisateurProjetController::class, 'accepterInvitation'])->name('accept.invitation');
    Route::post('/projets/{projectId}/invite', [ProjetController::class, 'inviteUser']);
    // Routes pour les utilisateurs-projets
Route::post('/projets/{projetId}/utilisateurs', [UtilisateurProjetController::class, 'store']);
Route::get('/projets/{projetId}/utilisateurs', [UtilisateurProjetController::class, 'listUtilisateurs']);
Route::delete('/projets/{projetId}/utilisateurs/{utilisateurId}', [UtilisateurProjetController::class, 'deleteUtilisateur']);



Route::apiResource('taches', TacheController::class);
Route::post('taches/{id}/assigner', [TacheController::class, 'assignTask']);
Route::get('/projets/{projetId}/taches', [TacheController::class, 'tasksByProject']);



Route::apiResource('/commentaires', CommentaireController::class);

Route::get('/Task/{TaskId}/commentaires', [CommentaireController::class, 'index']);
Route::apiResource('fichiers', FichierController::class);
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id; // Autoriser uniquement l'utilisateur authentifié à écouter son propre canal
});


  
});



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
